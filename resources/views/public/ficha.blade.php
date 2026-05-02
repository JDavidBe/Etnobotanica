@extends('layouts.app')

@section('title', $planta->nombre . ' — Etnobotánica')


@section('breadcrumbs')
  <i class="fas fa-chevron-right sep"></i>
  <a href="{{ route('categorias.show', $planta->categoria->id) }}" class="crumb">
    {{ $planta->categoria->nombre }}
  </a>
  <i class="fas fa-chevron-right sep"></i>
  <a href="{{ route('subtemas.show', [$planta->categoria->id, $planta->subtema->id]) }}" class="crumb">
    {{ $planta->subtema->nombre }}
  </a>
  <i class="fas fa-chevron-right sep"></i>
  <span style="color:rgba(255,255,255,.45)">{{ $planta->nombre }}</span>
@endsection

@section('content')

<div class="ficha-header">
  <div class="container">
    <a href="{{ route('subtemas.show', [$planta->categoria->id, $planta->subtema->id]) }}"
       class="back-link" style="color:rgba(255,255,255,.65);margin-bottom:14px">
      <i class="fas fa-arrow-left"></i> Volver al listado
    </a>
    <h2>{{ $planta->nombre }}</h2>
    <div class="ficha-meta">
      <span><i class="fas fa-flask"></i> {{ $planta->cientifico }}</span>
      <span><i class="fas fa-folder"></i> {{ $planta->categoria->nombre }} › {{ $planta->subtema->nombre }}</span>
      <span>
        <i class="fas fa-check-circle"></i>
        {{ $planta->verificada ? 'Verificado por expertos' : 'Pendiente de verificación' }}
      </span>
    </div>
  </div>
</div>

<div class="ficha-layout container">
  {{-- Columna imagen --}}
  <div>
    <div class="ficha-img-box">
      @if($planta->imagenUrl)
        <img src="{{ $planta->imagenUrl }}" alt="{{ $planta->nombre }}">
      @else
        <div class="ficha-no-img">
          <i class="fas fa-leaf"></i>
          <p>Sin imagen disponible</p>
        </div>
      @endif
    </div>

    @if($planta->video_url)
      @if(str_starts_with($planta->video_url, 'http'))
        <div class="video-wrap">
          <iframe src="{{ $planta->video_url }}" allowfullscreen></iframe>
        </div>
      @else
        <div style="margin-top:18px;border-radius:var(--radio);overflow:hidden;box-shadow:var(--sombra-lg)">
          <video controls style="width:100%;display:block" preload="metadata">
            <source src="{{ asset($planta->video_url) }}">
          </video>
        </div>
      @endif
    @else
      <div class="no-video">
        <i class="fas fa-video"></i>
        <p>Video no disponible aún</p>
      </div>
    @endif
  </div>

  {{-- Columna info --}}
  <div>
    <div class="info-cient">
      <i class="fas fa-flask" style="color:var(--dorado);font-size:1.2rem;flex-shrink:0"></i>
      <div>
        <div class="lbl">Nombre científico</div>
        <div class="val">{{ $planta->cientifico }}</div>
      </div>
    </div>

    <div class="info-card">
      <h3><i class="fas fa-tags"></i> Categorización y usos</h3>
      <div class="usos-pills">
        <span class="uso-pill"><i class="fas fa-folder" style="font-size:.7rem"></i> {{ $planta->categoria->nombre }}</span>
        <span class="uso-pill"><i class="fas fa-tag" style="font-size:.7rem"></i> {{ $planta->subtema->nombre }}</span>
        <span class="uso-pill main">{{ $planta->uso }}</span>
        @if($planta->tags)
          @foreach(explode(',', $planta->tags) as $tag)
            <span class="uso-pill">{{ trim($tag) }}</span>
          @endforeach
        @endif
      </div>
    </div>

    <div class="info-card">
      <h3><i class="fas fa-mortar-pestle"></i> Instrucciones de preparación</h3>
      <p>{{ $planta->instrucciones }}</p>
    </div>

    @if($planta->contexto)
      <div class="info-card">
        <h3><i class="fas fa-atom"></i> Información general</h3>
        <p>{{ $planta->contexto }}</p>
      </div>
    @endif

    @if($planta->relato)
      <div class="relato-card">
        <p>{{ $planta->relato }}</p>
        <div class="r-autor"><i class="fas fa-quote-right"></i> Relato de sabedor local</div>
      </div>
    @endif

    <a href="{{ route('subtemas.show', [$planta->categoria->id, $planta->subtema->id]) }}"
       class="btn-back">
      <i class="fas fa-arrow-left"></i> Volver
    </a>

    {{-- Comentarios --}}
    @include('components.comentarios', [
        'tipo'       => 'planta',
        'tipo_id'    => $planta->id,
        'comentarios'=> $comentarios,
    ])
  </div>
</div>

@endsection
