@extends('layouts.app')

@section('title', 'Dialogando Saberes — Etnobotánica Fusagasugá')

@section('breadcrumbs')
  <i class="fas fa-chevron-right sep"></i>
  <span style="color:rgba(255,255,255,.45)">Dialogando Saberes</span>
@endsection

@section('content')

<div class="ficha-header">
  <div class="container" style="padding-top:2.5rem;padding-bottom:2.5rem">
    <h2 style="font-size:2rem;margin-bottom:.5rem">
      <i class="fas fa-comments" style="font-size:1.5rem;opacity:.85"></i> Dialogando Saberes
    </h2>
    <p style="opacity:.75;font-size:1rem">Encuentros y diálogos de saberes con las comunidades de Fusagasugá</p>
  </div>
</div>

@php
  $dialogandoSaberes = [];
@endphp

<div class="container" style="padding-top:2rem;padding-bottom:3rem">
  <div class="ds-empty-state" style="display:flex;align-items:center;justify-content:center;flex-direction:column;gap:12px;min-height:220px;padding:32px 24px;border:1px dashed var(--border);border-radius:18px;background:var(--pale);text-align:center;color:var(--texto-suave);">
    <i class="fas fa-image" style="font-size:2.2rem;opacity:.7"></i>
    <p style="margin:0;font-size:1rem;font-weight:600;">Próximamente mostraremos las imágenes de Dialogando Saberes.</p>
  </div>
</div>

<style>
.ds-empty-state {
  border: 1px dashed var(--border);
  border-radius: 18px;
  background: var(--pale);
  color: var(--texto-suave);
}
</style>

@endsection
