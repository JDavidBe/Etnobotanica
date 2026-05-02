@extends('layouts.app')

@section('title', $aporte->nombre_planta . ' — Aporte comunitario')

@section('breadcrumbs')
  <i class="fas fa-chevron-right sep"></i>
  <a href="{{ route('aportar') }}" class="crumb">Aportes</a>
  <i class="fas fa-chevron-right sep"></i>
  <span style="color:rgba(255,255,255,.45)">{{ $aporte->nombre_planta }}</span>
@endsection

@section('content')

<div class="ficha-header">
  <div class="container">
    <a href="{{ route('aportar') }}" class="back-link" style="color:rgba(255,255,255,.65);margin-bottom:14px">
      <i class="fas fa-arrow-left"></i> Volver a aportes
    </a>
    <h2>{{ $aporte->nombre_planta }}</h2>
    <div class="ficha-meta">
      @if($aporte->cientifico)
        <span><i class="fas fa-flask"></i> {{ $aporte->cientifico }}</span>
      @endif
      <span><i class="fas fa-folder"></i> {{ $aporte->categoria }}</span>
      <span><i class="fas fa-users"></i> Aporte comunitario</span>
      <span><i class="fas fa-check-circle"></i> Verificado</span>
    </div>
  </div>
</div>

<div class="ficha-layout container">

  {{-- Columna imagen --}}
  <div>
    <div class="ficha-img-box">
      @if($aporte->img_path)
        <img src="{{ asset('storage/' . $aporte->img_path) }}" alt="{{ $aporte->nombre_planta }}">
      @else
        <div class="ficha-no-img">
          <i class="fas fa-seedling"></i>
          <p>Sin imagen disponible</p>
        </div>
      @endif
    </div>

    <div class="info-card" style="margin-top:18px">
      <h3><i class="fas fa-circle-info"></i> Datos del aporte</h3>
      <div style="display:flex;flex-direction:column;gap:10px;font-size:.87rem;color:var(--texto-suave)">
        <div><i class="fas fa-user" style="width:16px;color:var(--verde-mid)"></i>
          Enviado por <strong style="color:var(--texto)">{{ $aporte->enviado_por }}</strong>
        </div>
        <div><i class="fas fa-calendar-alt" style="width:16px;color:var(--verde-mid)"></i>
          {{ $aporte->creado_en->format('d \d\e F \d\e Y') }}
        </div>
        <div>
          <span style="background:var(--pale);color:var(--verde);padding:3px 10px;border-radius:20px;font-size:.78rem;font-weight:700">
            {{ $aporte->categoria }}
          </span>
        </div>
      </div>
    </div>
  </div>

  {{-- Columna info --}}
  <div>
    <div class="info-card">
      <h3><i class="fas fa-tags"></i> Uso principal</h3>
      <p>{{ $aporte->uso }}</p>
    </div>

    <div class="info-card">
      <h3><i class="fas fa-mortar-pestle"></i> Preparación y uso</h3>
      <p style="white-space:pre-line;line-height:1.7">{{ $aporte->preparacion }}</p>
    </div>

    @if($aporte->relato)
      <div class="relato-card">
        <p>{{ $aporte->relato }}</p>
        <div class="r-autor"><i class="fas fa-quote-right"></i> Historia personal del aportante</div>
      </div>
    @endif

    <a href="{{ route('aportar') }}" class="btn-back">
      <i class="fas fa-arrow-left"></i> Volver a aportes
    </a>

    {{-- Comentarios --}}
    @include('components.comentarios', [
        'tipo'        => 'aporte',
        'tipo_id'     => $aporte->id,
        'comentarios' => $comentarios,
    ])
  </div>
</div>

@endsection
