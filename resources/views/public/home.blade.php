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

@endsection

<style>
.catalogo-actions {
  text-align: center;
  margin-bottom: 2rem;
}

.btn-catalogo {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.75rem 1.5rem;
  background: var(--verde);
  color: white;
  text-decoration: none;
  border-radius: 8px;
  font-weight: 500;
  transition: background 0.3s ease;
}

.btn-catalogo:hover {
  background: var(--verde-mid);
  color: white;
  text-decoration: none;
}

.btn-catalogo i {
  font-size: 1.1rem;
}
</style>
