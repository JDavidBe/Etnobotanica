<?php

namespace App\Http\Controllers;

use App\Models\Aporte;
use App\Models\Categoria;
use App\Models\Comentario;
use App\Models\Notificacion;
use Illuminate\Http\Request;

class AporteController extends Controller
{
    public function create(Request $request)
    {
        $categorias = Categoria::all();

        $aportesAprobados = Aporte::where('estado', 'aprobado')
            ->when($request->query('cat'), fn($q, $cat) => $q->where('categoria', $cat))
            ->orderByDesc('creado_en')
            ->paginate(9);

        return view('public.aportar', compact('categorias', 'aportesAprobados'));
    }

    public function show(Aporte $aporte)
    {
        abort_if($aporte->estado !== 'aprobado', 404);

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
            'nombre_planta'  => 'required|string|max:150',
            'cientifico'     => 'nullable|string|max:150',
            'categoria'      => 'required|string|max:100',
            'uso'            => 'required|string|max:255',
            'preparacion'    => 'required|string',
            'relato'         => 'nullable|string',
            'imagen'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'consentimiento' => 'accepted',
        ], [
            'nombre_planta.required'  => 'El nombre de la planta es obligatorio.',
            'categoria.required'      => 'Debes seleccionar una categoría.',
            'uso.required'            => 'El uso principal es obligatorio.',
            'preparacion.required'    => 'La preparación es obligatoria.',
            'consentimiento.accepted' => 'Debes aceptar el consentimiento informado (RF-06).',
            'imagen.image'            => 'El archivo debe ser una imagen.',
            'imagen.max'              => 'La imagen no debe superar 4 MB.',
        ]);

        $imgPath = null;
        if ($request->hasFile('imagen')) {
            $imgPath = $request->file('imagen')->store('aportes', 'public');
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

        // Notificar a admins y moderadores
        Notificacion::crearParaAdmins(
            'nuevo_aporte',
            'Nuevo aporte para revisar',
            "«{$aporte->nombre_planta}» enviado por {$aporte->enviado_por} está pendiente de moderación.",
            route('admin.moderacion.index')
        );

        return redirect()->route('home')
            ->with('success', '¡Gracias! Tu aporte fue enviado para moderación (RF-07).');
    }
}
