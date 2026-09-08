<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aporte;
use App\Models\AuditoriaLog;
use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ModeracionController extends Controller
{
    public function index(Request $request)
    {
        $pendientes   = Aporte::where('estado', 'pendiente')->count();
        $aprobados    = Aporte::where('estado', 'aprobado')->count();
        $rechazados   = Aporte::where('estado', 'rechazado')->count();
        $total        = $pendientes + $aprobados + $rechazados;

        // Aportes por mes (últimos 6 meses) para la gráfica
        $porMes = Aporte::all()
            ->groupBy(fn($a) => $a->creado_en->format('Y-m'))
            ->map(fn($grupo, $mes) => ['mes' => $mes, 'total' => $grupo->count()])
            ->values();

        $estadoFiltro   = $request->query('estado');
        $estadosValidos = ['pendiente', 'aprobado', 'rechazado'];

        $aportes = Aporte::with('imagenes')
            ->when(in_array($estadoFiltro, $estadosValidos), fn($q) => $q->where('estado', $estadoFiltro))
            ->orderByDesc('creado_en')
            ->paginate(20);

        return view('admin.moderacion.index', compact(
            'pendientes', 'aprobados', 'rechazados', 'total',
            'porMes', 'aportes', 'estadoFiltro'
        ));
    }

    public function aprobar(Aporte $aporte)
    {
        $aporte->update(['estado' => 'aprobado', 'motivo_rechazo' => null]);

        AuditoriaLog::create([
            'accion'     => 'PUBLICÓ',
            'elemento'   => "Aporte: {$aporte->nombre_planta}",
            'usuario_id' => auth()->id(),
        ]);

        if ($aporte->user_id) {
            Notificacion::crearParaUser(
                $aporte->user_id,
                'aporte_aprobado',
                '¡Tu aporte fue aprobado!',
                "Tu aporte «{$aporte->nombre_planta}» fue revisado y publicado en el catálogo comunitario.",
                route('aportes.show', $aporte->id)
            );
        }

        return back()->with('success', "Aporte \"{$aporte->nombre_planta}\" aprobado.");
    }

    public function rechazar(Request $request, Aporte $aporte)
    {
        $request->validate([
            'motivo_rechazo' => 'required|string|max:500',
        ], [
            'motivo_rechazo.required' => 'Debes indicar el motivo del rechazo.',
        ]);

        $aporte->update([
            'estado'         => 'rechazado',
            'motivo_rechazo' => $request->motivo_rechazo,
        ]);

        AuditoriaLog::create([
            'accion'     => 'RECHAZÓ',
            'elemento'   => "Aporte: {$aporte->nombre_planta}",
            'usuario_id' => auth()->id(),
        ]);

        if ($aporte->user_id) {
            Notificacion::crearParaUser(
                $aporte->user_id,
                'aporte_rechazado',
                'Tu aporte fue rechazado',
                "Tu aporte «{$aporte->nombre_planta}» no pudo ser publicado. Motivo: {$request->motivo_rechazo}",
                route('lector.dashboard')
            );
        }

        return back()->with('success', "Aporte \"{$aporte->nombre_planta}\" rechazado.");
    }

    public function destroy(Aporte $aporte)
    {
        if ($aporte->img_path && Storage::exists('public/' . $aporte->img_path)) {
            Storage::delete('public/' . $aporte->img_path);
        }
        foreach ($aporte->imagenes as $img) {
            Storage::disk('public')->delete($img->img_path);
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
