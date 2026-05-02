@extends('layouts.app')

@section('title', 'Catálogo Completo — Etnobotánica')

@section('breadcrumbs')
  <i class="fas fa-chevron-right sep"></i>
  <span class="crumb">Catálogo Completo</span>
@endsection

@section('content')

<div class="container">
  <div class="sec-inner">
    <a href="{{ route('home') }}" class="back-link">
      <i class="fas fa-arrow-left"></i> Volver al inicio
    </a>
    <p class="sec-titulo">Catálogo Completo</p>
    <p class="sec-sub">Todas las categorías y sus plantas</p>

    @forelse($categorias as $categoria)
      <div class="categoria-section">
        <div class="categoria-header">
          <i class="{{ $categoria->icono }}"></i>
          <h3>{{ $categoria->nombre }}</h3>
          <span class="categoria-desc">{{ $categoria->descripcion }}</span>
        </div>

        <div class="grid-subs">
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
    @empty
      <div class="empty">
        <i class="fas fa-seedling"></i>
        <p>No hay categorías registradas.</p>
      </div>
    @endforelse
  </div>
</div>

@endsection

<style>
.categoria-section {
  margin-bottom: 3rem;
  padding-bottom: 2rem;
  border-bottom: 1px solid var(--borde);
}

.categoria-section:last-child {
  border-bottom: none;
  margin-bottom: 0;
  padding-bottom: 0;
}

.categoria-header {
  display: flex;
  align-items: center;
  gap: 1rem;
  margin-bottom: 1.5rem;
  padding: 1rem;
  background: var(--fondo-card);
  border-radius: 8px;
}

.categoria-header i {
  font-size: 1.5rem;
  color: var(--verde-mid);
}

.categoria-header h3 {
  margin: 0;
  color: var(--verde);
}

.categoria-desc {
  color: var(--texto-suave);
  font-style: italic;
  margin-left: auto;
}
</style>