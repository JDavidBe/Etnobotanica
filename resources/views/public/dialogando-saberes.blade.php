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
  $dialogandoSaberes = [
    ['url' => asset('img/dialogando-saberes/san-jose-piamonte.jpg'), 'caption' => 'Identificación de plantas con la comunidad en San José de Piamonte'],
    ['url' => asset('img/dialogando-saberes/plaza-mercado.jpg'), 'caption' => 'Plantas medicinales en la Plaza de Mercado de Fusagasugá'],
    ['url' => asset('img/dialogando-saberes/mercado-artesanal.jpg'), 'caption' => 'Mercado artesanal — productos naturales de la comunidad'],
    ['url' => asset('img/dialogando-saberes/chinauta-1.jpg'), 'caption' => 'Vivero comunitario en Chinauta'],
    ['url' => asset('img/dialogando-saberes/chinauta-2.jpg'), 'caption' => 'Cultivo de nopal en Chinauta'],
    ['url' => asset('img/dialogando-saberes/encuentro-comunitario-1.jpg'), 'caption' => 'Encuentro comunitario — productos naturales'],
    ['url' => asset('img/dialogando-saberes/dialogo-de-saberes-1.jpg'), 'caption' => 'Diálogo de saberes con la comunidad'],
    ['url' => asset('img/dialogando-saberes/encuentro-equipo.jpg'), 'caption' => 'Equipo de Etnobotánica con la comunidad'],
    ['url' => asset('img/dialogando-saberes/trabajo-de-campo.jpg'), 'caption' => 'Trabajo de campo en el mercado'],
    ['url' => asset('img/dialogando-saberes/encuentro-comunitario-2.jpg'), 'caption' => 'Encuentro comunitario'],
  ];
@endphp

<div class="container" style="padding-top:2rem;padding-bottom:3rem">
  <div class="ds-slideshow" id="ds-slideshow">
    @foreach($dialogandoSaberes as $foto)
      <div class="ds-slide @if($loop->first) is-active @endif">
        <img src="{{ $foto['url'] }}" alt="{{ $foto['caption'] }}">
        <div class="ds-caption">{{ $foto['caption'] }}</div>
      </div>
    @endforeach

    <button class="ds-arrow ds-prev" aria-label="Foto anterior"><i class="fas fa-chevron-left"></i></button>
    <button class="ds-arrow ds-next" aria-label="Foto siguiente"><i class="fas fa-chevron-right"></i></button>

    <div class="ds-dots">
      @foreach($dialogandoSaberes as $foto)
        <button class="ds-dot @if($loop->first) is-active @endif" data-index="{{ $loop->index }}" aria-label="Ver foto {{ $loop->iteration }}"></button>
      @endforeach
    </div>
  </div>
</div>

<script>
(function () {
  const slideshow = document.getElementById('ds-slideshow');
  if (!slideshow) return;

  const slides = Array.from(slideshow.querySelectorAll('.ds-slide'));
  const dots = Array.from(slideshow.querySelectorAll('.ds-dot'));
  const prevButton = slideshow.querySelector('.ds-prev');
  const nextButton = slideshow.querySelector('.ds-next');

  if (!slides.length) return;

  let currentIndex = 0;
  let timer = null;
  const intervalMs = 4500;

  const showSlide = function (index) {
    currentIndex = (index + slides.length) % slides.length;

    slides.forEach((slide, i) => {
      slide.classList.toggle('is-active', i === currentIndex);
    });

    dots.forEach((dot, i) => {
      dot.classList.toggle('is-active', i === currentIndex);
    });

    restartTimer();
  };

  const tick = function () {
    showSlide(currentIndex + 1);
  };

  const startTimer = function () {
    if (slides.length > 1) {
      timer = setInterval(tick, intervalMs);
    }
  };

  const stopTimer = function () {
    if (timer) {
      clearInterval(timer);
      timer = null;
    }
  };

  const restartTimer = function () {
    stopTimer();
    startTimer();
  };

  if (prevButton) {
    prevButton.addEventListener('click', () => showSlide(currentIndex - 1));
  }

  if (nextButton) {
    nextButton.addEventListener('click', () => showSlide(currentIndex + 1));
  }

  dots.forEach((dot) => {
    dot.addEventListener('click', () => {
      const index = Number(dot.dataset.index);
      if (!Number.isNaN(index)) showSlide(index);
    });
  });

  slideshow.addEventListener('mouseenter', stopTimer);
  slideshow.addEventListener('mouseleave', startTimer);

  if (slides.length > 1) startTimer();
})();
</script>

<style>
.ds-slideshow {
  position: relative;
  width: 100%;
  height: 62vh;
  min-height: 320px;
  max-height: 620px;
  border-radius: 18px;
  overflow: hidden;
  box-shadow: var(--sombra-lg);
  background: var(--bg-card);
}
.ds-slide {
  position: absolute;
  inset: 0;
  opacity: 0;
  transition: opacity .8s ease;
  pointer-events: none;
}
.ds-slide.is-active {
  opacity: 1;
  pointer-events: auto;
}
.ds-slide img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
}
.ds-caption {
  position: absolute;
  left: 0;
  right: 0;
  bottom: 0;
  padding: 48px 28px 20px;
  background: linear-gradient(to top, rgba(0,0,0,.72), rgba(0,0,0,0));
  color: #fff;
  font-size: 1.1rem;
  font-weight: 600;
}
.ds-arrow {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  width: 46px;
  height: 46px;
  border-radius: 50%;
  border: none;
  background: rgba(0,0,0,.4);
  color: #fff;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: background .2s ease;
  z-index: 2;
}
.ds-arrow:hover {
  background: rgba(0,0,0,.65);
}
.ds-prev { left: 16px; }
.ds-next { right: 16px; }
.ds-dots {
  position: absolute;
  bottom: 14px;
  left: 50%;
  transform: translateX(-50%);
  display: flex;
  gap: 8px;
  z-index: 2;
}
.ds-dot {
  width: 9px;
  height: 9px;
  border-radius: 50%;
  border: none;
  background: rgba(255,255,255,.5);
  cursor: pointer;
  padding: 0;
  transition: background .2s ease, transform .2s ease;
}
.ds-dot.is-active {
  background: #fff;
  transform: scale(1.3);
}
@media (max-width: 700px) {
  .ds-slideshow {
    height: 44vh;
    min-height: 240px;
  }
  .ds-caption {
    font-size: .95rem;
    padding: 36px 16px 14px;
  }
  .ds-arrow {
    width: 36px;
    height: 36px;
  }
}
</style>

@endsection
