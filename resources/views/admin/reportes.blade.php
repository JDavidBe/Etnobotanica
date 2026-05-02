@extends('layouts.admin')

@section('title', 'Reportes')
@section('admin-title', 'Reportes')

@section('content')

<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-ico sa"><i class="fas fa-eye"></i></div>
    <div class="stat-info">
      <h3>{{ number_format($stats['total_plantas']) }}</h3>
      <p>Plantas registradas</p>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-ico sv"><i class="fas fa-star"></i></div>
    <div class="stat-info">
      <h3>{{ $stats['verificadas'] }}</h3>
      <p>Plantas verificadas</p>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-ico sd"><i class="fas fa-paper-plane"></i></div>
    <div class="stat-info">
      <h3>{{ $stats['total_aportes'] }}</h3>
      <p>Total aportes</p>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-ico sr"><i class="fas fa-check-double"></i></div>
    <div class="stat-info">
      <h3>{{ $stats['aprobados'] }}</h3>
      <p>Aportes aprobados</p>
    </div>
  </div>
</div>

@endsection
