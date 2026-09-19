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
          <p>Sin imagen propia disponible</p>
          @if($planta->foto_referencia_url)
            <a href="{{ $planta->foto_referencia_url }}" target="_blank" rel="noopener"
               style="margin-top:10px;font-size:.82rem;display:inline-flex;align-items:center;gap:6px;color:var(--dorado)">
              <i class="fas fa-external-link-alt"></i> Ver fotografías libres (Wikimedia Commons)
            </a>
          @endif
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

        @if(str_starts_with($planta->video_url, 'http'))
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
              <source src="{{ asset($planta->video_url) }}">
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

    {{-- Clasificación taxonómica: Reino → División → Clase → Orden → Familia → Género → Especie --}}
    <div class="info-card taxonomia-card">
      <h3><i class="fas fa-dna"></i> Clasificación taxonómica</h3>

      @if(!$planta->taxonomia_determinada && !$planta->genero)
        <p class="tax-aviso">
          <i class="fas fa-triangle-exclamation"></i>
          Identificación taxonómica no confirmada para el nombre común reportado.
        </p>
      @endif

      <dl class="taxonomia-lista">
        @foreach($planta->taxonomia as $nivel => $valor)
          <div class="tax-fila">
            <dt>{{ $nivel }}</dt>
            <dd @class(['tax-especie' => $nivel === 'Especie'])>
              @if($valor)
                @if(in_array($nivel, ['Género', 'Especie'], true))
                  {{-- Solo Género y Especie van en cursiva (norma del ICN) --}}
                  <em>{{ $valor }}</em>
                @else
                  {{ $valor }}
                @endif
              @else
                <span class="tax-nd">No determinado</span>
              @endif
            </dd>
          </div>
        @endforeach
      </dl>

      @if($planta->taxonomia_nota)
        <p class="tax-nota">
          <i class="fas fa-circle-info"></i> {{ $planta->taxonomia_nota }}
        </p>
      @endif
    </div>

    @if($planta->descripcion)
      <div class="info-card">
        <h3><i class="fas fa-book-open"></i> Descripción</h3>
        <p>{{ $planta->descripcion }}</p>
      </div>
    @endif

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

.taxonomia-card .taxonomia-lista {
  display:grid;
  grid-template-columns:1fr;
  gap:0;
  margin:0;
}
.taxonomia-card .tax-fila {
  display:grid;
  grid-template-columns:140px 1fr;
  align-items:center;
  gap:14px;
  padding:12px 14px;
  border-radius:8px;
}
.taxonomia-card .tax-fila:nth-child(odd) {
  background:var(--bg-input);
}
.taxonomia-card dt {
  font-size:.72rem;
  font-weight:800;
  text-transform:uppercase;
  letter-spacing:.06em;
  color:var(--dorado);
}
.taxonomia-card dd {
  margin:0;
  font-size:1rem;
  font-weight:500;
  color:var(--texto);
  line-height:1.3;
  word-break:break-word;
  font-style:normal; /* Reino, División, Clase, Orden y Familia: redonda */
}
.taxonomia-card dd em {
  font-style:italic;
}
.taxonomia-card dd.tax-especie {
  font-size:1.08rem;
}
.taxonomia-card dd.tax-especie em {
  font-weight:700;
  color:var(--verde-claro);
}
.taxonomia-card .tax-nd {
  color:var(--texto-suave);
  font-style:italic;
  font-weight:400;
}
.taxonomia-card .tax-nota,
.taxonomia-card .tax-aviso {
  display:flex;
  align-items:flex-start;
  gap:8px;
  margin:14px 0 0;
  font-size:.85rem;
  line-height:1.55;
  border-radius:10px;
  padding:10px 14px;
}
.taxonomia-card .tax-nota {
  color:var(--texto-mid);
  background:var(--pale);
  border:1px solid var(--border);
}
.taxonomia-card .tax-aviso {
  color:var(--dorado-lt);
  background:rgba(212,175,55,.12);
  border:1px solid rgba(212,175,55,.4);
  font-weight:600;
}
.taxonomia-card .tax-aviso i,
.taxonomia-card .tax-nota i {
  margin-top:2px;
  flex-shrink:0;
}
@media (max-width: 520px) {
  .taxonomia-card .tax-fila {
    grid-template-columns:1fr;
    gap:2px;
    padding:10px 12px;
  }
}
</style>

@endsection
