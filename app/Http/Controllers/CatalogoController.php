<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Planta;
use App\Models\Aporte;
use App\Models\Subtema;
use Illuminate\Http\Request;

class CatalogoController extends Controller
{
    public function home()
    {
        $categorias = Categoria::all();

        $aportesAprobados = Aporte::where('estado', 'aprobado')
            ->orderByDesc('creado_en')
            ->limit(6)
            ->get();

        return view('public.home', compact('categorias', 'aportesAprobados'));
    }

    public function catalogo()
    {
        $categorias = Categoria::with(['subtemas' => function ($q) {
            $q->withCount('plantas');
        }])->get();

        return view('public.catalogo', compact('categorias'));
    }

    public function categoria(Categoria $categoria)
    {
        $categoria->load(['subtemas' => function ($q) {
            $q->withCount('plantas');
        }])->loadCount('plantas');
        return view('public.subtemas', compact('categoria'));
    }

    public function categoriaTodos(Categoria $categoria)
    {
        $filtro = request('filtro', 'todas');

        $query = $categoria->plantas()->with(['categoria', 'subtema']);

        if ($filtro === 'verificada') {
            $query->where('verificada', true);
        } elseif ($filtro === 'pendiente') {
            $query->where('verificada', false);
        }

        $plantas = $query->orderBy('nombre')->get();

        // Crear un objeto subtema virtual para mantener compatibilidad con la vista
        $subtema = (object) [
            'id' => null,
            'nombre' => 'Todos',
            'categoria_id' => $categoria->id
        ];

        return view('public.plantas', compact('categoria', 'subtema', 'plantas', 'filtro'));
    }

    public function subtema(Categoria $categoria, Subtema $subtema)
    {
        $filtro = request('filtro', 'todas');

        // Si el subtema es "Todos", mostrar todas las plantas de la categoría
        if ($subtema->nombre === 'Todos') {
            $query = $categoria->plantas()->with(['categoria', 'subtema']);
        } else {
            $query = $subtema->plantas()->with(['categoria', 'subtema']);
        }

        if ($filtro === 'verificada') {
            $query->where('verificada', true);
        } elseif ($filtro === 'pendiente') {
            $query->where('verificada', false);
        }

        $plantas = $query->orderBy('nombre')->get();

        return view('public.plantas', compact('categoria', 'subtema', 'plantas', 'filtro'));
    }

    public function ficha(Planta $planta)
    {
        $planta->load(['categoria', 'subtema', 'categorias']);

        $ip = request()->ip();
        $comentarios = $planta->comentarios()
            ->withCount('likes')
            ->get()
            ->map(fn($c) => array_merge($c->toArray(), [
                'ya_likeado' => $c->yaLikeado($ip),
            ]));

        return view('public.ficha', compact('planta', 'comentarios'));
    }

    /**
     * Mapea términos coloquiales de síntomas a las palabras que sí
     * aparecen en los datos (uso, descripción, instrucciones, contexto),
     * para que buscar "diarrea" también encuentre plantas etiquetadas
     * como "digestivo" o "cólico", aunque la palabra exacta no esté escrita.
     */
    private function sinonimosSintomas(): array
    {
        return [
            'diarrea'          => ['colico', 'colicos', 'antidiarreico', 'digestivo', 'malestar estomacal'],
            'dolor de estomago' => ['digestivo', 'colico', 'colicos', 'malestar estomacal'],
            'dolor de cabeza'  => ['cefalea', 'calmante'],
            'gripa'            => ['resfriado', 'catarro', 'respiratorio', 'tos'],
            'gripe'            => ['resfriado', 'catarro', 'respiratorio', 'tos'],
            'tos'              => ['respiratorio', 'expectorante'],
            'insomnio'         => ['calmante', 'relajante', 'ansiedad'],
            'ansiedad'         => ['calmante', 'relajante', 'nervios'],
            'nervios'          => ['calmante', 'relajante', 'ansiedad'],
            'golpe'            => ['antiinflamatorio', 'hematoma', 'inflamacion'],
            'golpes'           => ['antiinflamatorio', 'hematoma', 'inflamacion'],
            'quemadura'        => ['piel', 'heridas', 'cicatrizante'],
            'herida'           => ['piel', 'cicatrizante'],
            'heridas'          => ['piel', 'cicatrizante'],
            'gases'            => ['carminativo', 'digestivo'],
        ];
    }

    /**
     * Quita tildes para que "estomago" encuentre lo mismo que "estómago",
     * tanto en lo que escribe el usuario como en las claves del mapa de arriba.
     */
    private function normalizarTexto(string $texto): string
    {
        $texto = mb_strtolower(trim($texto));
        $reemplazos = ['á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u', 'ñ' => 'n'];
        return strtr($texto, $reemplazos);
    }

    public function buscar(Request $request)
    {
        $q = $request->input('q', '');
        $plantas = collect();

        if (strlen($q) >= 2) {
            $qNormalizado = $this->normalizarTexto($q);
            $terminos = [$q];

            foreach ($this->sinonimosSintomas() as $clave => $sinonimos) {
                if (str_contains($qNormalizado, $clave)) {
                    $terminos = array_merge($terminos, $sinonimos);
                }
            }

            $terminos = array_unique($terminos);

            $plantas = Planta::with(['categoria', 'subtema'])
                ->where(function ($query) use ($terminos) {
                    foreach ($terminos as $termino) {
                        $query->orWhereRaw('LOWER(nombre) LIKE LOWER(?)', ["%{$termino}%"])
                              ->orWhereRaw('LOWER(cientifico) LIKE LOWER(?)', ["%{$termino}%"])
                              ->orWhereRaw('LOWER(uso) LIKE LOWER(?)', ["%{$termino}%"])
                              ->orWhereRaw('LOWER(tags) LIKE LOWER(?)', ["%{$termino}%"])
                              ->orWhereRaw('LOWER(descripcion) LIKE LOWER(?)', ["%{$termino}%"])
                              ->orWhereRaw('LOWER(instrucciones) LIKE LOWER(?)', ["%{$termino}%"])
                              ->orWhereRaw('LOWER(contexto) LIKE LOWER(?)', ["%{$termino}%"])
                              ->orWhereHas('subtema', function ($subQuery) use ($termino) {
                                  $subQuery->whereRaw('LOWER(nombre) LIKE LOWER(?)', ["%{$termino}%"]);
                              });
                    }
                })
                ->orderBy('nombre')
                ->get();
        }

        if ($request->header('HX-Request')) {
            return view('public.partials.search-results', compact('plantas', 'q'));
        }

        return view('public.buscar', compact('plantas', 'q'));
    }
}
