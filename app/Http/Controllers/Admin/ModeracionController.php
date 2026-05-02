<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aporte;
use App\Models\AuditoriaLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ModeracionController extends Controller
{
    public function index(Request $request)
    {
        $pendientes = Aporte::where('estado', 'pendiente')->count();
        $estadoFiltro = $request->query('estado');
        $estadosValidos = ['pendiente', 'aprobado', 'rechazado'];

        $aportes = Aporte::when(in_array($estadoFiltro, $estadosValidos), function ($query) use ($estadoFiltro) {
                $query->where('estado', $estadoFiltro);
            })
            ->orderByDesc('creado_en')
            ->paginate(20);

        return view('admin.moderacion.index', compact('pendientes', 'aportes', 'estadoFiltro'));
    }

    public function aprobar(Aporte $aporte)
    {
        $aporte->update(['estado' => 'aprobado']);

        AuditoriaLog::create([
            'accion'     => 'PUBLICÓ',
            'elemento'   => "Aporte: {$aporte->nombre_planta}",
            'usuario_id' => auth()->id(),
        ]);

        return back()->with('success', "Aporte \"{$aporte->nombre_planta}\" aprobado.");
    }

    public function rechazar(Aporte $aporte)
    {
        $aporte->update(['estado' => 'rechazado']);

        AuditoriaLog::create([
            'accion'     => 'RECHAZÓ',
            'elemento'   => "Aporte: {$aporte->nombre_planta}",
            'usuario_id' => auth()->id(),
        ]);

        return back()->with('success', "Aporte \"{$aporte->nombre_planta}\" rechazado.");
    }

    public function destroy(Aporte $aporte)
    {
        if ($aporte->img_path && Storage::exists('public/' . $aporte->img_path)) {
            Storage::delete('public/' . $aporte->img_path);
        }

        $aporte->delete();

        AuditoriaLog::create([
            'accion'     => 'ELIMINÓ',
            'elemento'   => "Aporte: {$aporte->nombre_planta}",
            'usuario_id' => auth()->id(),
        ]);

        return back()->with('success', "Aporte \"{$aporte->nombre_planta}\" eliminado.");
    }
}
