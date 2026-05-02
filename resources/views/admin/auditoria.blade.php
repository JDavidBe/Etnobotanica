@extends('layouts.admin')

@section('title', 'Actividad (RF-05)')
@section('admin-title', 'Actividad (RF-05)')

@section('content')

<div class="tbl-card">
  <div class="tbl-hdr">
    <h3>Registro de auditoría (RF-05)</h3>
  </div>

  <table>
    <thead>
      <tr>
        <th>Acción</th>
        <th>Elemento</th>
        <th>Usuario</th>
        <th>Fecha</th>
      </tr>
    </thead>
    <tbody>
      @forelse($logs as $log)
        <tr>
          <td>
            @php
              $clase = match($log->accion) {
                'PUBLICÓ' => 'lb-pub',
                'EDITÓ'   => 'lb-edit',
                'CREÓ'    => 'lb-crea',
                'RECHAZÓ' => 'lb-rej',
                default   => 'lb-edit',
              };
            @endphp
            <span class="log-badge {{ $clase }}">{{ $log->accion }}</span>
          </td>
          <td>{{ $log->elemento }}</td>
          <td>{{ $log->usuario?->name ?? 'Sistema' }}</td>
          <td>{{ $log->fecha->format('d/m/Y, g:i a') }}</td>
        </tr>
      @empty
        <tr>
          <td colspan="4" style="text-align:center;color:var(--texto-suave);padding:30px">
            Sin registros de auditoría.
          </td>
        </tr>
      @endforelse
    </tbody>
  </table>

  @if($logs->hasPages())
    <div style="padding:14px 20px;border-top:1px solid var(--border-lt)">
      {{ $logs->links() }}
    </div>
  @endif
</div>

@endsection
