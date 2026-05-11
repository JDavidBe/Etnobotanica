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
      @php $imgs = $aporte->imagenes; @endphp
      @if($imgs->count() > 0)
        {{-- Imagen principal grande --}}
        <img src="{{ asset('storage/' . $imgs->first()->img_path) }}" alt="{{ $aporte->nombre_planta }}"
             id="galeria-main" style="width:100%;border-radius:var(--radio,12px);object-fit:cover;max-height:320px;cursor:pointer"
             onclick="abrirLightbox(0)">
        {{-- Thumbnails adicionales --}}
        @if($imgs->count() > 1)
          <div style="display:flex;gap:8px;margin-top:10px;flex-wrap:wrap">
            @foreach($imgs as $i => $img)
              <img src="{{ asset('storage/' . $img->img_path) }}"
                   alt="{{ $aporte->nombre_planta }} {{ $i+1 }}"
                   style="width:64px;height:64px;object-fit:cover;border-radius:8px;cursor:pointer;border:2px solid {{ $i===0 ? 'var(--verde-mid)' : 'transparent' }};transition:border .15s"
                   onclick="cambiarMain('{{ asset('storage/' . $img->img_path) }}', this); abrirLightbox({{ $i }})">
            @endforeach
          </div>
        @endif
      @elseif($aporte->img_path)
        <img src="{{ asset('storage/' . $aporte->img_path) }}" alt="{{ $aporte->nombre_planta }}"
             style="width:100%;border-radius:var(--radio,12px);object-fit:cover;max-height:320px">
      @else
        <div class="ficha-no-img">
          <i class="fas fa-seedling"></i>
          <p>Sin imagen disponible</p>
        </div>
      @endif
    </div>

    {{-- Lightbox --}}
    @if($imgs->count() > 0)
    <div id="lightbox" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.88);z-index:9999;align-items:center;justify-content:center"
         onclick="if(event.target===this)cerrarLightbox()">
      <button onclick="cerrarLightbox()" style="position:absolute;top:16px;right:20px;background:rgba(255,255,255,.15);border:none;color:#fff;width:38px;height:38px;border-radius:50%;font-size:1.1rem;cursor:pointer">×</button>
      <button onclick="lb(-1)" style="position:absolute;left:16px;background:rgba(255,255,255,.15);border:none;color:#fff;width:38px;height:38px;border-radius:50%;font-size:1.3rem;cursor:pointer">‹</button>
      <img id="lb-img" src="" style="max-width:88vw;max-height:88vh;border-radius:10px;object-fit:contain;box-shadow:0 8px 40px rgba(0,0,0,.5)">
      <button onclick="lb(1)" style="position:absolute;right:16px;background:rgba(255,255,255,.15);border:none;color:#fff;width:38px;height:38px;border-radius:50%;font-size:1.3rem;cursor:pointer">›</button>
    </div>
    @endif

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

@push('scripts')
<script>
@php $allImgs = $aporte->imagenes->count() > 0 ? $aporte->imagenes->pluck('img_path') : collect([$aporte->img_path]); @endphp
const _lbImgs = @json($allImgs->map(fn($p) => asset('storage/' . $p)));
let _lbIdx = 0;

function abrirLightbox(idx) {
  _lbIdx = idx;
  document.getElementById('lb-img').src = _lbImgs[_lbIdx];
  document.getElementById('lightbox').style.display = 'flex';
}
function cerrarLightbox() { document.getElementById('lightbox').style.display = 'none'; }
function lb(dir) {
  _lbIdx = (_lbIdx + dir + _lbImgs.length) % _lbImgs.length;
  document.getElementById('lb-img').src = _lbImgs[_lbIdx];
}
function cambiarMain(src, thumb) {
  document.getElementById('galeria-main').src = src;
  document.querySelectorAll('[onclick^="cambiarMain"]').forEach(t => t.style.borderColor = 'transparent');
  thumb.style.borderColor = 'var(--verde-mid)';
}
</script>
@endpush
