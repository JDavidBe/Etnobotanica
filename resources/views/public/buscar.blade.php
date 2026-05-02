@extends('layouts.app')

@section('title', 'Búsqueda — Etnobotánica')


@section('breadcrumbs')
  <i class="fas fa-chevron-right sep"></i>
  <span>Búsqueda</span>
@endsection

@section('content')

<div class="container">
  <div class="sec-inner">
    <p class="sec-titulo" id="search-tit">
      @if(request('q'))
        Resultados para "{{ request('q') }}" — {{ $plantas->count() }} plantas
      @else
        Busca una planta
      @endif
    </p>

    <div class="plant-grid" id="search-results">
      @forelse($plantas as $planta)
        <a href="{{ route('plantas.show', $planta->id) }}" class="plant-card">
          @if($planta->img_url)
            <img src="{{ $planta->img_url }}" class="plant-img" alt="{{ $planta->nombre }}">
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
          </div>
        </a>
      @empty
        <div class="empty">
          <i class="fas fa-search"></i>
          <p>Sin resultados para "{{ request('q') }}"</p>
        </div>
      @endforelse
    </div>
  </div>
</div>

@endsection
