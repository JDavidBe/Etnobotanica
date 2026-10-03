@extends('layouts.app')

@section('title', 'Dialogando Saberes — Etnobotánica Fusagasugá')

@section('breadcrumbs')
  <i class="fas fa-chevron-right sep"></i>
  <span style="color:rgba(255,255,255,.45)">Dialogando Saberes</span>
@endsection

@section('content')

<div class="ficha-header">
  <div class="container" style="padding-top:2.5rem;padding-bottom:2.5rem">
    <h2 style="font-size:2rem;margin-bottom:.5rem">
      <i class="fas fa-comments" style="font-size:1.5rem;opacity:.85"></i> Dialogando Saberes
    </h2>
    <p style="opacity:.75;font-size:1rem">Encuentros y diálogos de saberes con las comunidades de Fusagasugá</p>
  </div>
</div>

@php
  // Para agregar, quitar o cambiar una foto, edita este arreglo.
  // 'url': enlace directo a la imagen. 'caption': texto que aparece sobre la foto.
  $dialogandoSaberes = [
    ['url' => asset('img/balu.webp'), 'caption' => 'Encuentro comunitario con sabedores locales'],
    ['url' => asset('img/cafeto.webp'), 'caption' => 'Trabajo de campo y diálogo de saberes'],
    ['url' => asset('img/mora.jpg'), 'caption' => 'Mercado de plantas y usos tradicionales'],
    ['url' => asset('img/Guayabo.jpg'), 'caption' => 'Compartiendo conocimiento sobre plantas medicinales'],
    ['url' => asset('img/moringa.webp'), 'caption' => 'Cultivo y cuidado de especies medicinales'],
    ['url' => asset('img/pata.jpg'), 'caption' => 'Saberes locales en comunidad'],
  ];
@endphp

<div class="container" style="padding-top:2rem;padding-bottom:3rem">
  <div class="ds-slideshow" id="ds-slideshow">
    @foreach($dialogandoSaberes as $i => $foto)
      <div class="ds-slide {{ $i === 0 ? 'is-active' : '' }}">
        <img src="{{ $foto['url'] }}" alt="{{ $foto['caption'] }}" loading="{{ $i === 0 ? 'eager' : 'lazy' }}"
             onerror="this.closest('.ds-slide').classList.add('ds-broken')">
        <div class="ds-caption">{{ $foto['caption'] }}</div>
      </div>
    @endforeach

    <button class="ds-arrow ds-prev" onclick="dsGo(dsIndex - 1)" aria-label="Foto anterior"><i class="fas fa-chevron-left"></i></button>
    <button class="ds-arrow ds-next" onclick="dsGo(dsIndex + 1)" aria-label="Foto siguiente"><i class="fas fa-chevron-right"></i></button>

    <div class="ds-dots">
      @foreach($dialogandoSaberes as $i => $foto)
        <button class="ds-dot {{ $i === 0 ? 'is-active' : '' }}" onclick="dsGo({{ $i }})" aria-label="Ir a la foto {{ $i + 1 }}"></button>
      @endforeach
    </div>
  </div>
</div>

<script>
(function () {
  const slideshow = document.getElementById('ds-slideshow');
  const slides = slideshow.querySelectorAll('.ds-slide');
  const dots = slideshow.querySelectorAll('.ds-dot');
  const total = slides.length;
  const intervalMs = 4500;
  let timer = null;

  window.dsIndex = 0;

  window.dsGo = function (index) {
    window.dsIndex = (index + total) % total;
    slides.forEach((s, i) => s.classList.toggle('is-active', i === window.dsIndex));
    dots.forEach((d, i) => d.classList.toggle('is-active', i === window.dsIndex));
    restart();
  };

  function tick() { dsGo(window.dsIndex + 1); }
  function start() { timer = setInterval(tick, intervalMs); }
  function stop() { clearInterval(timer); }
  function restart() { stop(); start(); }

  slideshow.addEventListener('mouseenter', stop);
  slideshow.addEventListener('mouseleave', start);

  if (total > 1) start();
})();
</script>

<style>
.ds-slideshow {
  position: relative; width: min(100%, 1200px); margin: 0 auto; height: 62vh; min-height: 320px; max-height: 620px;
  border-radius: 22px; overflow: hidden; box-shadow: var(--sombra-lg); background: var(--bg-card);
  border: 1px solid rgba(55, 124, 91, 0.12);
}
.ds-slide {
  position: absolute; inset: 0; opacity: 0; transition: opacity .8s ease;
  pointer-events: none;
}
.ds-slide.is-active { opacity: 1; pointer-events: auto; }
.ds-slide img { width: 100%; height: 100%; object-fit: cover; display: block; }
.ds-slide.ds-broken img { display: none; }
.ds-slide.ds-broken {
  display: flex; align-items: center; justify-content: center;
  background: linear-gradient(135deg, var(--pale) 0%, var(--bg-input) 100%);
}
.ds-slide.ds-broken::before {
  content: "\f03e"; font-family: "Font Awesome 6 Free"; font-weight: 900;
  font-size: 3.5rem; color: var(--texto-suave); opacity: .35;
}
.ds-caption {
  position: absolute; left: 0; right: 0; bottom: 0; padding: 52px 28px 22px;
  background: linear-gradient(to top, rgba(0,0,0,.74), rgba(0,0,0,0));
  color: #fff; font-size: 1.1rem; font-weight: 700; letter-spacing: .01em;
}
.ds-arrow {
  position: absolute; top: 50%; transform: translateY(-50%);
  width: 46px; height: 46px; border-radius: 50%; border: none;
  background: rgba(17, 24, 39, .38); color: #fff; cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  backdrop-filter: blur(4px); transition: background .2s ease, transform .2s ease; z-index: 2;
}
.ds-arrow:hover { background: rgba(17, 24, 39, .62); transform: translateY(-50%) scale(1.04); }
.ds-prev { left: 18px; }
.ds-next { right: 18px; }
.ds-dots {
  position: absolute; bottom: 18px; left: 50%; transform: translateX(-50%);
  display: flex; gap: 8px; z-index: 2;
}
.ds-dot {
  width: 10px; height: 10px; border-radius: 50%; border: none;
  background: rgba(255,255,255,.52); cursor: pointer; padding: 0;
  transition: background .2s ease, transform .2s ease;
}
.ds-dot.is-active { background: #fff; transform: scale(1.35); }
@media (max-width: 700px) {
  .ds-slideshow { height: 44vh; min-height: 240px; border-radius: 16px; }
  .ds-caption { font-size: .95rem; padding: 36px 16px 14px; }
  .ds-arrow { width: 36px; height: 36px; }
}
</style>

@endsection
