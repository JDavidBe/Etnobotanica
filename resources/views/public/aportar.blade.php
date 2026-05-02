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

      {{-- Imagen opcional --}}
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
</script>
@endpush
