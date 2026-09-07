@extends('layouts.app')

@section('title', 'Nuevo tema — Foro')

@section('content')
<div class="container">
  <div class="sec-inner foro-form-wrap">

    <a href="{{ route('foro.index') }}" class="back-link">
      <i class="fas fa-arrow-left"></i> Volver al foro
    </a>
    <p class="sec-titulo">Nuevo tema</p>

    @if($errors->any())
      <div class="cf-error" style="display:block;margin-bottom:16px">
        <ul style="margin:0;padding-left:18px">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('foro.store') }}" class="com-form-wrap foro-form">
      @csrf

      <div>
        <label class="foro-label">Categoría</label>
        <select name="categoria" required class="cf-textarea foro-select">
          @foreach($categorias as $key => $label)
            <option value="{{ $key }}" {{ old('categoria') === $key ? 'selected' : '' }}>{{ $label }}</option>
          @endforeach
        </select>
      </div>

      <div>
        <label class="foro-label">Título</label>
        <input type="text" name="titulo" value="{{ old('titulo') }}" maxlength="200" required
               class="cf-textarea" style="resize:none">
      </div>

      <div>
        <label class="foro-label">Contenido</label>
        <textarea name="contenido" rows="6" maxlength="5000" required class="cf-textarea">{{ old('contenido') }}</textarea>
      </div>

      <button type="submit" class="btn-submit foro-submit">
        <i class="fas fa-paper-plane"></i> Publicar tema
      </button>
    </form>

  </div>
</div>

<style>
.foro-form-wrap { max-width:640px; }
.foro-form { display:flex; flex-direction:column; gap:16px; }
.foro-label { display:block; font-size:.85rem; color:var(--texto); margin-bottom:6px; font-weight:600; }
.foro-select { width:100%; cursor:pointer; }
.foro-submit { align-self:flex-start; padding:10px 26px; }

@media (max-width: 700px) {
  .foro-submit { width:100%; justify-content:center; }
}
</style>
@endsection
