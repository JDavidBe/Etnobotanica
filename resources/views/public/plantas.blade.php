@extends('layouts.app')

@section('title', isset($subtema) ? $subtema->nombre . ' — Etnobotánica' : $categoria->nombre . ' — Etnobotánica')


@section('breadcrumbs')
  <i class="fas fa-chevron-right sep"></i>
  <a href="{{ route('categorias.show', $categoria->id) }}" class="crumb">{{ $categoria->nombre }}</a>
  @if(isset($subtema))
    <i class="fas fa-chevron-right sep"></i>
    <span>{{ $subtema->nombre }}</span>
  @endif
@endsection

@section('content')

<div class="container">
  <div class="sec-inner">
    <a href="{{ route('categorias.show', $categoria->id) }}" class="back-link">
      <i class="fas fa-arrow-left"></i> Volver a {{ $categoria->nombre }}
    </a>
    <p class="sec-titulo">{{ isset($subtema) ? $subtema->nombre : $categoria->nombre }}</p>

    <div class="filtros">
      <a href="{{ request()->fullUrlWithQuery(['filtro' => 'todas']) }}"
         class="f-btn {{ $filtro === 'todas' ? 'on' : '' }}">Todas</a>
      <a href="{{ request()->fullUrlWithQuery(['filtro' => 'verificada']) }}"
         class="f-btn {{ $filtro === 'verificada' ? 'on' : '' }}">Verificadas</a>
      <a href="{{ request()->fullUrlWithQuery(['filtro' => 'pendiente']) }}"
         class="f-btn {{ $filtro === 'pendiente' ? 'on' : '' }}">Pendientes</a>
    </div>

    <div class="plant-grid">
      @forelse($plantas as $planta)
        <a href="{{ route('plantas.show', $planta->id) }}" class="plant-card">
          @if($planta->img_url)
            <img src="{{ asset($planta->img_url) }}" class="plant-img" alt="{{ $planta->nombre }}">
          @else
            <div class="plant-no-img"><i class="fas fa-leaf"></i></div>
          @endif
          <div class="plant-info">
            <span class="{{ $planta->verificada ? 'badge-v' : 'badge-p' }}">
              {{ $planta->verificada ? 'Verificado' : 'Pendiente' }}
            </span>
            <div class="plant-nombre">{{ $planta->nombre }}</div>
            <div class="plant-cient">{{ $planta->cientifico }}</div>
            <div class="plant-uso">{{ $planta->uso }}</div>
            @if($planta->tags)
              <div class="plant-tags">
                @foreach(explode(',', $planta->tags) as $tag)
                  <span class="tag">{{ trim($tag) }}</span>
                @endforeach
              </div>
            @endif
          </div>
        </a>
      @empty
        <div class="empty">
          <i class="fas fa-seedling"></i>
          <p>No hay plantas registradas aquí aún.</p>
        </div>
      @endforelse
    </div>
  </div>
</div>

@endsection
