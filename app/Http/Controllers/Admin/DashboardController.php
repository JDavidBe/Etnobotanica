<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aporte;
use App\Models\AuditoriaLog;
use App\Models\Categoria;
use App\Models\Planta;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_plantas'    => Planta::count(),
            'total_categorias' => Categoria::count(),
            'total_aportes'    => Aporte::count(),
            'pendientes'       => Aporte::where('estado', 'pendiente')->count(),
        ];

        $porCategoria = Categoria::withCount('plantas')
            ->orderByDesc('plantas_count')
            ->get();

        $actividadReciente = AuditoriaLog::with('usuario')
            ->orderByDesc('fecha')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'porCategoria', 'actividadReciente'));
    }
}
