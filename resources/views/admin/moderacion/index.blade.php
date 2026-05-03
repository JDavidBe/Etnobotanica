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
            <button class="abtn"
                    style="background:var(--pale);color:var(--verde);border:1px solid #c8e6c9"
                    onclick="abrirDetallesAporte({{ $aporte->id }})">
              <i class="fas fa-eye"></i> Detalles
            </button>

            @if($aporte->estado !== 'aprobado')
              <form method="POST"
                    action="{{ route('admin.moderacion.aprobar', $aporte) }}"
                    style="display:inline">
                @csrf @method('PATCH')
                <button type="submit" class="abtn ab-ok">Aprobar</button>
              </form>
            @endif

            @if($aporte->estado !== 'rechazado')
              <button class="abtn ab-rej"
                      onclick="abrirModalRechazo({{ $aporte->id }}, '{{ addslashes($aporte->nombre_planta) }}')">
                <i class="fas fa-times"></i> Rechazar
              </button>
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


{{-- Modal motivo de rechazo --}}
<div id="modal-rechazo" class="modal-ov" onclick="if(event.target===this)cerrarModalRechazo()">
  <div class="modal" style="max-width:480px">
    <div class="modal-hdr">
      <h3><i class="fas fa-times-circle"></i> Rechazar aporte</h3>
      <button class="modal-close" onclick="cerrarModalRechazo()"><i class="fas fa-times"></i></button>
    </div>
    <form id="form-rechazo" method="POST" action="">
      @csrf
      @method('POST')
      <div class="modal-body">
        <p style="font-size:.88rem;color:var(--texto-suave);margin:0 0 14px">
          Estás rechazando el aporte <strong id="rechazo-nombre"></strong>.
          El aportante verá este motivo en su panel.
        </p>
        <div class="f-group">
          <label style="font-size:.82rem;font-weight:700;color:var(--texto);display:block;margin-bottom:6px">
            Motivo del rechazo *
          </label>
          <textarea name="motivo_rechazo" rows="4"
                    placeholder="Explica claramente por qué no puede publicarse este aporte…"
                    required maxlength="500"
                    style="width:100%;box-sizing:border-box;border:1px solid var(--border-lt);border-radius:9px;padding:10px 14px;font-size:.88rem;font-family:'Nunito',sans-serif;background:var(--fondo-card,#fafafa);color:var(--texto);resize:vertical"></textarea>
          <span style="font-size:.72rem;color:var(--texto-suave);float:right" id="rechazo-chars">0 / 500</span>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="abtn" onclick="cerrarModalRechazo()" style="background:var(--fondo-card);border:1px solid var(--border-lt)">Cancelar</button>
        <button type="submit" class="abtn ab-rej"><i class="fas fa-times-circle"></i> Confirmar rechazo</button>
      </div>
    </form>
  </div>
</div>

{{-- Modal detalles del aporte --}}
<div id="modal-det-aporte" class="modal-ov" onclick="if(event.target===this)cerrarDetallesAporte()">
  <div class="modal" style="max-width:620px">
    <div class="modal-hdr">
      <h3><i class="fas fa-clipboard-list"></i> <span id="mdet-titulo"></span></h3>
      <button class="modal-close" onclick="cerrarDetallesAporte()"><i class="fas fa-times"></i></button>
    </div>

    {{-- Imagen --}}
    <div id="mdet-img-wrap" style="display:none">
      <img id="mdet-img" src="" alt=""
           style="width:100%;max-height:220px;object-fit:cover">
    </div>

    <div class="modal-body">

      {{-- Badges estado + categoría --}}
      <div style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:20px;align-items:center">
        <span id="mdet-estado" class="pill"></span>
        <span id="mdet-categoria"
              style="background:var(--pale);color:var(--verde);padding:4px 12px;border-radius:20px;font-size:.78rem;font-weight:700"></span>
        <em id="mdet-cientifico" style="font-size:.82rem;color:var(--texto-suave)"></em>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px 20px">

        <div style="grid-column:1/-1">
          <p class="mdet-label">Uso principal</p>
          <p id="mdet-uso" class="mdet-val"></p>
        </div>

        <div style="grid-column:1/-1">
          <p class="mdet-label">Preparación / uso</p>
          <p id="mdet-preparacion" class="mdet-val" style="white-space:pre-line;line-height:1.65"></p>
        </div>

        <div id="mdet-relato-wrap" style="grid-column:1/-1;display:none">
          <p class="mdet-label">Historia personal</p>
          <p id="mdet-relato" class="mdet-val" style="font-style:italic;color:var(--texto-suave);line-height:1.6"></p>
        </div>

        <div>
          <p class="mdet-label">Enviado por</p>
          <p id="mdet-enviado" class="mdet-val"></p>
        </div>

        <div>
          <p class="mdet-label">IP de origen</p>
          <p id="mdet-ip" class="mdet-val" style="font-family:monospace;font-size:.82rem"></p>
        </div>

        <div style="grid-column:1/-1">
          <p class="mdet-label">Fecha de envío</p>
          <p id="mdet-fecha" class="mdet-val"></p>
        </div>

      </div>
    </div>

    <div class="modal-footer">
      <button class="btn-cancel" onclick="cerrarDetallesAporte()">Cerrar</button>
    </div>
  </div>
</div>

<style>
.mdet-label {
  font-size:.72rem;font-weight:700;text-transform:uppercase;
  letter-spacing:.06em;color:var(--texto-suave);margin:0 0 3px;
}
.mdet-val { margin:0;font-size:.88rem;color:var(--texto); }
</style>

@endsection

@push('scripts')
<script>
function abrirModal(id) {
  const m = document.getElementById(id);
  if (m) { m.style.display = 'flex'; }
}
function cerrarModal(id) {
  const m = document.getElementById(id);
  if (m) { m.style.display = 'none'; }
}

// Datos completos de todos los aportes de la página actual
const _modAportes = @json($aportes->items());

const _estadoClases = {
  pendiente: 'p-pend',
  aprobado:  'p-ok',
  rechazado: 'p-rej',
};
const _estadoLabels = {
  pendiente: 'Pendiente',
  aprobado:  'Aprobado',
  rechazado: 'Rechazado',
};

function abrirDetallesAporte(id) {
  const ap = _modAportes.find(a => a.id === id);
  if (!ap) return;

  document.getElementById('mdet-titulo').textContent    = ap.nombre_planta;
  document.getElementById('mdet-categoria').textContent = ap.categoria;
  document.getElementById('mdet-cientifico').textContent= ap.cientifico || '';
  document.getElementById('mdet-uso').textContent       = ap.uso;
  document.getElementById('mdet-preparacion').textContent = ap.preparacion;
  document.getElementById('mdet-enviado').textContent   = ap.enviado_por || 'Anónimo';
  document.getElementById('mdet-ip').textContent        = ap.ip_origen  || '—';

  const fechaEl = document.getElementById('mdet-fecha');
  fechaEl.textContent = new Date(ap.creado_en).toLocaleString('es-CO', {
    day:'2-digit', month:'long', year:'numeric', hour:'2-digit', minute:'2-digit'
  });

  // Estado pill
  const estadoEl = document.getElementById('mdet-estado');
  estadoEl.className = 'pill ' + (_estadoClases[ap.estado] || '');
  estadoEl.textContent = _estadoLabels[ap.estado] || ap.estado;

  // Relato
  const relatoWrap = document.getElementById('mdet-relato-wrap');
  if (ap.relato) {
    document.getElementById('mdet-relato').textContent = ap.relato;
    relatoWrap.style.display = 'block';
  } else {
    relatoWrap.style.display = 'none';
  }

  // Imagen
  const imgWrap = document.getElementById('mdet-img-wrap');
  if (ap.img_path) {
    document.getElementById('mdet-img').src = '/storage/' + ap.img_path;
    document.getElementById('mdet-img').alt = ap.nombre_planta;
    imgWrap.style.display = 'block';
  } else {
    imgWrap.style.display = 'none';
  }

  const modal = document.getElementById('modal-det-aporte');
  modal.style.display = 'flex';
  modal.classList.add('open');
}

function cerrarDetallesAporte() {
  const modal = document.getElementById('modal-det-aporte');
  modal.style.display = 'none';
  modal.classList.remove('open');
}

// ── Modal rechazo ──
function abrirModalRechazo(id, nombre) {
  const form = document.getElementById('form-rechazo');
  form.action = '/admin/moderacion/' + id + '/rechazar';
  document.getElementById('rechazo-nombre').textContent = '«' + nombre + '»';
  const ta = form.querySelector('textarea');
  ta.value = '';
  document.getElementById('rechazo-chars').textContent = '0 / 500';
  ta.addEventListener('input', () => {
    document.getElementById('rechazo-chars').textContent = ta.value.length + ' / 500';
  }, {once: false});
  const modal = document.getElementById('modal-rechazo');
  modal.style.display = 'flex';
  modal.classList.add('open');
  setTimeout(() => ta.focus(), 80);
}
function cerrarModalRechazo() {
  const modal = document.getElementById('modal-rechazo');
  modal.style.display = 'none';
  modal.classList.remove('open');
}
</script>
@endpush
