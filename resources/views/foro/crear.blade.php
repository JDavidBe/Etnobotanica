@extends('layouts.app')

@section('title', 'Nuevo tema — Foro')

@section('content')
<div class="container">
  <div class="sec-inner foro-form-wrap">

    <a href="{{ route('foro.index') }}" class="foro-back-link">
  <span class="foro-back-icon"><i class="fas fa-arrow-left"></i></span> Volver al foro
</a>
    <p class="sec-titulo">Nuevo tema</p>
   <p class="sec-sub">Comparte tu duda o idea con la comunidad. Sé claro en el título para que otros puedan ayudarte más rápido.</p>

    @if($errors->any())
      <div class="alert-error">
        <i class="fas fa-circle-exclamation" style="margin-top:2px"></i>
        <ul style="margin:0;padding-left:18px">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <div class="foro-form-card">
      <form method="POST" action="{{ route('foro.store') }}" id="foroCrearForm">
        @csrf

        <div class="f-group">
          <label><i class="fas fa-tag" style="color:var(--verde-mid);margin-right:6px"></i>Categoría</label>
          <select name="categoria" required>
            @foreach($categorias as $key => $label)
              <option value="{{ $key }}" {{ old('categoria') === $key ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
          </select>
          <p class="foro-hint">Elige la categoría que mejor describa tu tema.</p>
        </div>

        <div class="f-group">
          <label><i class="fas fa-heading" style="color:var(--verde-mid);margin-right:6px"></i>Título</label>
          <input type="text" name="titulo" id="foroTitulo" value="{{ old('titulo') }}" maxlength="200" required
                 placeholder="Ej: ¿Cómo se usa el matico para heridas leves?">
          <p class="foro-hint"><span id="foroTituloCount">0</span>/200 caracteres</p>
        </div>

        <div class="f-group">
          <label><i class="fas fa-align-left" style="color:var(--verde-mid);margin-right:6px"></i>Contenido</label>
          <textarea name="contenido" id="foroContenido" rows="7" maxlength="5000" required
                    placeholder="Cuenta con detalle tu duda, experiencia o idea...">{{ old('contenido') }}</textarea>
          <p class="foro-hint"><span id="foroContenidoCount">0</span>/5000 caracteres</p>
        </div>

        <div class="foro-form-actions">
          <button type="submit" class="btn-submit">
            <i class="fas fa-paper-plane"></i> Publicar tema
          </button>
          <a href="{{ route('foro.index') }}" class="btn-cancel">Cancelar</a>
        </div>
      </form>
    </div>

  </div>
</div>

<style>
  .foro-back-link {
  display: inline-flex;
  align-items: center;
  gap: 9px;
  font-size: .82rem;
  font-weight: 700;
  color: var(--texto-mid);
  background: var(--pale);
  border: 1px solid var(--border-lt);
  padding: 7px 16px 7px 8px;
  border-radius: 20px;
  margin-bottom: 22px;
  text-decoration: none;
  transition: var(--trans, all .15s);
}
.foro-back-icon {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 22px;
  height: 22px;
  border-radius: 50%;
  background: var(--bg-card);
  color: var(--verde-mid);
  font-size: .72rem;
  transition: transform .2s;
}
.foro-back-link:hover {
  color: var(--verde-mid);
  border-color: var(--verde-mid);
  background: var(--bg-card);
}
.foro-back-link:hover .foro-back-icon { transform: translateX(-3px); }

.foro-form-wrap {
  max-width: 640px;
  margin: 0 auto;
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
}
.foro-form-wrap .foro-back-link { align-self: flex-start; }
.foro-form-wrap .foro-form-card,
.foro-form-wrap .alert-error { width: 100%; text-align: left; }

.foro-form-card {
  background: var(--bg-card);
  border: 1px solid var(--border-lt);
  border-radius: 16px;
  padding: 26px 28px;
  box-shadow: var(--sombra-lg, 0 4px 20px rgba(0,0,0,.15));
}

.foro-hint { font-size: .76rem; color: var(--texto-suave); margin: 6px 2px 0; }

.foro-form-actions { display: flex; gap: 12px; align-items: center; margin-top: 6px; }

@media (max-width: 700px) {
  .foro-form-card { padding: 20px; }
  .foro-form-actions { flex-direction: column-reverse; align-items: stretch; }
  .foro-form-actions .btn-submit,
  .foro-form-actions .btn-cancel { width: 100%; justify-content: center; }
}
</style>

<script>
(function () {
  function bindCounter(inputId, counterId) {
    const input = document.getElementById(inputId);
    const counter = document.getElementById(counterId);
    if (!input || !counter) return;
    const update = () => counter.textContent = input.value.length;
    input.addEventListener('input', update);
    update();
  }
  bindCounter('foroTitulo', 'foroTituloCount');
  bindCounter('foroContenido', 'foroContenidoCount');
})();
</script>
@endsection