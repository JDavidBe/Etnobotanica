<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Subtema;
use Illuminate\Http\Request;

class SubtemaAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Subtema::with('categoria')->withCount('plantas');

        if ($request->filled('cat')) {
            $query->where('categoria_id', $request->cat);
        }

        $subtemas   = $query->orderBy('nombre')->get();
        $categorias = Categoria::orderBy('nombre')->get();

        return view('admin.subtemas.index', compact('subtemas', 'categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'       => 'required|string|max:100',
            'categoria_id' => 'required|exists:categorias,id',
        ], [
            'nombre.required'       => 'El nombre es obligatorio.',
            'categoria_id.required' => 'Debes seleccionar una categoría.',
        ]);

        Subtema::create([
            'nombre'       => $request->nombre,
            'categoria_id' => $request->categoria_id,
        ]);

        return back()->with('success', "Subtema \"{$request->nombre}\" creado.");
    }

    public function update(Request $request, Subtema $subtema)
    {
        $request->validate([
            'nombre'       => 'required|string|max:100',
            'categoria_id' => 'required|exists:categorias,id',
        ]);

        $subtema->update([
            'nombre'       => $request->nombre,
            'categoria_id' => $request->categoria_id,
        ]);

        return back()->with('success', "Subtema actualizado.");
    }

    public function destroy(Subtema $subtema)
    {
        $nombre = $subtema->nombre;
        $subtema->delete();
        return back()->with('success', "Subtema \"{$nombre}\" eliminado.");
    }
}
