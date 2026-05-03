<?php
namespace App\Http\Controllers;

use App\Models\Aporte;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LectorController extends Controller
{
    public function dashboard()
    {
        $aportes = auth()->user()->aportes()->orderByDesc('creado_en')->get();
        return view('lector.dashboard', compact('aportes'));
    }

    public function edit(Aporte $aporte)
    {
        $this->authorize_aporte($aporte);
        abort_if($aporte->estado === 'pendiente', 403, 'El aporte ya está en revisión.');

        $categorias = Categoria::all();
        return view('lector.aporte-edit', compact('aporte', 'categorias'));
    }

    public function update(Request $request, Aporte $aporte)
    {
        $this->authorize_aporte($aporte);
        abort_if($aporte->estado === 'pendiente', 403, 'El aporte ya está en revisión.');

        $request->validate([
            'nombre_planta' => 'required|string|max:150',
            'cientifico'    => 'nullable|string|max:150',
            'categoria'     => 'required|string|max:100',
            'uso'           => 'required|string|max:255',
            'preparacion'   => 'required|string',
            'relato'        => 'nullable|string',
            'imagen'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $imgPath = $aporte->img_path;
        if ($request->hasFile('imagen')) {
            if ($imgPath) Storage::disk('public')->delete($imgPath);
            $imgPath = $request->file('imagen')->store('aportes', 'public');
        }

        $aporte->update([
            'nombre_planta'  => $request->nombre_planta,
            'cientifico'     => $request->cientifico ?? '',
            'categoria'      => $request->categoria,
            'uso'            => $request->uso,
            'preparacion'    => $request->preparacion,
            'relato'         => $request->relato,
            'img_path'       => $imgPath,
            'estado'         => 'pendiente',   // vuelve a revisión
            'motivo_rechazo' => null,
        ]);

        return redirect()->route('lector.dashboard')
            ->with('success', '¡Aporte actualizado! Está en revisión nuevamente.');
    }

    private function authorize_aporte(Aporte $aporte): void
    {
        abort_if($aporte->user_id !== auth()->id(), 403);
    }
}
