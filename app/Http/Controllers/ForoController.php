<?php

namespace App\Http\Controllers;

use App\Models\ForoTema;
use Illuminate\Http\Request;

class ForoController extends Controller
{
    public function index(Request $request)
    {
        $categoriaActiva = $request->query('categoria');

        $temas = ForoTema::with('user')
            ->withCount('respuestas')
            ->when($categoriaActiva, fn($q) => $q->where('categoria', $categoriaActiva))
            ->orderByDesc('fijado')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('foro.index', [
            'temas'            => $temas,
            'categorias'       => ForoTema::CATEGORIAS,
            'categoriaActiva'  => $categoriaActiva,
        ]);
    }

    public function create()
    {
        return view('foro.crear', ['categorias' => ForoTema::CATEGORIAS]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo'    => 'required|string|max:200',
            'contenido' => 'required|string|max:5000',
            'categoria' => 'required|in:' . implode(',', array_keys(ForoTema::CATEGORIAS)),
        ], [
            'titulo.required'    => 'El título es obligatorio.',
            'contenido.required' => 'Escribe algo para iniciar la discusión.',
        ]);

        $tema = ForoTema::create([
            'titulo'    => $request->titulo,
            'contenido' => $request->contenido,
            'categoria' => $request->categoria,
            'user_id'   => auth()->id(),
        ]);

        return redirect()->route('foro.show', $tema)->with('success', '¡Tema publicado!');
    }

    public function show(ForoTema $tema)
    {
        $tema->increment('vistas');
        $tema->load(['user', 'respuestas.user']);

        return view('foro.show', compact('tema'));
    }

    public function storeRespuesta(Request $request, ForoTema $tema)
    {
        if ($tema->cerrado) {
            return back()->with('error', 'Este tema está cerrado y ya no acepta respuestas.');
        }

        $request->validate([
            'contenido' => 'required|string|max:2000',
        ], [
            'contenido.required' => 'La respuesta no puede estar vacía.',
        ]);

        $tema->respuestas()->create([
            'user_id'   => auth()->id(),
            'contenido' => $request->contenido,
        ]);

        return back()->with('success', '¡Respuesta publicada!');
    }

    public function cerrar(ForoTema $tema)
    {
        $tema->update(['cerrado' => !$tema->cerrado]);

        return back()->with('success', $tema->cerrado ? 'Tema cerrado.' : 'Tema reabierto.');
    }

    public function fijar(ForoTema $tema)
    {
        $tema->update(['fijado' => !$tema->fijado]);

        return back()->with('success', $tema->fijado ? 'Tema fijado arriba.' : 'Tema desfijado.');
    }

    public function destroy(ForoTema $tema)
    {
        $tema->delete();

        return redirect()->route('foro.index')->with('success', 'Tema eliminado.');
    }
}