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
      {{-- Diferenciación visual experto vs comunidad --}}
      @if($planta->verificada)
        <span style="background:rgba(56,142,60,.25);border:1px solid rgba(56,142,60,.5);border-radius:20px;padding:3px 12px;display:inline-flex;align-items:center;gap:6px">
          <i class="fas fa-user-tie" style="color:#a5d6a7"></i>
          <strong style="color:#c8e6c9">Contenido del experto</strong>
        </span>
      @else
        <span style="background:rgba(245,124,0,.2);border:1px solid rgba(245,124,0,.4);border-radius:20px;padding:3px 12px;display:inline-flex;align-items:center;gap:6px">
          <i class="fas fa-users" style="color:#ffcc80"></i>
          <strong style="color:#ffe0b2">Saber comunitario</strong>
        </span>
      @endif
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

    {{-- Video con overlay noticiero si hay créditos --}}
    @if($planta->video_url)
      <div style="margin-top:18px">
        @if($planta->video_persona_nombre)
          {{-- Aviso de validación --}}
          @if(!$planta->video_validado)
            <div style="background:#fff3e0;border:1px solid #ffcc80;border-radius:8px;padding:8px 12px;margin-bottom:8px;font-size:.78rem;color:#e65100;display:flex;align-items:center;gap:8px">
              <i class="fas fa-hourglass-half"></i> Pendiente de validación con el entrevistado
            </div>
          @endif
        @endif

        @if(str_contains($planta->video_url, 'youtube.com/embed'))
          <div class="video-wrap" style="position:relative">
            <iframe src="{{ $planta->video_url }}" allowfullscreen></iframe>
            {{-- Overlay nombre/rol --}}
            @if($planta->video_persona_nombre)
              <div class="video-overlay-credito">
                <i class="fas fa-microphone" style="font-size:.75rem;opacity:.8"></i>
                <div>
                  <div class="voc-nombre">{{ $planta->video_persona_nombre }}</div>
                  @if($planta->video_persona_rol)
                    <div class="voc-rol">{{ $planta->video_persona_rol }}</div>
                  @endif
                </div>
              </div>
            @endif
          </div>
        @else
          <div style="position:relative;border-radius:var(--radio);overflow:hidden;box-shadow:var(--sombra-lg)">
            <video controls style="width:100%;display:block" preload="metadata">
              <source src="{{ $planta->video_url }}">
            </video>
            @if($planta->video_persona_nombre)
              <div class="video-overlay-credito">
                <i class="fas fa-microphone" style="font-size:.75rem;opacity:.8"></i>
                <div>
                  <div class="voc-nombre">{{ $planta->video_persona_nombre }}</div>
                  @if($planta->video_persona_rol)
                    <div class="voc-rol">{{ $planta->video_persona_rol }}</div>
                  @endif
                </div>
              </div>
            @endif
          </div>
        @endif
      </div>
    @else
      <div class="no-video">
        <i class="fas fa-video"></i>
        <p>Video no disponible aún</p>
      </div>
    @endif
  </div>

  {{-- Columna info --}}
  <div>
    {{-- Badge fuente --}}
    <div style="margin-bottom:16px">
      @if($planta->verificada)
        <div style="display:inline-flex;align-items:center;gap:10px;background:#e8f5e9;border:1px solid #a5d6a7;border-radius:10px;padding:10px 16px">
          <i class="fas fa-user-tie" style="color:#388e3c;font-size:1.2rem"></i>
          <div>
            <div style="font-size:.78rem;font-weight:700;color:#1b5e20;text-transform:uppercase;letter-spacing:.05em">Contenido del experto</div>
            <div style="font-size:.75rem;color:#388e3c">Información validada por especialistas</div>
          </div>
        </div>
      @else
        <div style="display:inline-flex;align-items:center;gap:10px;background:#fff8e1;border:1px solid #ffe082;border-radius:10px;padding:10px 16px">
          <i class="fas fa-users" style="color:#f57c00;font-size:1.2rem"></i>
          <div>
            <div style="font-size:.78rem;font-weight:700;color:#e65100;text-transform:uppercase;letter-spacing:.05em">Saber comunitario</div>
            <div style="font-size:.75rem;color:#f57c00">Conocimiento tradicional — pendiente de verificación experta</div>
          </div>
        </div>
      @endif
    </div>

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
        {{-- Todas las categorías (M2M) --}}
        @forelse($planta->categorias as $cat)
          <span class="uso-pill"><i class="fas fa-folder" style="font-size:.7rem"></i> {{ $cat->nombre }}</span>
        @empty
          <span class="uso-pill"><i class="fas fa-folder" style="font-size:.7rem"></i> {{ $planta->categoria->nombre }}</span>
        @endforelse
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

    @include('components.comentarios', [
        'tipo'        => 'planta',
        'tipo_id'     => $planta->id,
        'comentarios' => $comentarios,
    ])
  </div>
</div>

<style>
.video-overlay-credito {
  position:absolute;
  bottom:0; left:0; right:0;
  background:linear-gradient(transparent, rgba(0,0,0,.75));
  color:#fff;
  padding:24px 16px 12px;
  display:flex;
  align-items:flex-end;
  gap:10px;
  pointer-events:none;
}
.voc-nombre {
  font-size:.95rem;
  font-weight:700;
  line-height:1.2;
  text-shadow:0 1px 3px rgba(0,0,0,.5);
}
.voc-rol {
  font-size:.78rem;
  opacity:.85;
  font-style:italic;
}
</style>

@endsection
