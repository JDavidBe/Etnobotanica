<?php

use Carbon\Carbon;
use Illuminate\Support\Str;

if (! function_exists('is_active_route')) {
    /**
     * Comprueba si la ruta actual coincide con una o varias rutas nombradas.
     */
    function is_active_route(string|array $routes): bool
    {
        $currentRoute = request()->route()?->getName();

        if ($currentRoute === null) {
            return false;
        }

        foreach ((array) $routes as $route) {
            if (Str::is($route, $currentRoute)) {
                return true;
            }
        }

        return false;
    }
}

if (! function_exists('format_fecha_humana')) {
    /**
     * Formatea una fecha en formato legible para la interfaz.
     */
    function format_fecha_humana($fecha): string
    {
        if ($fecha === null || $fecha === '') {
            return '—';
        }

        return Carbon::parse($fecha)
            ->locale(app()->getLocale())
            ->translatedFormat('d \d\e F \d\e Y');
    }
}
