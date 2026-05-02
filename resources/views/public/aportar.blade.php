@extends('layouts.app')

@section('title', 'Aportar conocimiento — Etnobotánica')


@section('content')

<div class="form-wrap">
  <h2>Comparte tu Saber</h2>
  <p class="sub">Tu conocimiento es valioso para la comunidad de Fusagasugá.</p>

  <div class="form-box">
    @if($errors->any())
      <div class="alert-error">
        <i class="fas fa-exclamation-circle"></i>
        <ul>
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('aportar.store') }}" enctype="multipart/form-data">
      @csrf

      <div class="f-row">
        <div class="f-group">
          <label>Nombre de la planta *</label>
          <input type="text" name="nombre_planta" value="{{ old('nombre_planta') }}"
                 placeholder="Ej: Canelón amarillo" required>
        </div>
        <div class="f-group">
          <label>Nombre científico</label>
          <input type="text" name="cientifico" value="{{ old('cientifico') }}"
                 placeholder="Ej: Nectandra sp.">
        </div>
      </div>

      <div class="f-row">
        <div class="f-group">
          <label>Categoría *</label>
          <select name="categoria" required>
            <option value="">Seleccionar…</option>
            @foreach($categorias as $cat)
              <option value="{{ $cat->nombre }}" {{ old('categoria') === $cat->nombre ? 'selected' : '' }}>
                {{ $cat->nombre }}
              </option>
            @endforeach
          </select>
        </div>
        <div class="f-group">
          <label>Uso principal *</label>
          <input type="text" name="uso" value="{{ old('uso') }}"
                 placeholder="Ej: Para bajar la fiebre" required>
        </div>
      </div>

      <div class="f-group">
        <label>¿Cómo se prepara o usa? *</label>
        <textarea name="preparacion" rows="3"
                  placeholder="Describe el proceso paso a paso…" required>{{ old('preparacion') }}</textarea>
      </div>

      <div class="f-group">
        <label>Relato o historia personal</label>
        <textarea name="relato" rows="3"
                  placeholder="¿Cómo aprendiste sobre esta planta?">{{ old('relato') }}</textarea>
      </div>

      <div class="f-group">
        <label>Foto de la planta <span style="font-weight:400;font-size:.8rem;opacity:.7">(opcional — JPG, PNG, WEBP, máx. 4 MB)</span></label>
        <div class="img-upload-wrap" id="img-upload-wrap-aporte">
          <label for="imagen-aporte" class="img-upload-label" id="img-upload-label-aporte">
            <i class="fas fa-camera"></i>
            <span>Haz clic o arrastra una foto aquí</span>
          </label>
          <input type="file" name="imagen" id="imagen-aporte"
                 accept="image/jpeg,image/png,image/webp"
                 style="display:none"
                 onchange="previewImagen(this, 'preview-aporte', 'img-upload-label-aporte')">
          <div id="preview-aporte" class="img-preview" style="display:none">
            <img id="preview-aporte-img" src="" alt="Vista previa">
            <button type="button" class="img-remove-btn" onclick="quitarImagen('imagen-aporte','preview-aporte','img-upload-label-aporte')">
              <i class="fas fa-times"></i>
            </button>
          </div>
        </div>
      </div>

      <div class="consent">
        <input type="checkbox" name="consentimiento" id="a-consent" required>
        <p>Acepto que esta información sea publicada en el catálogo comunitario bajo licencia abierta
          <strong>(RF-06 — Consentimiento informado)</strong>.</p>
      </div>

      <div class="f-actions">
        <a href="{{ route('home') }}" class="btn-cancel">Cancelar</a>
        <button type="submit" class="btn-submit">
          <i class="fas fa-paper-plane"></i> Enviar para revisión
        </button>
      </div>
    </form>
  </div>
</div>

{{-- ══════════════════════════════════════════════
     SECCIÓN: Aportes de la comunidad
══════════════════════════════════════════════ --}}
<div class="form-wrap" style="margin-top:0;padding-top:8px">

  <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:6px">
    <div>
      <h2 style="margin-bottom:4px">Aportes de la comunidad</h2>
      <p class="sub" style="margin-bottom:0">Conocimiento compartido y verificado por nuestros moderadores.</p>
    </div>
    <form method="GET" action="{{ route('aportar') }}" style="display:flex;align-items:center;gap:8px;flex-wrap:wrap">
      <label style="font-size:.82rem;color:var(--texto-suave);white-space:nowrap">
        <i class="fas fa-filter"></i> Categoría:
      </label>
      <select name="cat" onchange="this.form.submit()"
              style="padding:6px 12px;border:1px solid var(--border-lt);border-radius:8px;font-size:.84rem;background:var(--fondo-card);color:var(--texto);cursor:pointer">
        <option value="">Todas</option>
        @foreach($categorias as $cat)
          <option value="{{ $cat->nombre }}" {{ request('cat') === $cat->nombre ? 'selected' : '' }}>
            {{ $cat->nombre }}
          </option>
        @endforeach
      </select>
    </form>
  </div>

  @if($aportesAprobados->count())
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:18px;margin-top:20px">
      @foreach($aportesAprobados as $ap)
        <div style="background:var(--fondo-card);border:1px solid var(--border-lt);border-radius:14px;overflow:hidden;display:flex;flex-direction:column;transition:box-shadow .2s"
             onmouseover="this.style.boxShadow='0 4px 20px rgba(0,0,0,.1)'"
             onmouseout="this.style.boxShadow='none'">

          @if($ap->img_path)
            <div style="height:160px;overflow:hidden">
              <img src="{{ asset('storage/' . $ap->img_path) }}"
                   alt="{{ $ap->nombre_planta }}"
                   style="width:100%;height:100%;object-fit:cover">
            </div>
          @else
            <div style="height:90px;background:linear-gradient(135deg,var(--pale) 0%,#c8e6c9 100%);display:flex;align-items:center;justify-content:center">
              <i class="fas fa-seedling" style="font-size:2.2rem;color:var(--verde-mid);opacity:.45"></i>
            </div>
          @endif

          <div style="padding:16px 18px;flex:1;display:flex;flex-direction:column;gap:8px">
            <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:8px">
              <div>
                <strong style="font-size:.97rem;color:var(--texto);line-height:1.3">{{ $ap->nombre_planta }}</strong>
                @if($ap->cientifico)
                  <br><em style="font-size:.78rem;color:var(--texto-suave)">{{ $ap->cientifico }}</em>
                @endif
              </div>
              <span style="background:var(--pale);color:var(--verde);padding:3px 9px;border-radius:20px;font-size:.72rem;font-weight:700;white-space:nowrap;flex-shrink:0">
                {{ $ap->categoria }}
              </span>
            </div>

            <p style="font-size:.83rem;color:var(--texto-suave);margin:0">
              <i class="fas fa-circle-info" style="color:var(--verde-mid);margin-right:4px"></i>
              <strong>Uso:</strong> {{ $ap->uso }}
            </p>

            <p style="font-size:.83rem;color:var(--texto);margin:0;line-height:1.5;flex:1">
              {{ Str::limit($ap->preparacion, 110) }}
            </p>

            <div style="display:flex;align-items:center;justify-content:space-between;margin-top:6px;padding-top:10px;border-top:1px solid var(--border-lt)">
              <span style="font-size:.74rem;color:var(--texto-suave)">
                <i class="fas fa-calendar-alt" style="margin-right:3px"></i>
                {{ $ap->creado_en->format('d M Y') }}
              </span>
              <a href="{{ route('aportes.show', $ap->id) }}"
                 style="background:none;border:1px solid var(--verde-mid);color:var(--verde);padding:4px 12px;border-radius:20px;font-size:.78rem;text-decoration:none;display:inline-block">
                Ver más <i class="fas fa-arrow-right" style="font-size:.7rem"></i>
              </a>
            </div>
          </div>
        </div>
      @endforeach
    </div>

    @if($aportesAprobados->hasPages())
      <div style="margin-top:24px;text-align:center">
        {{ $aportesAprobados->appends(request()->query())->links() }}
      </div>
    @endif

  @else
    <div style="text-align:center;padding:48px 20px;color:var(--texto-suave);background:var(--fondo-card);border-radius:14px;border:1px dashed var(--border-lt);margin-top:20px">
      <i class="fas fa-seedling" style="font-size:2.5rem;opacity:.3;display:block;margin-bottom:12px"></i>
      <p style="margin:0;font-size:.9rem">
        @if(request('cat'))
          Aún no hay aportes aprobados en la categoría <strong>{{ request('cat') }}</strong>.
        @else
          Aún no hay aportes aprobados por la comunidad. ¡Sé el primero!
        @endif
      </p>
    </div>
  @endif
</div>

{{-- Modal detalle aporte (vista pública) --}}
<div id="modal-detalle-aporte" class="modal-ov" onclick="if(event.target===this)cerrarDetalleAporte()">
  <div class="modal" style="max-width:560px">
    <div class="modal-hdr">
      <h3><i class="fas fa-seedling"></i> <span id="det-titulo"></span></h3>
      <button class="modal-close" onclick="cerrarDetalleAporte()"><i class="fas fa-times"></i></button>
    </div>
    <div id="det-img-wrap" style="display:none">
      <img id="det-img" src="" alt="" style="width:100%;max-height:240px;object-fit:cover">
    </div>
    <div class="modal-body">
      <div style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:16px">
        <span id="det-categoria" style="background:var(--pale);color:var(--verde);padding:4px 12px;border-radius:20px;font-size:.78rem;font-weight:700"></span>
        <em id="det-cientifico" style="font-size:.82rem;color:var(--texto-suave);align-self:center"></em>
      </div>
      <div style="display:flex;flex-direction:column;gap:16px">
        <div>
          <p style="font-size:.74rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--texto-suave);margin:0 0 4px">Uso principal</p>
          <p id="det-uso" style="margin:0;font-size:.9rem;color:var(--texto)"></p>
        </div>
        <div>
          <p style="font-size:.74rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--texto-suave);margin:0 0 4px">Preparación / uso</p>
          <p id="det-preparacion" style="margin:0;font-size:.9rem;color:var(--texto);white-space:pre-line;line-height:1.6"></p>
        </div>
        <div id="det-relato-wrap" style="display:none">
          <p style="font-size:.74rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--texto-suave);margin:0 0 4px">Historia personal</p>
          <p id="det-relato" style="margin:0;font-size:.88rem;color:var(--texto-suave);font-style:italic;line-height:1.6"></p>
        </div>
      </div>
      <p id="det-fecha" style="font-size:.74rem;color:var(--texto-suave);margin:18px 0 0;text-align:right"></p>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
function previewImagen(input, previewId, labelId) {
  const file = input.files[0];
  if (!file) return;
  const reader = new FileReader();
  reader.onload = e => {
    document.getElementById(previewId + '-img').src = e.target.result;
    document.getElementById(previewId).style.display = 'block';
    document.getElementById(labelId).style.display = 'none';
  };
  reader.readAsDataURL(file);
}

function quitarImagen(inputId, previewId, labelId) {
  document.getElementById(inputId).value = '';
  document.getElementById(previewId).style.display = 'none';
  document.getElementById(labelId).style.display = 'flex';
}

const _aportes = @json($aportesAprobados->items());

function abrirDetalleAporte(id) {
  const ap = _aportes.find(a => a.id === id);
  if (!ap) return;

  document.getElementById('det-titulo').textContent      = ap.nombre_planta;
  document.getElementById('det-categoria').textContent   = ap.categoria;
  document.getElementById('det-cientifico').textContent  = ap.cientifico || '';
  document.getElementById('det-uso').textContent         = ap.uso;
  document.getElementById('det-preparacion').textContent = ap.preparacion;
  document.getElementById('det-fecha').textContent =
    'Compartido el ' + new Date(ap.creado_en).toLocaleDateString('es-CO', {day:'2-digit',month:'long',year:'numeric'});

  const relatoWrap = document.getElementById('det-relato-wrap');
  if (ap.relato) {
    document.getElementById('det-relato').textContent = ap.relato;
    relatoWrap.style.display = 'block';
  } else {
    relatoWrap.style.display = 'none';
  }

  const imgWrap = document.getElementById('det-img-wrap');
  if (ap.img_path) {
    document.getElementById('det-img').src = '/storage/' + ap.img_path;
    document.getElementById('det-img').alt = ap.nombre_planta;
    imgWrap.style.display = 'block';
  } else {
    imgWrap.style.display = 'none';
  }

  const modal = document.getElementById('modal-detalle-aporte');
  modal.style.display = 'flex';
  modal.classList.add('open');
}

function cerrarDetalleAporte() {
  const modal = document.getElementById('modal-detalle-aporte');
  modal.style.display = 'none';
  modal.classList.remove('open');
}
</script>
@endpush
