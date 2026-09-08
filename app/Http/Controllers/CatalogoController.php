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

    public function buscar(Request $request)
    {
        $q = $request->input('q', '');
        $plantas = collect();

        if (strlen($q) >= 2) {
            $plantas = Planta::with(['categoria', 'subtema'])
                ->where(function ($query) use ($q) {
                    $query->whereRaw('LOWER(nombre) LIKE LOWER(?)', ["%{$q}%"])
                          ->orWhereRaw('LOWER(cientifico) LIKE LOWER(?)', ["%{$q}%"])
                          ->orWhereRaw('LOWER(uso) LIKE LOWER(?)', ["%{$q}%"])
                          ->orWhereRaw('LOWER(tags) LIKE LOWER(?)', ["%{$q}%"])
                          ->orWhereHas('subtema', function ($subQuery) use ($q) {
                              $subQuery->whereRaw('LOWER(nombre) LIKE LOWER(?)', ["%{$q}%"]);
                          });
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
