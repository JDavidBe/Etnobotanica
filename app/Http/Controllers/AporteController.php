<?php

namespace App\Http\Controllers;

use App\Models\Aporte;
use App\Models\AporteImagen;
use App\Models\Categoria;
use App\Models\Comentario;
use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AporteController extends Controller
{
    public function create(Request $request)
    {
        $categorias = Categoria::all();

        $aportesAprobados = Aporte::with('imagenes')
            ->where('estado', 'aprobado')
            ->when($request->query('cat'), fn($q, $cat) => $q->where('categoria', $cat))
            ->orderByDesc('creado_en')
            ->paginate(9);

        return view('public.aportar', compact('categorias', 'aportesAprobados'));
    }

    public function show(Aporte $aporte)
    {
        abort_if($aporte->estado !== 'aprobado', 404);

        $aporte->load('imagenes');

        $ip = request()->ip();
        $comentarios = $aporte->comentarios()
            ->withCount('likes')
            ->get()
            ->map(fn($c) => array_merge($c->toArray(), [
                'ya_likeado' => $c->yaLikeado($ip),
            ]));

        return view('public.aporte-detalle', compact('aporte', 'comentarios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_planta'      => 'required|string|max:150',
            'cientifico'         => 'nullable|string|max:150',
            'categoria'          => 'required|string|max:100',
            'uso'                => 'required|string|max:255',
            'preparacion'        => 'required|string',
            'relato'             => 'nullable|string',
            'imagenes'           => 'nullable|array|max:5',
            'imagenes.*'         => 'image|mimes:jpg,jpeg,png,webp|max:4096',
            'consentimiento'     => 'accepted',
            'consentimiento_img' => 'accepted',
        ], [
            'nombre_planta.required'      => 'El nombre de la planta es obligatorio.',
            'categoria.required'          => 'Debes seleccionar una categoría.',
            'uso.required'                => 'El uso principal es obligatorio.',
            'preparacion.required'        => 'La preparación es obligatoria.',
            'consentimiento.accepted'     => 'Debes aceptar el consentimiento informado.',
            'consentimiento_img.accepted' => 'Debes confirmar que tienes los derechos sobre las imágenes adjuntas.',
            'imagenes.*.image'            => 'Cada archivo debe ser una imagen.',
            'imagenes.*.max'              => 'Cada imagen no debe superar 4 MB.',
            'imagenes.max'                => 'Puedes subir máximo 5 imágenes.',
        ]);

        // Imagen principal (primera) en img_path para compatibilidad legado
        $imgPath = null;
        if ($request->hasFile('imagenes') && count($request->file('imagenes')) > 0) {
            $imgPath = $request->file('imagenes')[0]->store('aportes', 'public');
        }

        $aporte = Aporte::create([
            'nombre_planta' => $request->nombre_planta,
            'cientifico'    => $request->cientifico ?? '',
            'categoria'     => $request->categoria,
            'uso'           => $request->uso,
            'preparacion'   => $request->preparacion,
            'relato'        => $request->relato,
            'img_path'      => $imgPath,
            'enviado_por'   => auth()->check() ? auth()->user()->name : 'Anónimo',
            'ip_origen'     => $request->ip(),
            'estado'        => 'pendiente',
            'user_id'       => auth()->id(),
        ]);

        // Guardar imágenes adicionales en tabla aporte_imagenes
        if ($request->hasFile('imagenes')) {
            foreach ($request->file('imagenes') as $orden => $file) {
                $path = $file->store('aportes', 'public');
                AporteImagen::create([
                    'aporte_id' => $aporte->id,
                    'img_path'  => $path,
                    'orden'     => $orden,
                ]);
            }
        }

        Notificacion::crearParaAdmins(
            'nuevo_aporte',
            'Nuevo aporte para revisar',
            "«{$aporte->nombre_planta}» enviado por {$aporte->enviado_por} está pendiente de moderación.",
            route('admin.moderacion.index')
        );

        return redirect()->route('home')
            ->with('success', '¡Gracias! Tu aporte fue enviado para moderación.');
    }
}
