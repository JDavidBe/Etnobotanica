<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditoriaLog;
use App\Models\Planta;
use App\Models\Aporte;

class AuditoriaController extends Controller
{
    public function index()
    {
        $logs = AuditoriaLog::with('usuario')
            ->orderByDesc('fecha')
            ->paginate(30);

        return view('admin.auditoria', compact('logs'));
    }

    public function reportes()
    {
        $stats = [
            'total_plantas'  => Planta::count(),
            'verificadas'    => Planta::where('verificada', true)->count(),
            'total_aportes'  => Aporte::count(),
            'aprobados'      => Aporte::where('estado', 'aprobado')->count(),
        ];

        return view('admin.reportes', compact('stats'));
    }
}
