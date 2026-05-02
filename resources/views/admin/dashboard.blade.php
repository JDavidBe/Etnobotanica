@extends('layouts.admin')

@section('title', 'Dashboard')
@section('admin-title', 'Dashboard')

@section('content')

{{-- Flash --}}
@if(session('success'))
  <div id="flash-success" data-msg="{{ session('success') }}" style="display:none"></div>
@endif

{{-- Stats --}}
<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-ico sv"><i class="fas fa-leaf"></i></div>
    <div class="stat-info">
      <h3>{{ $stats['total_plantas'] }}</h3>
      <p>Plantas registradas</p>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-ico sd"><i class="fas fa-folder-open"></i></div>
    <div class="stat-info">
      <h3>{{ $stats['total_categorias'] }}</h3>
      <p>Categorías activas</p>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-ico sa"><i class="fas fa-users"></i></div>
    <div class="stat-info">
      <h3>{{ $stats['total_aportes'] }}</h3>
      <p>Aportes recibidos</p>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-ico sr"><i class="fas fa-clock"></i></div>
    <div class="stat-info">
      <h3>{{ $stats['pendientes'] }}</h3>
      <p>Pendientes revisión</p>
    </div>
  </div>
</div>

{{-- Gráfico + Actividad reciente --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:18px">
  <div class="tbl-card" style="padding:20px">
    <h3 style="font-size:.92rem;font-weight:700;margin-bottom:16px">Plantas por categoría</h3>
    <div class="bar-chart">
      @php $max = $porCategoria->max('plantas_count') ?: 1; @endphp
      @foreach($porCategoria as $cat)
        <div class="bar-row">
          <span class="bar-lbl">{{ $cat->nombre }}</span>
          <div class="bar-track">
            <div class="bar-fill" style="width:{{ round($cat->plantas_count / $max * 100) }}%"></div>
          </div>
          <span class="bar-val">{{ $cat->plantas_count }}</span>
        </div>
      @endforeach
    </div>
  </div>

  <div class="tbl-card" style="padding:20px">
    <h3 style="font-size:.92rem;font-weight:700;margin-bottom:14px">Actividad reciente</h3>
    <div style="font-size:.83rem;color:var(--texto-mid);line-height:2.2">
      @forelse($actividadReciente as $log)
        <div>
          @php
            $icon = match($log->accion) {
              'CREÓ'    => '✅',
              'EDITÓ'   => '✏️',
              'ELIMINÓ' => '🗑️',
              'PUBLICÓ' => '✅',
              'RECHAZÓ' => '❌',
              default   => '🕐',
            };
          @endphp
          {{ $icon }} <strong>{{ $log->elemento }}</strong>
          · hace {{ $log->fecha->translatedFormat('d M Y h:i A') }}
        </div>
      @empty
        <p style="color:var(--texto-suave)">Sin actividad reciente.</p>
      @endforelse
    </div>
  </div>
</div>

@endsection
