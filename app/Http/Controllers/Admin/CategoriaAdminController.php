<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaAdminController extends Controller
{
    public function index()
    {
        $categorias = Categoria::withCount(['subtemas', 'plantas'])->get();

        return view('admin.categorias.index', compact('categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'      => 'required|string|max:100|unique:categorias,nombre',
            'descripcion' => 'nullable|string',
            'icono'       => 'nullable|string|max:100',
            'orden'       => 'nullable|integer|min:0',
        ]);

        Categoria::create([
            'nombre'      => $request->nombre,
            'descripcion' => $request->descripcion,
            'icono'       => $request->icono ?? 'fas fa-leaf',
            'orden'       => $request->orden ?? 0,
        ]);

        return back()->with('success', "Categoría \"{$request->nombre}\" creada.");
    }

    public function update(Request $request, Categoria $categoria)
    {
        $request->validate([
            'nombre'      => 'required|string|max:100|unique:categorias,nombre,' . $categoria->id,
            'descripcion' => 'nullable|string',
            'icono'       => 'nullable|string|max:100',
            'orden'       => 'nullable|integer|min:0',
        ]);

        $categoria->update([
            'nombre'      => $request->nombre,
            'descripcion' => $request->descripcion,
            'icono'       => $request->icono ?? $categoria->icono,
            'orden'       => $request->orden ?? $categoria->orden,
        ]);

        return back()->with('success', "Categoría actualizada.");
    }

    public function destroy(Categoria $categoria)
    {
        $categoria->delete();
        return back()->with('success', "Categoría eliminada.");
    }
}
