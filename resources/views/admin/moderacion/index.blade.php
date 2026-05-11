@extends('layouts.admin')

@section('title', 'Moderación')
@section('admin-title', 'Moderación ')

@section('content')

@if(session('success'))
  <div id="flash-success" data-msg="{{ session('success') }}" style="display:none"></div>
@endif

{{-- ══ ESTADÍSTICAS ══ --}}
<div class="stats-grid" style="margin-bottom:18px">
  <div class="stat-card">
    <div class="stat-ico sr"><i class="fas fa-clock"></i></div>
    <div class="stat-info">
      <h3>{{ $pendientes }}</h3>
      <p>Pendientes revisión</p>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-ico sv"><i class="fas fa-check-circle"></i></div>
    <div class="stat-info">
      <h3>{{ $aprobados }}</h3>
      <p>Aprobados</p>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-ico" style="background:#fce4ec"><i class="fas fa-times-circle" style="color:#c62828"></i></div>
    <div class="stat-info">
      <h3>{{ $rechazados }}</h3>
      <p>Rechazados</p>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-ico sa"><i class="fas fa-inbox"></i></div>
    <div class="stat-info">
      <h3>{{ $total }}</h3>
      <p>Total recibidos</p>
    </div>
  </div>
</div>

{{-- ══ GRÁFICAS ══ --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:18px;margin-bottom:20px">

  {{-- Dona: distribución de estados --}}
  <div class="tbl-card" style="padding:20px">
    <h3 style="font-size:.92rem;font-weight:700;margin-bottom:16px">
      <i class="fas fa-chart-pie" style="color:var(--verde-mid)"></i> Distribución de estados
    </h3>
    @if($total > 0)
      <div style="position:relative;height:200px;display:flex;align-items:center;justify-content:center">
        <canvas id="chart-dona"></canvas>
        <div style="position:absolute;text-align:center;pointer-events:none">
          <div style="font-size:1.6rem;font-weight:700;color:var(--texto)">{{ $total }}</div>
          <div style="font-size:.72rem;color:var(--texto-suave)">aportes</div>
        </div>
      </div>
    @else
      <div style="height:200px;display:flex;align-items:center;justify-content:center;color:var(--texto-suave);font-size:.85rem">
        Sin aportes aún
      </div>
    @endif
  </div>

  {{-- Barras: aportes por mes --}}
  <div class="tbl-card" style="padding:20px">
    <h3 style="font-size:.92rem;font-weight:700;margin-bottom:16px">
      <i class="fas fa-chart-bar" style="color:var(--verde-mid)"></i> Aportes últimos 6 meses
    </h3>
    @if($porMes->count() > 0)
      <div style="position:relative;height:200px">
        <canvas id="chart-meses"></canvas>
      </div>
    @else
      <div style="height:200px;display:flex;align-items:center;justify-content:center;color:var(--texto-suave);font-size:.85rem">
        Sin datos de meses
      </div>
    @endif
  </div>

</div>

{{-- ══ ALERTA PENDIENTES ══ --}}
@if($pendientes > 0)
<div class="alert-warn">
  <i class="fas fa-exclamation-triangle"></i>
  <strong>{{ $pendientes }} {{ $pendientes === 1 ? 'aporte pendiente' : 'aportes pendientes' }}</strong> de revisión.
</div>
@endif

{{-- ══ FILTROS ══ --}}
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
      @if($value === 'pendiente' && $pendientes > 0)
        <span style="background:#c62828;color:#fff;border-radius:20px;font-size:.7rem;padding:1px 7px;margin-left:4px">{{ $pendientes }}</span>
      @endif
    </a>
  @endforeach
</div>

{{-- ══ TABLA ══ --}}
<div class="tbl-card">
  <div class="tbl-hdr">
    <h3>{{ $estadoFiltro ? ($filtros[$estadoFiltro] ?? 'Aportes') : 'Todos los aportes' }}</h3>
  </div>

  <table>
    <thead>
      <tr>
        <th>Fotos</th>
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
          <td style="width:70px;padding:6px 10px">
            @php $imgs = $aporte->imagenes; @endphp
            @if($imgs->count() > 0)
              <div style="position:relative;display:inline-block">
                <img src="{{ asset('storage/' . $imgs->first()->img_path) }}"
                     alt="{{ $aporte->nombre_planta }}"
                     style="width:52px;height:52px;object-fit:cover;border-radius:8px;cursor:pointer"
                     onclick="abrirModal('modal-img-{{ $aporte->id }}')">
                @if($imgs->count() > 1)
                  <span style="position:absolute;bottom:2px;right:2px;background:rgba(0,0,0,.65);color:#fff;font-size:.6rem;border-radius:4px;padding:1px 4px">+{{ $imgs->count() - 1 }}</span>
                @endif
              </div>
            @elseif($aporte->img_path)
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

        {{-- Modal galería de imágenes del aporte --}}
        @if($aporte->imagenes->count() > 0 || $aporte->img_path)
          <div id="modal-img-{{ $aporte->id }}" class="modal-ov" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.82);z-index:9999;align-items:center;justify-content:center"
               onclick="if(event.target===this)cerrarModal('modal-img-{{ $aporte->id }}')">
            <div style="position:relative;max-width:90vw;max-height:90vh">
              @php $allImgs = $aporte->imagenes->count() > 0 ? $aporte->imagenes->pluck('img_path') : collect([$aporte->img_path]); @endphp
              @if($allImgs->count() === 1)
                <img src="{{ asset('storage/' . $allImgs->first()) }}"
                     alt="{{ $aporte->nombre_planta }}"
                     style="max-width:85vw;max-height:85vh;border-radius:14px;object-fit:contain;box-shadow:0 8px 40px rgba(0,0,0,.6)">
              @else
                <div style="display:flex;gap:10px;flex-wrap:wrap;justify-content:center;max-width:90vw">
                  @foreach($allImgs as $imgP)
                    <img src="{{ asset('storage/' . $imgP) }}"
                         style="width:200px;height:160px;object-fit:cover;border-radius:10px;box-shadow:0 4px 20px rgba(0,0,0,.5)">
                  @endforeach
                </div>
              @endif
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
    <div id="mdet-galeria" style="display:none;gap:6px;padding:0 0 0;flex-wrap:wrap"></div>
    <div class="modal-body">
      <div style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:20px;align-items:center">
        <span id="mdet-estado" class="pill"></span>
        <span id="mdet-categoria" style="background:var(--pale);color:var(--verde);padding:4px 12px;border-radius:20px;font-size:.78rem;font-weight:700"></span>
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
        <div id="mdet-motivo-wrap" style="grid-column:1/-1;display:none">
          <p class="mdet-label" style="color:#c62828">Motivo de rechazo</p>
          <p id="mdet-motivo" class="mdet-val" style="color:#c62828"></p>
        </div>
        <div>
          <p class="mdet-label">Enviado por</p>
          <p id="mdet-enviado" class="mdet-val"></p>
        </div>
        <div>
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
.mdet-label { font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--texto-suave);margin:0 0 3px; }
.mdet-val   { margin:0;font-size:.88rem;color:var(--texto); }
</style>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
// ── Gráfica Dona ──────────────────────────────────────────────────
@if($total > 0)
(function() {
  const ctx = document.getElementById('chart-dona');
  if (!ctx) return;
  new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: ['Pendientes', 'Aprobados', 'Rechazados'],
      datasets: [{
        data: [{{ $pendientes }}, {{ $aprobados }}, {{ $rechazados }}],
        backgroundColor: ['#f9a825','#388e3c','#c62828'],
        borderWidth: 2,
        borderColor: '#fff',
        hoverOffset: 6,
      }]
    },
    options: {
      cutout: '70%',
      plugins: { legend: { position: 'bottom', labels: { font: { size: 12 }, boxWidth: 12 } } },
      responsive: true,
      maintainAspectRatio: false,
    }
  });
})();
@endif

// ── Gráfica Barras por Mes ────────────────────────────────────────
@if($porMes->count() > 0)
(function() {
  const ctx = document.getElementById('chart-meses');
  if (!ctx) return;
  const labels = @json($porMes->pluck('mes')->map(fn($m) => \Carbon\Carbon::parse($m . '-01')->translatedFormat('M Y')));
  const data   = @json($porMes->pluck('total'));
  new Chart(ctx, {
    type: 'bar',
    data: {
      labels,
      datasets: [{
        label: 'Aportes',
        data,
        backgroundColor: '#388e3c99',
        borderColor: '#388e3c',
        borderWidth: 1.5,
        borderRadius: 5,
      }]
    },
    options: {
      plugins: { legend: { display: false } },
      scales: {
        y: { beginAtZero: true, ticks: { stepSize: 1, font: { size: 11 } } },
        x: { ticks: { font: { size: 11 } } }
      },
      responsive: true,
      maintainAspectRatio: false,
    }
  });
})();
@endif

// ── Modales ──────────────────────────────────────────────────────
function abrirModal(id) { const m=document.getElementById(id); if(m){ m.style.display='flex'; } }
function cerrarModal(id){ const m=document.getElementById(id); if(m){ m.style.display='none'; } }

const _modAportes = @json($aportes->items());
const _estadoClases = { pendiente:'p-pend', aprobado:'p-ok', rechazado:'p-rej' };
const _estadoLabels = { pendiente:'Pendiente', aprobado:'Aprobado', rechazado:'Rechazado' };

function abrirDetallesAporte(id) {
  const ap = _modAportes.find(a => a.id === id);
  if (!ap) return;

  document.getElementById('mdet-titulo').textContent     = ap.nombre_planta;
  document.getElementById('mdet-categoria').textContent  = ap.categoria;
  document.getElementById('mdet-cientifico').textContent = ap.cientifico || '';
  document.getElementById('mdet-uso').textContent        = ap.uso;
  document.getElementById('mdet-preparacion').textContent= ap.preparacion;
  document.getElementById('mdet-enviado').textContent    = ap.enviado_por || 'Anónimo';

  const fechaEl = document.getElementById('mdet-fecha');
  fechaEl.textContent = new Date(ap.creado_en).toLocaleString('es-CO', {
    day:'2-digit', month:'long', year:'numeric', hour:'2-digit', minute:'2-digit'
  });

  const estadoEl = document.getElementById('mdet-estado');
  estadoEl.className = 'pill ' + (_estadoClases[ap.estado] || '');
  estadoEl.textContent = _estadoLabels[ap.estado] || ap.estado;

  const relatoWrap = document.getElementById('mdet-relato-wrap');
  if (ap.relato) {
    document.getElementById('mdet-relato').textContent = ap.relato;
    relatoWrap.style.display = 'block';
  } else { relatoWrap.style.display = 'none'; }

  const motivoWrap = document.getElementById('mdet-motivo-wrap');
  if (ap.motivo_rechazo) {
    document.getElementById('mdet-motivo').textContent = ap.motivo_rechazo;
    motivoWrap.style.display = 'block';
  } else { motivoWrap.style.display = 'none'; }

  // Galería de imágenes
  const galeria = document.getElementById('mdet-galeria');
  const imagenes = ap.imagenes || [];
  const imgPath  = ap.img_path;
  const allImgs  = imagenes.length > 0 ? imagenes.map(i => i.img_path) : (imgPath ? [imgPath] : []);

  if (allImgs.length > 0) {
    galeria.innerHTML = allImgs.map(p =>
      `<img src="/storage/${p}" style="width:100%;max-height:180px;object-fit:cover">`
    ).join('');
    galeria.style.display = 'flex';
  } else {
    galeria.style.display = 'none';
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
