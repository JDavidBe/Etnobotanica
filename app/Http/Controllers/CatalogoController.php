<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Planta;
use App\Models\Subtema;
use Illuminate\Http\Request;

class CatalogoController extends Controller
{
    public function home()
    {
        $categorias = Categoria::all();
        return view('public.home', compact('categorias'));
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
        $planta->load(['categoria', 'subtema']);
        return view('public.ficha', compact('planta'));
    }

    public function buscar(Request $request)
    {
        $q = $request->input('q', '');
        $plantas = collect();

        if (strlen($q) >= 2) {
            $plantas = Planta::with(['categoria', 'subtema'])
                ->where(function ($query) use ($q) {
                    $query->where('nombre',    'ilike', "%{$q}%")
                          ->orWhere('cientifico', 'ilike', "%{$q}%")
                          ->orWhere('uso',        'ilike', "%{$q}%")
                          ->orWhere('tags',       'ilike', "%{$q}%");
                })
                ->orderBy('nombre')
                ->get();
        }

        return view('public.buscar', compact('plantas', 'q'));
    }
}
