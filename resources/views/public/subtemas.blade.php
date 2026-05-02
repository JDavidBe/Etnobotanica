@extends('layouts.app')

@section('title', $categoria->nombre . ' — Etnobotánica')


@section('breadcrumbs')
  <i class="fas fa-chevron-right sep"></i>
  <span class="crumb">{{ $categoria->nombre }}</span>
@endsection

@section('content')

<div class="container">
  <div class="sec-inner">
    <a href="{{ route('home') }}" class="back-link">
      <i class="fas fa-arrow-left"></i> Volver a categorías
    </a>
    <p class="sec-titulo">{{ $categoria->nombre }}</p>
    <p class="sec-sub">{{ $categoria->descripcion }}</p>

    <div class="grid-subs">
      <!-- Enlace especial "Todos" -->
      <a href="{{ route('categorias.todos', $categoria->id) }}" class="card-sub card-todos">
        <i class="fas fa-list"></i>
        <h4>Todos</h4>
        <span>{{ $categoria->plantas_count }} plantas</span>
      </a>

      @forelse($categoria->subtemas as $sub)
        <a href="{{ route('subtemas.show', [$categoria->id, $sub->id]) }}" class="card-sub">
          <i class="fas fa-leaf"></i>
          <h4>{{ $sub->nombre }}</h4>
          <span>{{ $sub->plantas_count }} plantas</span>
        </a>
      @empty
        <div class="empty">
          <i class="fas fa-folder-open"></i>
          <p>No hay subtemas registrados en esta categoría.</p>
        </div>
      @endforelse
    </div>
  </div>
</div>

@endsection

<style>
.card-todos {
  background: var(--verde) !important;
  color: white !important;
  border: 2px solid var(--verde-mid);
}

.card-todos:hover {
  background: var(--verde-mid) !important;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.card-todos i {
  color: white !important;
}

.card-todos h4 {
  color: white !important;
}

.card-todos span {
  background: rgba(255,255,255,0.2);
  color: white !important;
}
</style>
