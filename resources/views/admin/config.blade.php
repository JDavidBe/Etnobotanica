@extends('layouts.admin')

@section('title', 'Configuración')
@section('admin-title', 'Configuración')

@section('content')

@if(session('success'))
  <div id="flash-success" data-msg="{{ session('success') }}" style="display:none"></div>
@endif

<div class="cfg-grid">
  <div class="tbl-card" style="padding:22px">
    <h3 style="font-size:.95rem;font-weight:700;margin-bottom:16px;color:var(--verde)">
      Información del sitio
    </h3>
    <form method="POST" action="{{ route('admin.config') }}">
      @csrf
      <div class="f-group">
        <label>Nombre del proyecto</label>
        <input type="text" name="nombre_sitio" value="Etnobotánica Fusagasugá">
      </div>
      <div class="f-group">
        <label>Email de contacto</label>
        <input type="email" name="email_contacto" value="info@etnobotanica.co">
      </div>
      <button type="submit" class="btn-submit" style="margin-top:8px">
        <i class="fas fa-save"></i> Guardar
      </button>
    </form>
  </div>

  <div class="tbl-card" style="padding:22px">
    <h3 style="font-size:.95rem;font-weight:700;margin-bottom:16px;color:var(--verde)">
      Moderación 
    </h3>
    <form method="POST" action="{{ route('admin.config') }}">
      @csrf
      <div class="f-group">
        <label>Aportes requieren aprobación</label>
        <select name="aprobacion_aportes">
          <option value="1">Sí, siempre</option>
          <option value="0">No, automático</option>
        </select>
      </div>
      <div class="f-group">
        <label>Máx. aportes por IP/día</label>
        <input type="number" name="max_aportes_ip" value="5" min="1" max="50">
      </div>
      <button type="submit" class="btn-submit" style="margin-top:8px">
        <i class="fas fa-save"></i> Guardar
      </button>
    </form>
  </div>
</div>

@endsection
