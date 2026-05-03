@extends('layouts.app')

@section('title', 'Editar aporte — ' . $aporte->nombre_planta)

@section('content')
<div class="form-wrap">
  <a href="{{ route('lector.dashboard') }}" class="back-link" style="display:inline-flex;align-items:center;gap:6px;margin-bottom:20px;color:var(--verde);text-decoration:none;font-size:.88rem">
    <i class="fas fa-arrow-left"></i> Volver a mis aportes
  </a>

  <h2>Editar aporte</h2>
  <p class="sub">Corrige tu aporte según el motivo de rechazo y reenvíalo para revisión.</p>

  {{-- Motivo de rechazo visible --}}
  @if($aporte->motivo_rechazo)
    <div style="background:#fff8f8;border:1px solid #ffcdd2;border-radius:12px;padding:16px 18px;margin-bottom:20px;display:flex;gap:12px;align-items:flex-start">
      <i class="fas fa-exclamation-triangle" style="color:#e53935;margin-top:2px;flex-shrink:0"></i>
      <div style="font-size:.88rem">
        <strong style="color:#b71c1c;display:block;margin-bottom:4px">Motivo del rechazo:</strong>
        <p style="margin:0;color:#555;line-height:1.6">{{ $aporte->motivo_rechazo }}</p>
      </div>
    </div>
  @endif

  <div class="form-box">
    @if($errors->any())
      <div class="alert-error">
        <i class="fas fa-exclamation-circle"></i>
        <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
      </div>
    @endif

    <form method="POST" action="{{ route('lector.aporte.update', $aporte->id) }}" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <div class="f-row">
        <div class="f-group">
          <label>Nombre de la planta *</label>
          <input type="text" name="nombre_planta" value="{{ old('nombre_planta', $aporte->nombre_planta) }}" required>
        </div>
        <div class="f-group">
          <label>Nombre científico</label>
          <input type="text" name="cientifico" value="{{ old('cientifico', $aporte->cientifico) }}" placeholder="Ej: Nectandra sp.">
        </div>
      </div>

      <div class="f-row">
        <div class="f-group">
          <label>Categoría *</label>
          <select name="categoria" required>
            <option value="">Seleccionar…</option>
            @foreach($categorias as $cat)
              <option value="{{ $cat->nombre }}" {{ old('categoria', $aporte->categoria) === $cat->nombre ? 'selected' : '' }}>
                {{ $cat->nombre }}
              </option>
            @endforeach
          </select>
        </div>
        <div class="f-group">
          <label>Uso principal *</label>
          <input type="text" name="uso" value="{{ old('uso', $aporte->uso) }}" required>
        </div>
      </div>

      <div class="f-group">
        <label>¿Cómo se prepara o usa? *</label>
        <textarea name="preparacion" rows="4" required>{{ old('preparacion', $aporte->preparacion) }}</textarea>
      </div>

      <div class="f-group">
        <label>Relato o historia personal</label>
        <textarea name="relato" rows="3">{{ old('relato', $aporte->relato) }}</textarea>
      </div>

      {{-- Imagen --}}
      <div class="f-group">
        <label>Foto de la planta <span style="font-weight:400;font-size:.8rem;opacity:.7">(JPG, PNG, WEBP, máx. 4 MB)</span></label>
        @if($aporte->img_path)
          <div style="margin-bottom:10px;display:flex;align-items:center;gap:12px">
            <img src="{{ asset('storage/' . $aporte->img_path) }}" alt=""
                 style="width:80px;height:80px;object-fit:cover;border-radius:8px;border:1px solid var(--border-lt)">
            <span style="font-size:.8rem;color:var(--texto-suave)">Imagen actual. Sube otra para reemplazarla.</span>
          </div>
        @endif
        <div class="img-upload-wrap" id="img-upload-wrap-edit">
          <label for="imagen-edit" class="img-upload-label" id="img-upload-label-edit">
            <i class="fas fa-camera"></i>
            <span>Haz clic o arrastra una nueva foto</span>
          </label>
          <input type="file" name="imagen" id="imagen-edit"
                 accept="image/jpeg,image/png,image/webp" style="display:none"
                 onchange="previewImagen(this,'preview-edit','img-upload-label-edit')">
          <div id="preview-edit" class="img-preview" style="display:none">
            <img id="preview-edit-img" src="" alt="Vista previa">
            <button type="button" class="img-remove-btn"
                    onclick="quitarImagen('imagen-edit','preview-edit','img-upload-label-edit')">
              <i class="fas fa-times"></i>
            </button>
          </div>
        </div>
      </div>

      <div class="f-actions">
        <a href="{{ route('lector.dashboard') }}" class="btn-cancel">Cancelar</a>
        <button type="submit" class="btn-submit">
          <i class="fas fa-paper-plane"></i> Reenviar para revisión
        </button>
      </div>
    </form>
  </div>
</div>

@endsection

@push('scripts')
<script>
function previewImagen(input, previewId, labelId) {
  const file = input.files[0]; if (!file) return;
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
