@extends('layouts.admin')

@section('title', 'Editar Planta')
@section('admin-title', 'Editar Planta')

@section('content')

<div class="tbl-card" style="max-width:780px">
  <div class="tbl-hdr">
    <h3><i class="fas fa-edit"></i> {{ $planta->nombre }}</h3>
    <a href="{{ route('admin.plantas.index') }}" class="btn-site">
      <i class="fas fa-arrow-left"></i> Volver
    </a>
  </div>

  <div style="padding:24px">
    @if($errors->any())
      <div class="alert-warn" style="background:#fce4ec;border-color:#f48fb1;color:#b71c1c;margin-bottom:20px">
        <i class="fas fa-exclamation-circle"></i>
        <ul style="list-style:none">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('admin.plantas.update', $planta) }}" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <div class="f-row">
        <div class="f-group">
          <label>Nombre común *</label>
          <input type="text" name="nombre" value="{{ old('nombre', $planta->nombre) }}" required>
        </div>
        <div class="f-group">
          <label>Nombre científico</label>
          <input type="text" name="cientifico" value="{{ old('cientifico', $planta->cientifico) }}">
        </div>
      </div>

      {{-- Categoría principal + adicionales --}}
      <div class="f-row">
        <div class="f-group">
          <label>Categoría principal *</label>
          <select name="categoria_id" id="sel-categoria" required onchange="cargarSubtemas(this.value)">
            <option value="">Seleccionar…</option>
            @foreach($categorias as $cat)
              <option value="{{ $cat->id }}"
                {{ old('categoria_id', $planta->categoria_id) == $cat->id ? 'selected' : '' }}>
                {{ $cat->nombre }}
              </option>
            @endforeach
          </select>
        </div>
        <div class="f-group">
          <label>Subtema *</label>
          <select name="subtema_id" id="sel-subtema" required>
            @foreach($categorias->firstWhere('id', $planta->categoria_id)?->subtemas ?? [] as $sub)
              <option value="{{ $sub->id }}"
                {{ old('subtema_id', $planta->subtema_id) == $sub->id ? 'selected' : '' }}>
                {{ $sub->nombre }}
              </option>
            @endforeach
          </select>
        </div>
      </div>

      @php $catIds = old('categorias_extra', $planta->categorias->pluck('id')->toArray()); @endphp
      <div class="f-group">
        <label>Categorías adicionales <span style="font-weight:400;font-size:.8rem;color:var(--texto-suave)">(aparece en múltiples categorías)</span></label>
        <div style="display:flex;flex-wrap:wrap;gap:10px;padding:12px;border:1px solid var(--border-lt);border-radius:9px;background:var(--fondo-card)">
          @foreach($categorias as $cat)
            <label style="display:flex;align-items:center;gap:6px;cursor:pointer;font-size:.85rem;font-weight:400">
              <input type="checkbox" name="categorias_extra[]" value="{{ $cat->id }}"
                     {{ in_array($cat->id, $catIds) ? 'checked' : '' }}
                     style="width:14px;height:14px;accent-color:var(--verde-mid)">
              {{ $cat->nombre }}
            </label>
          @endforeach
        </div>
        <small style="color:var(--texto-suave);font-size:.78rem">La categoría principal se incluye automáticamente.</small>
      </div>

      <div class="f-group">
        <label>Uso principal *</label>
        <input type="text" name="uso" value="{{ old('uso', $planta->uso) }}" required>
      </div>
      <div class="f-group">
        <label>Instrucciones de preparación *</label>
        <textarea name="instrucciones" rows="3" required>{{ old('instrucciones', $planta->instrucciones) }}</textarea>
      </div>
      <div class="f-group">
        <label>Información general</label>
        <textarea name="contexto" rows="2">{{ old('contexto', $planta->contexto) }}</textarea>
      </div>
      <div class="f-group">
        <label>Relato del sabedor local</label>
        <textarea name="relato" rows="2">{{ old('relato', $planta->relato) }}</textarea>
      </div>

      {{-- Imagen --}}
      <div class="f-group">
        <label>Imagen de la planta <span style="font-weight:400;font-size:.8rem;color:var(--texto-suave)">(JPG, PNG, WEBP — máx. 4 MB)</span></label>
        @if($planta->imagenUrl)
          <div class="img-actual-wrap" id="img-actual">
            <img src="{{ $planta->imagenUrl }}" alt="{{ $planta->nombre }}"
                 style="max-height:160px;border-radius:10px;object-fit:cover">
            <label style="display:flex;align-items:center;gap:6px;margin-top:8px;cursor:pointer;font-size:.85rem;color:var(--rojo,#e53935)">
              <input type="checkbox" name="borrar_imagen" value="1" id="chk-borrar"
                     onchange="toggleNuevaImagen(this.checked)"
                     style="width:14px;height:14px;accent-color:#e53935">
              Eliminar imagen actual
            </label>
          </div>
          <div id="nueva-imagen-wrap" style="display:none;margin-top:10px">
        @endif
        <div class="img-upload-wrap" id="img-upload-wrap-edit">
          <label for="imagen-edit" class="img-upload-label" id="img-upload-label-edit">
            <i class="fas fa-cloud-upload-alt"></i>
            <span>{{ $planta->imagenUrl ? 'Sube una imagen de reemplazo' : 'Haz clic o arrastra una imagen aquí' }}</span>
          </label>
          <input type="file" name="imagen" id="imagen-edit"
                 accept="image/jpeg,image/png,image/webp"
                 style="display:none"
                 onchange="previewImagen(this, 'preview-edit', 'img-upload-label-edit')">
          <div id="preview-edit" class="img-preview" style="display:none">
            <img id="preview-edit-img" src="" alt="Vista previa">
            <button type="button" class="img-remove-btn" onclick="quitarImagen('imagen-edit','preview-edit','img-upload-label-edit')">
              <i class="fas fa-times"></i>
            </button>
          </div>
        </div>
        @if($planta->imagenUrl)
          </div>
        @endif
      </div>

      {{-- Video --}}
      <div class="f-row">
        <div class="f-group">
          <label>URL video YouTube</label>
          <input type="text" name="video_url" value="{{ old('video_url', $planta->video_url) }}"
                 placeholder="https://youtube.com/watch?v=…">
          <small style="color:var(--texto-suave);font-size:.8rem;margin-top:4px;display:block">O sube un video local abajo</small>
        </div>
        <div class="f-group">
          <label>Tags (separados por coma)</label>
          <input type="text" name="tags" value="{{ old('tags', $planta->tags) }}">
        </div>
      </div>

      <div class="f-group">
        <label>Video local (opcional)</label>
        @if($planta->video_url && !str_starts_with($planta->video_url, 'http'))
          <div style="margin-bottom:12px;padding:12px;border:1px solid var(--border);border-radius:6px;background:var(--bg-input)">
            <strong>Video actual:</strong> {{ $planta->video_url }}
            <label style="display:inline-flex;align-items:center;gap:6px;margin-left:16px;font-size:.9rem">
              <input type="checkbox" name="borrar_video" value="1"> Eliminar video actual
            </label>
          </div>
        @endif
        <input type="file" name="video_file" accept="video/*" style="margin-bottom:8px">
        <small style="color:var(--texto-suave);font-size:.8rem">
          Formatos: MP4, WebM, OGV. Máximo 50MB. <strong>Tiene prioridad sobre la URL arriba.</strong>
        </small>
      </div>

      {{-- Créditos del video --}}
      <div style="border:1px solid var(--border-lt);border-radius:10px;padding:16px;background:var(--fondo-card);margin-bottom:16px">
        <p style="font-size:.82rem;font-weight:700;color:var(--texto-suave);text-transform:uppercase;letter-spacing:.05em;margin:0 0 12px">
          <i class="fas fa-id-badge"></i> Créditos del video (overlay noticiero)
        </p>
        <div class="f-row" style="margin-bottom:0">
          <div class="f-group">
            <label>Nombre de la persona entrevistada</label>
            <input type="text" name="video_persona_nombre"
                   value="{{ old('video_persona_nombre', $planta->video_persona_nombre) }}"
                   placeholder="Ej: María Esperanza Rodríguez">
          </div>
          <div class="f-group">
            <label>Rol / ocupación</label>
            <input type="text" name="video_persona_rol"
                   value="{{ old('video_persona_rol', $planta->video_persona_rol) }}"
                   placeholder="Ej: Sabedora local, Curandera">
          </div>
        </div>
        <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:.85rem;margin-top:8px">
          <input type="checkbox" name="video_validado" value="1"
                 {{ old('video_validado', $planta->video_validado) ? 'checked' : '' }}
                 style="width:14px;height:14px;accent-color:var(--verde-mid)">
          El video ha sido validado y autorizado por la persona entrevistada
        </label>
        @if(!$planta->video_validado && $planta->video_url)
          <p style="font-size:.78rem;color:#f57c00;margin:8px 0 0">
            <i class="fas fa-exclamation-triangle"></i> Este video aún no ha sido validado con el entrevistado.
          </p>
        @endif
      </div>

      <div class="f-group">
        <label style="display:flex;align-items:center;gap:8px;cursor:pointer">
          <input type="checkbox" name="verificada" value="1"
                 {{ old('verificada', $planta->verificada) ? 'checked' : '' }}
                 style="width:16px;height:16px;accent-color:var(--verde-mid)">
          Marcar como verificada por experto
        </label>
      </div>

      <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:8px">
        <a href="{{ route('admin.plantas.index') }}" class="btn-cancel">Cancelar</a>
        <button type="submit" class="btn-submit">
          <i class="fas fa-save"></i> Actualizar planta
        </button>
      </div>
    </form>
  </div>
</div>

@endsection

@push('scripts')
<script>
function cargarSubtemas(categoriaId) {
  const sel = document.getElementById('sel-subtema');
  sel.innerHTML = '<option value="">Cargando…</option>';
  if (!categoriaId) { sel.innerHTML = '<option value="">Selecciona primero una categoría</option>'; return; }
  fetch(`/admin/categorias/${categoriaId}/subtemas`)
    .then(r => r.json())
    .then(data => {
      sel.innerHTML = '<option value="">Seleccionar…</option>' +
        data.map(s => `<option value="${s.id}">${s.nombre}</option>`).join('');
    });
}
function toggleNuevaImagen(borrar) {
  const wrap = document.getElementById('nueva-imagen-wrap');
  if (wrap) wrap.style.display = borrar ? 'block' : 'none';
  if (!borrar) quitarImagen('imagen-edit','preview-edit','img-upload-label-edit');
}
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
