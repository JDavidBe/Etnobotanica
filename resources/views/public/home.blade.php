@extends('layouts.app')

@section('title', 'Etnobotánica Fusagasugá')


@section('content')

<div class="hero">
  <h1>Saberes botánicos de<br><em>Fusagasugá</em></h1>
  <p>Catálogo colaborativo del conocimiento tradicional sobre plantas medicinales y su uso local.</p>
</div>

<div class="container">
  <div class="sec-inner">
    <p class="sec-titulo">Explorar Categorías</p>
    <p class="sec-sub">Selecciona una categoría para explorar los saberes botánicos</p>

    <div class="catalogo-actions">
      <a href="{{ route('catalogo') }}" class="btn-catalogo">
        <i class="fas fa-book-open"></i>
        Ver Catálogo Completo
      </a>
    </div>

    <div class="grid-cats">
      @forelse($categorias as $cat)
        <a href="{{ route('categorias.show', $cat->id) }}" class="card-cat">
          <div class="cat-ico">
            <i class="{{ $cat->icono }}"></i>
          </div>
          <h3>{{ $cat->nombre }}</h3>
          <p>{{ $cat->descripcion }}</p>
        </a>
      @empty
        <div class="empty">
          <i class="fas fa-seedling"></i>
          <p>No hay categorías registradas aún.</p>
        </div>
      @endforelse
    </div>
  </div>
</div>

{{-- ══ SECCIÓN: Dialogando Saberes (carrusel de fotos de la comunidad) ══ --}}
@php
  // Para agregar, quitar o cambiar una foto, edita este arreglo.
  // 'url': enlace directo a la imagen. 'caption': texto que aparece debajo.
  $dialogandoSaberes = [
    ['url' => 'https://drive.google.com/thumbnail?id=1HByny92I0H8hfu1C1E_YF7SfDzReAvHk&sz=w1000', 'caption' => 'Encuentro comunitario'],
    ['url' => 'https://drive.google.com/thumbnail?id=1nXQBRA3iR1d4hN02RGA-rXoNzlX4JJrB&sz=w1000', 'caption' => 'Trabajo de campo con la comunidad'],
    ['url' => 'https://drive.google.com/thumbnail?id=1PdKjZBxuBuBn8sKQxIt5senNF2S1mUyb&sz=w1000', 'caption' => 'Diálogo de saberes'],
    ['url' => 'https://drive.google.com/thumbnail?id=1qG2IDvfGErFT_a0i8I2PGUR67zfuj1eE&sz=w1000', 'caption' => 'Diálogo de saberes'],
    ['url' => 'https://drive.google.com/thumbnail?id=1d1RmLAjIKzlKg3-ASwN3Qdcwvi7eVC-B&sz=w1000', 'caption' => 'Encuentro comunitario'],
    // Las siguientes 5 vienen de SharePoint institucional: por ahora puede que no
    // carguen para los visitantes, porque ese enlace exige iniciar sesión con la
    // cuenta @ucundinamarca.edu.co. Mientras tanto se ven con un ícono de
    // reemplazo. Ver la nota debajo de este bloque sobre cómo solucionarlo.
    ['url' => 'https://mailunicundiedu-my.sharepoint.com/:i:/r/personal/anaesperanzamerchan_ucundinamarca_edu_co/Documents/Etnobot%C3%A1nica/evidencia%20fotografica/chinauta/IMG_20260225_111518.jpg?d=w4c414921860342beac6db916310cede8&csf=1&web=1&e=p8ktJa', 'caption' => 'Encuentro en Chinauta'],
    ['url' => 'https://mailunicundiedu-my.sharepoint.com/:i:/r/personal/anaesperanzamerchan_ucundinamarca_edu_co/Documents/Etnobot%C3%A1nica/evidencia%20fotografica/chinauta/IMG_20260225_112849.jpg?d=wb1e338d9ac5241c6859caa6895f87dc3&csf=1&web=1&e=UrFoe4', 'caption' => 'Encuentro en Chinauta'],
    ['url' => 'https://mailunicundiedu-my.sharepoint.com/:i:/r/personal/anaesperanzamerchan_ucundinamarca_edu_co/Documents/Etnobot%C3%A1nica/evidencia%20fotografica/san%20jose%20piamonte/WhatsApp%20Image%202026-03-20%20at%206.19.39%20PM.jpeg?d=w36b07c567cc24f8a95bb7f1d4bcb4fd8&csf=1&web=1&e=9dE8lN', 'caption' => 'Diálogo de saberes en San José de Piamonte'],
    ['url' => 'https://mailunicundiedu-my.sharepoint.com/:i:/r/personal/anaesperanzamerchan_ucundinamarca_edu_co/Documents/Etnobot%C3%A1nica/evidencia%20fotografica/plaza%20mercado/IMG_20260220_092810.jpg?d=wb16c2a6f6b7f49b6bf9034763ec5e20f&csf=1&web=1&e=dpzLGH', 'caption' => 'Plaza de mercado de Fusagasugá'],
    ['url' => 'https://mailunicundiedu-my.sharepoint.com/:i:/r/personal/anaesperanzamerchan_ucundinamarca_edu_co/Documents/Etnobot%C3%A1nica/evidencia%20fotografica/Mercado%20artesanal/cc9bfa1d-76d3-4c65-a718-c9084ab4dafb.jfif?d=web70c69c1be4485a8b674b3d8657b838&csf=1&web=1&e=lJzywA', 'caption' => 'Mercado artesanal'],
  ];
@endphp
<div class="container">
  <div class="sec-inner">
    <p class="sec-titulo">Dialogando Saberes</p>
    <p class="sec-sub">Encuentros y diálogos de saberes con las comunidades de Fusagasugá</p>

    <div class="ds-carousel-wrap">
      <button class="ds-arrow ds-prev" onclick="dsScroll(-1)" aria-label="Foto anterior"><i class="fas fa-chevron-left"></i></button>
      <div class="ds-track" id="ds-track">
        @foreach($dialogandoSaberes as $foto)
          <figure class="ds-slide">
            <img src="{{ $foto['url'] }}" alt="{{ $foto['caption'] }}" loading="lazy"
                 onclick="abrirLightboxDS(this.src, this.alt)"
                 onerror="this.closest('.ds-slide').classList.add('ds-broken')">
            <figcaption>{{ $foto['caption'] }}</figcaption>
          </figure>
        @endforeach
      </div>
      <button class="ds-arrow ds-next" onclick="dsScroll(1)" aria-label="Foto siguiente"><i class="fas fa-chevron-right"></i></button>
    </div>
  </div>
</div>

{{-- Lightbox de Dialogando Saberes --}}
<div id="ds-lightbox" onclick="cerrarLightboxDS()"
     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.88);z-index:9999;align-items:center;justify-content:center;cursor:zoom-out">
  <img id="ds-lightbox-img" src="" alt="" style="max-width:92vw;max-height:88vh;border-radius:10px;box-shadow:0 10px 40px rgba(0,0,0,.6)">
  <button onclick="cerrarLightboxDS()" aria-label="Cerrar"
          style="position:absolute;top:18px;right:22px;background:rgba(255,255,255,.15);border:none;color:#fff;width:40px;height:40px;border-radius:50%;font-size:1.2rem;cursor:pointer">
    <i class="fas fa-times"></i>
  </button>
</div>

<script>
function dsScroll(dir) {
  const track = document.getElementById('ds-track');
  const slide = track.querySelector('.ds-slide');
  if (!slide) return;
  const styles = getComputedStyle(track);
  const gap = parseFloat(styles.columnGap || styles.gap || 18);
  track.scrollBy({ left: (slide.offsetWidth + gap) * dir, behavior: 'smooth' });
}
function abrirLightboxDS(src, alt) {
  document.getElementById('ds-lightbox-img').src = src;
  document.getElementById('ds-lightbox-img').alt = alt;
  document.getElementById('ds-lightbox').style.display = 'flex';
}
function cerrarLightboxDS() {
  document.getElementById('ds-lightbox').style.display = 'none';
}
</script>

<style>
.ds-carousel-wrap { position: relative; display: flex; align-items: center; gap: 10px; }
.ds-track {
  display: flex; gap: 18px; overflow-x: auto; scroll-snap-type: x mandatory;
  scroll-behavior: smooth; padding: 6px 2px 16px; flex: 1;
}
.ds-track::-webkit-scrollbar { height: 8px; }
.ds-track::-webkit-scrollbar-thumb { background: var(--border); border-radius: 4px; }
.ds-slide {
  position: relative; flex: 0 0 260px; scroll-snap-align: start;
  border-radius: 14px; overflow: hidden; background: var(--bg-card);
  box-shadow: var(--sombra); cursor: zoom-in; margin: 0;
}
.ds-slide img { width: 100%; height: 190px; object-fit: cover; display: block; transition: transform .35s ease; }
.ds-slide:hover img { transform: scale(1.06); }
.ds-slide figcaption {
  padding: 10px 12px; font-size: .82rem; color: var(--texto); font-weight: 600;
  background: var(--bg-card);
}
.ds-slide.ds-broken { cursor: default; }
.ds-slide.ds-broken img { display: none; }
.ds-slide.ds-broken::before {
  content: "\f03e"; font-family: "Font Awesome 6 Free"; font-weight: 900;
  font-size: 2.2rem; color: var(--texto-suave); opacity: .4;
  height: 190px; display: flex; align-items: center; justify-content: center;
}
.ds-arrow {
  flex-shrink: 0; width: 42px; height: 42px; border-radius: 50%; border: none;
  background: var(--verde); color: #fff; cursor: pointer; display: flex;
  align-items: center; justify-content: center; box-shadow: var(--sombra);
  transition: background .2s ease;
}
.ds-arrow:hover { background: var(--verde-mid); }
@media (max-width: 700px) {
  .ds-slide { flex-basis: 210px; }
  .ds-slide img, .ds-slide.ds-broken::before { height: 150px; }
  .ds-arrow { width: 34px; height: 34px; font-size: .85rem; }
}
</style>

{{-- ══ SECCIÓN: Aportes de la comunidad ══ --}}
@if($aportesAprobados->count())
<div style="background:var(--pale);padding:48px 0 56px;margin-top:16px">
  <div class="container">
    <div class="sec-inner" style="padding-top:0">
      <div style="display:flex;align-items:flex-end;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:24px">
        <div>
          <p class="sec-titulo" style="margin-bottom:4px">Aportes de la comunidad</p>
          <p class="sec-sub" style="margin-bottom:0">Conocimiento compartido y verificado por nuestros moderadores</p>
        </div>
        <a href="{{ route('aportar') }}" class="btn-catalogo" style="font-size:.85rem;padding:.6rem 1.2rem">
          <i class="fas fa-seedling"></i> Ver todos los aportes
        </a>
      </div>

      <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:18px">
        @foreach($aportesAprobados as $ap)
          <a href="{{ route('aportes.show', $ap->id) }}" class="card-aporte-home" style="text-decoration:none;color:inherit">
            <div class="cah-img">
              @if($ap->img_path)
                <img src="{{ $ap->imagenUrl }}" alt="{{ $ap->nombre_planta }}">
              @else
                <div class="cah-noimg">
                  <i class="fas fa-seedling"></i>
                </div>
              @endif
              <span class="cah-cat">{{ $ap->categoria }}</span>
            </div>
            <div class="cah-body">
              <strong class="cah-nombre">{{ $ap->nombre_planta }}</strong>
              @if($ap->cientifico)
                <em class="cah-cientifico">{{ $ap->cientifico }}</em>
              @endif
              <p class="cah-uso"><i class="fas fa-circle-info"></i> {{ $ap->uso }}</p>
              <p class="cah-prep">{{ Str::limit($ap->preparacion, 90) }}</p>
              <div class="cah-footer">
                <span><i class="fas fa-calendar-alt"></i> {{ $ap->creado_en->format('d M Y') }}</span>
                <span class="cah-link">Leer más <i class="fas fa-arrow-right" style="font-size:.7rem"></i></span>
              </div>
            </div>
          </a>
        @endforeach
      </div>
    </div>
  </div>
</div>
@endif

@endsection

<style>
.catalogo-actions { text-align: center; margin-bottom: 2rem; }
.btn-catalogo {
  display: inline-flex; align-items: center; gap: .5rem;
  padding: .75rem 1.5rem; background: var(--verde); color: white;
  text-decoration: none; border-radius: 8px; font-weight: 500;
  transition: background .3s ease;
}
.btn-catalogo:hover { background: var(--verde-mid); color: white; text-decoration: none; }
.btn-catalogo i { font-size: 1.1rem; }

/* ── Tarjeta aporte home ── */
.card-aporte-home {
  background: var(--bg-card); border-radius: 16px;
  overflow: hidden; display: flex; flex-direction: column;
  box-shadow: var(--sombra); border: 1px solid transparent;
  transition: var(--trans);
}
.card-aporte-home:hover {
  transform: translateY(-6px); box-shadow: var(--sombra-lg);
  border-color: var(--verde-mid);
}
.cah-img { position: relative; height: 150px; overflow: hidden; background: #c8e6c9; }
.cah-img img { width: 100%; height: 100%; object-fit: cover; transition: transform .35s ease; }
.card-aporte-home:hover .cah-img img { transform: scale(1.05); }
.cah-noimg {
  height: 100%; display: flex; align-items: center; justify-content: center;
  background: linear-gradient(135deg, var(--pale) 0%, #c8e6c9 100%);
  font-size: 2.2rem; color: var(--verde-mid); opacity: .45;
}
.cah-cat {
  position: absolute; top: 10px; right: 10px;
  background: rgba(0,0,0,.55); color: #fff; backdrop-filter: blur(4px);
  padding: 3px 10px; border-radius: 20px; font-size: .72rem; font-weight: 700;
}
.cah-body { padding: 16px 18px; flex: 1; display: flex; flex-direction: column; gap: 6px; }
.cah-nombre { font-size: .97rem; color: var(--texto); font-weight: 700; line-height: 1.3; }
.cah-cientifico { font-size: .78rem; color: var(--texto-suave); display: block; }
.cah-uso { font-size: .8rem; color: var(--texto-suave); margin: 0; }
.cah-uso i { color: var(--verde-mid); margin-right: 3px; }
.cah-prep { font-size: .82rem; color: var(--texto); line-height: 1.5; margin: 0; flex: 1; }
.cah-footer {
  display: flex; align-items: center; justify-content: space-between;
  padding-top: 10px; border-top: 1px solid var(--border-lt);
  font-size: .74rem; color: var(--texto-suave); margin-top: 4px;
}
.cah-link { color: var(--verde); font-weight: 600; font-size: .78rem; }
</style>
