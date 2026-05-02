@extends('layouts.admin')

@section('title', 'Moderación (RF-07)')
@section('admin-title', 'Moderación (RF-07)')

@section('content')

@if(session('success'))
  <div id="flash-success" data-msg="{{ session('success') }}" style="display:none"></div>
@endif

<div class="alert-warn">
  <i class="fas fa-exclamation-triangle"></i>
  <strong>{{ $pendientes }} aportes</strong> están pendientes de revisión.
  @if($aportes->count() !== $pendientes)
    <span>Mostrando {{ $aportes->count() }} aportes.</span>
  @endif
</div>

<div style="margin:1rem 0;display:flex;flex-wrap:wrap;gap:.5rem">
  @php
    $filtros = [
      '' => 'Todos',
      'pendiente' => 'Pendientes',
      'aprobado' => 'Aprobados',
      'rechazado' => 'Rechazados',
    ];
  @endphp
  @foreach($filtros as $value => $label)
    <a href="{{ route('admin.moderacion.index', array_filter(['estado' => $value])) }}"
       class="abtn {{ $estadoFiltro === $value || ($value === '' && !$estadoFiltro) ? 'ab-active' : 'abtn-secondary' }}"
       style="padding:.5rem 1rem">
      {{ $label }}
    </a>
  @endforeach
</div>

<div class="tbl-card">
  <div class="tbl-hdr">
    <h3>Aportes pendientes</h3>
  </div>

  <table>
    <thead>
      <tr>
        <th>Foto</th>
        <th>Planta</th>
        <th>Uso descrito</th>
        <th>Estado</th>
        <th>Enviado por</th>
        <th>Fecha</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      @forelse($aportes as $aporte)
        <tr id="aporte-{{ $aporte->id }}">
          <td style="width:60px;padding:6px 10px">
            @if($aporte->img_path)
              <img src="{{ asset('storage/' . $aporte->img_path) }}"
                   alt="{{ $aporte->nombre_planta }}"
                   style="width:52px;height:52px;object-fit:cover;border-radius:8px;cursor:pointer"
                   onclick="abrirModal('modal-img-{{ $aporte->id }}')">
            @else
              <div style="width:52px;height:52px;border-radius:8px;background:var(--fondo-card);display:flex;align-items:center;justify-content:center;color:var(--texto-suave)">
                <i class="fas fa-image" style="font-size:.9rem"></i>
              </div>
            @endif
          </td>
          <td><strong>{{ $aporte->nombre_planta }}</strong></td>
          <td>{{ Str::limit($aporte->preparacion, 60) }}</td>
          <td>
            @if($aporte->estado === 'pendiente')
              <span class="badge badge-warning">Pendiente</span>
            @elseif($aporte->estado === 'aprobado')
              <span class="badge badge-success">Aprobado</span>
            @else
              <span class="badge badge-danger">Rechazado</span>
            @endif
          </td>
          <td>{{ $aporte->enviado_por }}</td>
          <td>{{ $aporte->creado_en->format('d M Y') }}</td>
          <td>
            @if($aporte->estado !== 'aprobado')
              <form method="POST"
                    action="{{ route('admin.moderacion.aprobar', $aporte) }}"
                    style="display:inline">
                @csrf @method('PATCH')
                <button type="submit" class="abtn ab-ok">Aprobar</button>
              </form>
            @endif

            @if($aporte->estado !== 'rechazado')
              <form method="POST"
                    action="{{ route('admin.moderacion.rechazar', $aporte) }}"
                    style="display:inline">
                @csrf @method('PATCH')
                <button type="submit" class="abtn ab-rej">Rechazar</button>
              </form>
            @endif

            <form method="POST"
                  action="{{ route('admin.moderacion.destroy', $aporte) }}"
                  id="form-del-aporte-{{ $aporte->id }}"
                  style="display:inline">
              @csrf @method('DELETE')
              <button type="button" class="abtn ab-del"
                      onclick="alpineConfirm(
                        '¿Eliminar aporte?',
                        '¿Eliminar definitivamente el aporte de «{{ addslashes($aporte->nombre_planta) }}»?',
                        'form-del-aporte-{{ $aporte->id }}'
                      )">Eliminar</button>
            </form>
          </td>
        </tr>

        {{-- Modal imagen aporte --}}
        @if($aporte->img_path)
          <div id="modal-img-{{ $aporte->id }}" class="modal-ov" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.75);z-index:9999;align-items:center;justify-content:center"
               onclick="if(event.target===this)cerrarModal('modal-img-{{ $aporte->id }}')">
            <div style="position:relative;max-width:90vw;max-height:90vh">
              <img src="{{ asset('storage/' . $aporte->img_path) }}"
                   alt="{{ $aporte->nombre_planta }}"
                   style="max-width:85vw;max-height:85vh;border-radius:14px;object-fit:contain;box-shadow:0 8px 40px rgba(0,0,0,.6)">
              <button onclick="cerrarModal('modal-img-{{ $aporte->id }}')"
                      style="position:absolute;top:-14px;right:-14px;width:34px;height:34px;border-radius:50%;background:#fff;border:none;cursor:pointer;font-size:1rem;display:flex;align-items:center;justify-content:center;box-shadow:0 2px 8px rgba(0,0,0,.3)">
                <i class="fas fa-times"></i>
              </button>
            </div>
          </div>
        @endif

      @empty
        <tr>
          <td colspan="7" style="text-align:center;color:var(--texto-suave);padding:30px">
            No hay aportes {{ $estadoFiltro ? 'en estado «' . $filtros[$estadoFiltro] . '»' : '' }}
          </td>
        </tr>
      @endforelse
    </tbody>
  </table>

  {{-- Paginación --}}
  @if($aportes->hasPages())
    <div style="margin-top:1rem;text-align:center">
      {{ $aportes->appends(request()->query())->links() }}
    </div>
  @endif
</div>

@endsection

@push('scripts')
<script>
// Abrir modal de imagen
function abrirModal(id) {
  const m = document.getElementById(id);
  if (m) { m.style.display = 'flex'; }
}
function cerrarModal(id) {
  const m = document.getElementById(id);
  if (m) { m.style.display = 'none'; }
}
</script>
@endpush
