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
    ['url' => 'https://drive.google.com/thumbnail?id=1HByny92I0H8hfu1C1E_YF7SfDzReAvHk&sz=w1600', 'caption' => 'Encuentro comunitario'],
    ['url' => 'https://drive.google.com/thumbnail?id=1nXQBRA3iR1d4hN02RGA-rXoNzlX4JJrB&sz=w1600', 'caption' => 'Trabajo de campo con la comunidad'],
    ['url' => 'https://drive.google.com/thumbnail?id=1PdKjZBxuBuBn8sKQxIt5senNF2S1mUyb&sz=w1600', 'caption' => 'Diálogo de saberes'],
    ['url' => 'https://drive.google.com/thumbnail?id=1qG2IDvfGErFT_a0i8I2PGUR67zfuj1eE&sz=w1600', 'caption' => 'Diálogo de saberes'],
    ['url' => 'https://drive.google.com/thumbnail?id=1d1RmLAjIKzlKg3-ASwN3Qdcwvi7eVC-B&sz=w1600', 'caption' => 'Encuentro comunitario'],
    // Las siguientes 5 vienen de SharePoint institucional: por ahora puede que no
    // carguen para los visitantes, porque ese enlace exige iniciar sesión con la
    // cuenta @ucundinamarca.edu.co. Mientras tanto se ven con un ícono de
    // reemplazo. Lo ideal es descargarlas y subirlas al proyecto (ver LEEME).
    ['url' => 'https://mailunicundiedu-my.sharepoint.com/:i:/r/personal/anaesperanzamerchan_ucundinamarca_edu_co/Documents/Etnobot%C3%A1nica/evidencia%20fotografica/chinauta/IMG_20260225_111518.jpg?d=w4c414921860342beac6db916310cede8&csf=1&web=1&e=p8ktJa', 'caption' => 'Encuentro en Chinauta'],
    ['url' => 'https://mailunicundiedu-my.sharepoint.com/:i:/r/personal/anaesperanzamerchan_ucundinamarca_edu_co/Documents/Etnobot%C3%A1nica/evidencia%20fotografica/chinauta/IMG_20260225_112849.jpg?d=wb1e338d9ac5241c6859caa6895f87dc3&csf=1&web=1&e=UrFoe4', 'caption' => 'Encuentro en Chinauta'],
    ['url' => 'https://mailunicundiedu-my.sharepoint.com/:i:/r/personal/anaesperanzamerchan_ucundinamarca_edu_co/Documents/Etnobot%C3%A1nica/evidencia%20fotografica/san%20jose%20piamonte/WhatsApp%20Image%202026-03-20%20at%206.19.39%20PM.jpeg?d=w36b07c567cc24f8a95bb7f1d4bcb4fd8&csf=1&web=1&e=9dE8lN', 'caption' => 'Diálogo de saberes en San José de Piamonte'],
    ['url' => 'https://mailunicundiedu-my.sharepoint.com/:i:/r/personal/anaesperanzamerchan_ucundinamarca_edu_co/Documents/Etnobot%C3%A1nica/evidencia%20fotografica/plaza%20mercado/IMG_20260220_092810.jpg?d=wb16c2a6f6b7f49b6bf9034763ec5e20f&csf=1&web=1&e=dpzLGH', 'caption' => 'Plaza de mercado de Fusagasugá'],
    ['url' => 'https://mailunicundiedu-my.sharepoint.com/:i:/r/personal/anaesperanzamerchan_ucundinamarca_edu_co/Documents/Etnobot%C3%A1nica/evidencia%20fotografica/Mercado%20artesanal/cc9bfa1d-76d3-4c65-a718-c9084ab4dafb.jfif?d=web70c69c1be4485a8b674b3d8657b838&csf=1&web=1&e=lJzywA', 'caption' => 'Mercado artesanal'],
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
  position: relative; width: 100%; height: 62vh; min-height: 320px; max-height: 620px;
  border-radius: 18px; overflow: hidden; box-shadow: var(--sombra-lg); background: var(--bg-card);
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
  position: absolute; left: 0; right: 0; bottom: 0; padding: 48px 28px 20px;
  background: linear-gradient(to top, rgba(0,0,0,.72), rgba(0,0,0,0));
  color: #fff; font-size: 1.1rem; font-weight: 600;
}
.ds-arrow {
  position: absolute; top: 50%; transform: translateY(-50%);
  width: 46px; height: 46px; border-radius: 50%; border: none;
  background: rgba(0,0,0,.4); color: #fff; cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  transition: background .2s ease; z-index: 2;
}
.ds-arrow:hover { background: rgba(0,0,0,.65); }
.ds-prev { left: 16px; }
.ds-next { right: 16px; }
.ds-dots {
  position: absolute; bottom: 14px; left: 50%; transform: translateX(-50%);
  display: flex; gap: 8px; z-index: 2;
}
.ds-dot {
  width: 9px; height: 9px; border-radius: 50%; border: none;
  background: rgba(255,255,255,.5); cursor: pointer; padding: 0;
  transition: background .2s ease, transform .2s ease;
}
.ds-dot.is-active { background: #fff; transform: scale(1.3); }
@media (max-width: 700px) {
  .ds-slideshow { height: 44vh; min-height: 240px; }
  .ds-caption { font-size: .95rem; padding: 36px 16px 14px; }
  .ds-arrow { width: 36px; height: 36px; }
}
</style>

@endsection
