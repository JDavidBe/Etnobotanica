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
  <a href="https://www.ucundinamarca.edu.co"  style="display:flex;align-items:center;gap:28px;flex-wrap:wrap;background:var(--fondo-card,#fff);border:1px solid var(--border-lt,#e0e0e0);border-radius:16px;padding:28px 32px;margin-bottom:32px;box-shadow:0 2px 12px rgba(0,0,0,.06);cursor:pointer;transition:all 0.3s ease;text-decoration:none" onmouseover="this.style.boxShadow='0 4px 20px rgba(0,0,0,.12)';this.style.borderColor='var(--verde,#2e7d32)'" onmouseout="this.style.boxShadow='0 2px 12px rgba(0,0,0,.06)';this.style.borderColor='var(--border-lt,#e0e0e0)'">
    <div style="flex-shrink:0;width:100px;height:100px;background:linear-gradient(135deg,#1b5e20,#4caf50);border-radius:16px;display:flex;align-items:center;justify-content:center;transition:transform 0.3s ease" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
      {{-- Logo Universidad de Cundinamarca --}}
      <img src="https://www.ucundinamarca.edu.co/images/ucundinamarca/escudo-color.png" alt="Logo Universidad de Cundinamarca" style="width:72px;height:72px;object-fit:contain;border-radius:8px">
    </div>
    <div style="flex:1;min-width:220px">
      <h2 style="font-size:1.25rem;font-weight:800;color:var(--verde,#2e7d32);margin:0 0 4px">Universidad de Cundinamarca</h2>
      <p style="font-size:.88rem;color:var(--texto-suave);margin:0 0 8px">Facultad de Ingeniería — Ingeniería de Sistemas</p>
      <p style="font-size:.83rem;color:var(--texto-mid,#555);margin:0;line-height:1.6">
        Proyecto académico de catalogación del conocimiento etnobotánico tradicional
        de la región de Fusagasugá, desarrollado como parte del programa de
        Ingeniería de Sistemas de la UdeCundinamarca.
      </p>
    </div>
  </a>

  {{-- Equipo de desarrollo --}}
  <h3 style="font-size:1rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--texto-suave);margin:0 0 16px">
    <i class="fas fa-laptop-code" style="color:var(--verde-mid)"></i> Desarrollo &amp; Diseño
  </h3>
  <div class="cred-grid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:14px;margin-bottom:32px">
    @php
    $equipo = [
      ['nombre'=>'Juan David Bernal Beltrán', 'rol'=>'Desarrollo', 'correo'=>'juandavidbernal@ucundinamarca.edu.co','icono'=>'fas fa-code', 'color'=>'#2e7d32'],
      ['nombre'=>'Johan Steven Álvarez Rodríguez', 'rol'=>'Desarrollo', 'correo'=>'jstevenalvarez@ucundinamarca.edu.co','icono'=>'fas fa-code', 'color'=>'#00897b'],
      ['nombre'=>'Johan Estiven Leal Mora', 'rol'=>'Desarrollo Backend', 'correo'=>'jeleal@udecundinamarca.edu.co','icono'=>'fas fa-server', 'color'=>'#1565c0'],
      ['nombre'=>'Jhordy Santiago Miranda García', 'rol'=>'Arquitectura & Base de datos', 'correo'=>'jsmiranda@ucundinamarca.edu.co', 'icono'=>'fas fa-database', 'color'=>'#6a1b9a']
    ]
    @endphp
    @foreach($equipo as $m)
      <div style="display:flex;flex-direction:column;align-items:flex-start;gap:12px;background:var(--fondo-card,#fff);border:1px solid var(--border-lt,#e0e0e0);border-radius:12px;padding:20px;box-shadow:0 1px 6px rgba(0,0,0,.05);transition:all 0.3s ease" onmouseover="this.style.boxShadow='0 4px 16px rgba(0,0,0,.12)';this.style.borderColor='{{ $m['color'] }}'" onmouseout="this.style.boxShadow='0 1px 6px rgba(0,0,0,.05)';this.style.borderColor='var(--border-lt,#e0e0e0)'">
        <div style="display:flex;align-items:center;gap:12px;width:100%">
          <div style="width:48px;height:48px;border-radius:12px;background:{{ $m['color'] }}1a;flex-shrink:0;display:flex;align-items:center;justify-content:center">
            <i class="{{ $m['icono'] }}" style="color:{{ $m['color'] }};font-size:1.2rem"></i>
          </div>
          <div style="flex:1;min-width:0">
            <div style="font-weight:700;font-size:.95rem;color:var(--texto);word-wrap:break-word">{{ $m['nombre'] }}</div>
            <div style="font-size:.78rem;color:var(--texto-suave);margin-top:2px">{{ $m['rol'] }}</div>
          </div>
        </div>
        <a href="mailto:{{ $m['correo'] }}" style="font-size:.75rem;color:{{ $m['color'] }};text-decoration:none;font-weight:600;transition:opacity 0.2s ease" onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'" title="Enviar correo"><i class="fas fa-envelope" style="margin-right:5px"></i>{{ $m['correo'] }}</a>
      </div>
    @endforeach
  </div>

  {{-- Investigación --}}
  <h3 style="font-size:1rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--texto-suave);margin:0 0 16px">
    <i class="fas fa-microscope" style="color:var(--verde-mid)"></i> Investigación etnobotánica
  </h3>
  <div class="cred-grid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:14px;margin-bottom:32px">
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

  {{-- Investigadores y Directores --}}
  <h3 style="font-size:1rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--texto-suave);margin:0 0 16px">
    <i class="fas fa-graduation-cap" style="color:var(--verde-mid)"></i> Investigadores &amp; Directores
  </h3>
  <div class="cred-grid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:14px;margin-bottom:32px">
    @php
    $directores = [
      ['nombre'=>'Dra. Ana Esperanza Merchán Hernández ', 'rol'=>'Director del disciplinar', 'correo'=>'anaesperanzamerchan@ucundinamarca.edu.co','icono'=>'fas fa-user-tie', 'color'=>'#d32f2f'],
      ['nombre'=>'Dr. Daniel Alejandro Moncada Beltran', 'rol'=>'Director Metodológico', 'correo'=>'dalejandromoncada@ucundinamarca.edu.co', 'icono'=>'fas fa-flask', 'color'=>'#f57c00'],
      ['nombre'=>'Dra. Natalia Escobar Escobar', 'rol'=>'Director de investigación', 'correo'=>'nataliaescobar@ucundinamarca.edu.co', 'icono'=>'fas fa-search', 'color'=>'#1b5e20'],
      ['nombre'=>'Dr. Gustavo Andrés Rodríguez Méndez ', 'rol'=>'Director de investigación', 'correo'=>'gustavoarodriguez@ucundinamarca.edu.co', 'icono'=>'fas fa-briefcase', 'color'=>'#5d4037']
    ];
    @endphp
    @foreach($directores as $m)
      <div style="display:flex;align-items:center;gap:14px;background:var(--fondo-card,#fff);border:1px solid var(--border-lt,#e0e0e0);border-radius:12px;padding:16px 18px;box-shadow:0 1px 6px rgba(0,0,0,.05)">
        <div style="width:44px;height:44px;border-radius:12px;background:{{ $m['color'] }}1a;flex-shrink:0;display:flex;align-items:center;justify-content:center">
          <i class="{{ $m['icono'] }}" style="color:{{ $m['color'] }};font-size:1.1rem"></i>
        </div>
        <div>
          <div style="font-weight:700;font-size:.93rem;color:var(--texto)">{{ $m['nombre'] }}</div>
          <div style="font-size:.78rem;color:var(--texto-suave)">{{ $m['rol'] }}</div>
          <div style="font-size:.75rem;color:var(--texto-suave);margin-top:4px"><a href="mailto:{{ $m['correo'] }}" style="color:var(--verde-mid,#4caf50);text-decoration:none;font-weight:600;transition:text-decoration 0.2s ease" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'" title="Enviar correo">{{ $m['correo'] }}</a></div>
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
      'Laravel 13','PHP 8.3','PostgreSQL','Spatie Permissions',
      'Blade','Tailwind CSS 4','Alpine.js','Chart.js','HTMX',
      'Docker','Nginx','Cloudinary','Render',
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
