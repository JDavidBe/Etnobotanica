    <div class="plant-grid" id="search-results">
  @forelse($plantas as $planta)
    <a href="{{ route('plantas.show', $planta->id) }}" class="search-item">
      <div class="search-item-name">{{ $planta->nombre }}</div>
      <div class="search-item-scient">{{ $planta->cientifico }}</div>
    </a>
  @empty
    <div class="search-empty">Sin resultados</div>
  @endforelse
    </div>