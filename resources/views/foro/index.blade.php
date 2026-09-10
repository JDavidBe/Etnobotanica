@extends('layouts.app')

@section('title', 'Foro — Etnobotánica')

@section('content')
<div class="container">
  <div class="sec-inner">

    <div class="foro-topbar">
      <div>
        <p class="sec-titulo" style="margin-bottom:4px">Foro de la comunidad</p>
        <p class="foro-subtitulo">Comparte dudas, experiencias e ideas sobre saberes etnobotánicos.</p>
      </div>
      @auth
        <a href="{{ route('foro.create') }}" class="btn-hdr btn-aportar">
          <i class="fas fa-plus"></i> Nuevo tema
        </a>
      @else
        <a href="{{ route('login') }}" class="btn-hdr btn-admin">
          <i class="fas fa-sign-in-alt"></i> Inicia sesión para participar
        </a>
      @endauth
    </div>

    <div class="foro-cat-tabs">
      <a href="{{ route('foro.index') }}" class="foro-cat-tab {{ !$categoriaActiva ? 'on' : '' }}">Todos</a>
      @foreach($categorias as $key => $label)
        <a href="{{ route('foro.index', ['categoria' => $key]) }}"
           class="foro-cat-tab {{ $categoriaActiva === $key ? 'on' : '' }}">{{ $label }}</a>
      @endforeach
    </div>

    <div class="foro-lista">
      @forelse($temas as $tema)
        <a href="{{ route('foro.show', $tema) }}" class="foro-item {{ $tema->fijado ? 'fijado' : '' }}">
          <div class="foro-item-main">
            <div class="foro-item-badges">
              @if($tema->fijado)
                <span class="foro-badge pin"><i class="fas fa-thumbtack"></i> Fijado</span>
              @endif
              @if($tema->cerrado)
                <span class="foro-badge cerrado"><i class="fas fa-lock"></i> Cerrado</span>
              @endif
              <span class="foro-badge cat">{{ $tema->categoriaLabel() }}</span>
            </div>
            <strong class="foro-item-titulo">{{ $tema->titulo }}</strong>
            <p class="foro-item-meta">por {{ $tema->user->name }} · {{ $tema->created_at->diffForHumans() }}</p>
          </div>
          <div class="foro-item-stats">
            <span><i class="fas fa-comment"></i> {{ $tema->respuestas_count }}</span>
            <span><i class="fas fa-eye"></i> {{ $tema->vistas }}</span>
          </div>
        </a>
      @empty
        <div class="com-empty">
          <i class="fas fa-comment-slash"></i>
          <p>Todavía no hay temas en esta categoría. ¡Sé el primero en abrir uno!</p>
        </div>
      @endforelse
    </div>

    <div class="foro-paginacion">
      {{ $temas->links() }}
    </div>

  </div>
</div>

<style>
.foro-topbar { display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:14px; margin-bottom:22px; }
.foro-subtitulo { color:var(--texto-suave); font-size:.9rem; margin:0; }

.foro-cat-tabs { display:flex; flex-wrap:wrap; gap:8px; margin-bottom:22px; }
.foro-cat-tab {
  padding:6px 16px; border-radius:20px; font-size:.82rem; font-weight:600;
  border:1px solid var(--border-lt); color:var(--texto-suave); text-decoration:none;
  transition: all .15s; white-space:nowrap;
}
.foro-cat-tab:hover { border-color:var(--verde-mid); color:var(--verde-mid); }
.foro-cat-tab.on { background:var(--verde); border-color:var(--verde); color:#fff; }

.foro-lista { display:flex; flex-direction:column; gap:12px; }
.foro-item {
  display:flex; align-items:center; justify-content:space-between; gap:16px;
  background:var(--bg-card); border:1px solid var(--border-lt); border-radius:14px;
  padding:16px 20px; text-decoration:none; transition: box-shadow .2s, border-color .2s;
}
.foro-item:hover { box-shadow:0 2px 12px rgba(0,0,0,.07); border-color:var(--verde-mid); }
.foro-item.fijado { border-left:4px solid var(--dorado, #c9a227); }
.foro-item-main { min-width:0; flex:1; }
.foro-item-badges { display:flex; gap:6px; flex-wrap:wrap; margin-bottom:6px; }
.foro-badge { font-size:.68rem; font-weight:700; padding:2px 9px; border-radius:12px; white-space:nowrap; }
.foro-badge.pin { background:#fdf2d0; color:#8a6d1a; }
.foro-badge.cerrado { background:#eee; color:#777; }
.foro-badge.cat { background:var(--pale); color:var(--verde); }
.foro-item-titulo { display:block; color:var(--texto); font-size:1rem; margin-bottom:4px; }
.foro-item-meta { color:var(--texto-suave); font-size:.8rem; margin:0; }
.foro-item-stats { display:flex; gap:14px; color:var(--texto-suave); font-size:.85rem; flex-shrink:0; }
.foro-item-stats span { display:flex; align-items:center; gap:5px; }

.foro-paginacion { margin-top:24px; }

/* ── Responsive ── */
@media (max-width: 700px) {
  .foro-topbar { flex-direction:column; align-items:stretch; }
  .foro-topbar > a { width:100%; justify-content:center; }
  .foro-item { flex-direction:column; align-items:stretch; gap:10px; }
  .foro-item-stats { justify-content:flex-start; }
  .foro-cat-tabs { flex-wrap:nowrap; overflow-x:auto; -webkit-overflow-scrolling:touch; padding-bottom:4px; }
  .foro-cat-tab { flex-shrink:0; }
}
</style>
@endsection
