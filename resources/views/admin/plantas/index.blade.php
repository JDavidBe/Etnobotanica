@extends('layouts.admin')

@section('title', 'Gestión de Plantas')
@section('admin-title', 'Gestión de Plantas')

@section('content')

@if(session('success'))
  <div id="flash-success" data-msg="{{ session('success') }}" style="display:none"></div>
@endif

<div class="tbl-card">
  <div class="tbl-hdr">
    <h3>Gestión de Plantas</h3>
    <a href="{{ route('admin.plantas.create') }}" class="ab-add">
      <i class="fas fa-plus"></i> Nueva planta
    </a>
  </div>

  {{-- Buscador --}}
  <div class="tbl-search">
    <form method="GET" action="{{ route('admin.plantas.index') }}">
      <input class="tbl-input"
             name="q"
             value="{{ request('q') }}"
             placeholder="Buscar…"
             autocomplete="off">
    </form>
  </div>

  <table>
    <thead>
      <tr>
        <th>Planta</th>
        <th>Categoría</th>
        <th>Subtema</th>
        <th>Estado</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      @forelse($plantas as $planta)
        <tr>
          <td>
            <strong>{{ $planta->nombre }}</strong><br>
            <small style="color:var(--texto-suave)">{{ $planta->cientifico }}</small>
          </td>
          <td>{{ $planta->categoria->nombre }}</td>
          <td>{{ $planta->subtema->nombre }}</td>
          <td>
            <span class="pill {{ $planta->verificada ? 'p-ok' : 'p-pend' }}">
              {{ $planta->verificada ? 'Verificada' : 'Pendiente' }}
            </span>
          </td>
          <td>
            <a href="{{ route('admin.plantas.edit', $planta) }}" class="abtn ab-ok">Editar</a>
            <form method="POST"
                  action="{{ route('admin.plantas.destroy', $planta) }}"
                  style="display:inline"
                  id="form-del-planta-{{ $planta->id }}">
              @csrf
              @method('DELETE')
              <button type="button" class="abtn ab-del"
                      onclick="alpineConfirm(
                        '¿Eliminar planta?',
                        '¿Eliminar &laquo;{{ addslashes($planta->nombre) }}&raquo;? Esta acción no se puede deshacer.',
                        'form-del-planta-{{ $planta->id }}'
                      )">Eliminar</button>
            </form>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="5" style="text-align:center;color:var(--texto-suave);padding:30px">
            No hay plantas registradas.
          </td>
        </tr>
      @endforelse
    </tbody>
  </table>

  {{-- Paginación --}}
  @if($plantas->hasPages())
    <div style="padding:14px 20px;border-top:1px solid var(--border-lt)">
      {{ $plantas->withQueryString()->links() }}
    </div>
  @endif
</div>

@endsection
