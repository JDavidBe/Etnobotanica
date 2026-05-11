@extends('layouts.app')

@section('title', 'Créditos — Etnobotánica Fusagasugá')

@section('breadcrumbs')
  <i class="fas fa-chevron-right sep"></i>
  <span style="color:rgba(255,255,255,.45)">Créditos</span>
@endsection

@section('content')

<div class="ficha-header">
  <div class="container" style="padding-top:2.5rem;padding-bottom:2.5rem">
    <h2 style="font-size:2rem;margin-bottom:.5rem">
      <i class="fas fa-award" style="font-size:1.5rem;opacity:.85"></i> Créditos del proyecto
    </h2>
    <p style="opacity:.75;font-size:1rem">Catálogo etnobotánico colaborativo de Fusagasugá</p>
  </div>
</div>

<div class="container" style="max-width:860px;padding:2.5rem 1rem 4rem">

  {{-- Logo + Descripción institución --}}
  <div style="display:flex;align-items:center;gap:28px;flex-wrap:wrap;background:var(--fondo-card,#fff);border:1px solid var(--border-lt,#e0e0e0);border-radius:16px;padding:28px 32px;margin-bottom:32px;box-shadow:0 2px 12px rgba(0,0,0,.06)">
    <div style="flex-shrink:0;width:100px;height:100px;background:linear-gradient(135deg,#1b5e20,#4caf50);border-radius:16px;display:flex;align-items:center;justify-content:center">
      {{-- Placeholder logo UdeCundinamarca --}}
      <svg viewBox="0 0 80 80" width="72" height="72" xmlns="http://www.w3.org/2000/svg">
        <circle cx="40" cy="40" r="36" fill="none" stroke="rgba(255,255,255,.35)" stroke-width="2"/>
        <text x="40" y="28" text-anchor="middle" font-family="serif" font-size="10" font-weight="700" fill="#fff" letter-spacing="1">UDEC</text>
        <path d="M18 36 Q40 18 62 36" stroke="rgba(255,255,255,.6)" stroke-width="2" fill="none"/>
        <path d="M22 50 Q40 34 58 50" stroke="rgba(255,255,255,.6)" stroke-width="2" fill="none"/>
        <text x="40" y="62" text-anchor="middle" font-family="sans-serif" font-size="7" fill="rgba(255,255,255,.8)">Cundinamarca</text>
      </svg>
    </div>
    <div style="flex:1;min-width:220px">
      <h2 style="font-size:1.25rem;font-weight:800;color:var(--verde,#2e7d32);margin:0 0 4px">Universidad de Cundinamarca</h2>
      <p style="font-size:.88rem;color:var(--texto-suave);margin:0 0 8px">Facultad de Ingeniería — Ingeniería de Sistemas</p>
      <p style="font-size:.83rem;color:var(--texto-mid,#555);margin:0;line-height:1.6">
        Proyecto académico de catalogación del conocimiento etnobotánico tradicional
        de la región de Fusagasugá, desarrollado como parte del programa de
        Ingeniería de Sistemas de la UdeCundinamarca — CAI CTI 3.
      </p>
    </div>
  </div>

  {{-- Equipo de desarrollo --}}
  <h3 style="font-size:1rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--texto-suave);margin:0 0 16px">
    <i class="fas fa-laptop-code" style="color:var(--verde-mid)"></i> Desarrollo &amp; Diseño
  </h3>
  <div class="cred-grid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:14px;margin-bottom:32px">
    @php
    $equipo = [
      ['nombre'=>'Johan Estiven Leal Mora', 'rol'=>'Desarrollo Backend', 'icono'=>'fas fa-server', 'color'=>'#1565c0'],
      ['nombre'=>'Jhordy Santiago Miranda García', 'rol'=>'Arquitectura & Base de datos', 'icono'=>'fas fa-database', 'color'=>'#6a1b9a']
    ]
    @endphp
    @foreach($equipo as $m)
      <div style="display:flex;align-items:center;gap:14px;background:var(--fondo-card,#fff);border:1px solid var(--border-lt,#e0e0e0);border-radius:12px;padding:16px 18px;box-shadow:0 1px 6px rgba(0,0,0,.05)">
        <div style="width:44px;height:44px;border-radius:12px;background:{{ $m['color'] }}1a;flex-shrink:0;display:flex;align-items:center;justify-content:center">
          <i class="{{ $m['icono'] }}" style="color:{{ $m['color'] }};font-size:1.1rem"></i>
        </div>
        <div>
          <div style="font-weight:700;font-size:.93rem;color:var(--texto)">{{ $m['nombre'] }}</div>
          <div style="font-size:.78rem;color:var(--texto-suave)">{{ $m['rol'] }}</div>
        </div>
      </div>
    @endforeach
  </div>

  {{-- Investigación --}}
  <h3 style="font-size:1rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--texto-suave);margin:0 0 16px">
    <i class="fas fa-microscope" style="color:var(--verde-mid)"></i> Investigación etnobotánica
  </h3>
  <div class="cred-grid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:14px;margin-bottom:32px">
    @php
    $investigacion = [
      ['nombre'=>'Sabedores locales de Fusagasugá', 'rol'=>'Fuente primaria de conocimiento', 'icono'=>'fas fa-leaf', 'color'=>'#2e7d32'],
      ['nombre'=>'Comunidad participante',           'rol'=>'Aportes y validación comunitaria', 'icono'=>'fas fa-users', 'color'=>'#e65100'],
    ];
    @endphp
    @foreach($investigacion as $m)
      <div style="display:flex;align-items:center;gap:14px;background:var(--fondo-card,#fff);border:1px solid var(--border-lt,#e0e0e0);border-radius:12px;padding:16px 18px;box-shadow:0 1px 6px rgba(0,0,0,.05)">
        <div style="width:44px;height:44px;border-radius:12px;background:{{ $m['color'] }}1a;flex-shrink:0;display:flex;align-items:center;justify-content:center">
          <i class="{{ $m['icono'] }}" style="color:{{ $m['color'] }};font-size:1.1rem"></i>
        </div>
        <div>
          <div style="font-weight:700;font-size:.93rem;color:var(--texto)">{{ $m['nombre'] }}</div>
          <div style="font-size:.78rem;color:var(--texto-suave)">{{ $m['rol'] }}</div>
        </div>
      </div>
    @endforeach
  </div>

  {{-- Tecnologías --}}
  <h3 style="font-size:1rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--texto-suave);margin:0 0 16px">
    <i class="fas fa-cogs" style="color:var(--verde-mid)"></i> Tecnologías utilizadas
  </h3>
  <div style="display:flex;flex-wrap:wrap;gap:10px;margin-bottom:36px">
    @php
    $techs = [
      'Laravel 12','PHP 8.3','PostgreSQL','Spatie Permissions',
      'Blade','Tailwind-like CSS','Alpine.js','Chart.js','HTMX',
    ];
    @endphp
    @foreach($techs as $tech)
      <span style="background:var(--pale,#e8f5e9);color:var(--verde,#2e7d32);border:1px solid #c8e6c9;border-radius:20px;padding:5px 14px;font-size:.82rem;font-weight:600">
        {{ $tech }}
      </span>
    @endforeach
  </div>

  {{-- Nota legal --}}
  <div style="background:var(--fondo-card,#fff);border:1px solid var(--border-lt,#e0e0e0);border-left:4px solid var(--verde-mid,#4caf50);border-radius:10px;padding:18px 22px;font-size:.83rem;color:var(--texto-suave);line-height:1.7">
    <strong style="display:block;margin-bottom:4px;color:var(--texto)"><i class="fas fa-scale-balanced"></i> Aviso legal</strong>
    El contenido de este catálogo proviene de saberes tradicionales compartidos voluntariamente por la comunidad de Fusagasugá.
    Los autores de cada aporte retienen la autoría moral de su conocimiento.
    Las imágenes y videos se publican bajo consentimiento informado de sus autores .
    El proyecto no tiene fines comerciales.
  </div>

  <div style="text-align:center;margin-top:32px">
    <a href="{{ route('home') }}" class="btn-back" style="display:inline-flex">
      <i class="fas fa-arrow-left"></i> Volver al catálogo
    </a>
  </div>
</div>

@endsection
