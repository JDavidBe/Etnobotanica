@extends('layouts.app')

@section('title', 'Mis aportes — Etnobotánica')

@section('content')
<div class="form-wrap" style="max-width:860px">
  <h2><i class="fas fa-seedling" style="color:var(--verde)"></i> Mis aportes</h2>
  <p class="sub">Aquí puedes ver el estado de tus aportes y editarlos si fueron rechazados.</p>

  @if(session('success'))
    <div style="background:#e8f5e9;border:1px solid #a5d6a7;border-radius:10px;padding:12px 16px;margin-bottom:16px;font-size:.88rem;color:#1b5e20;display:flex;align-items:center;gap:10px">
      <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
  @endif

  @if($aportes->isEmpty())
    <div style="text-align:center;padding:56px 20px;background:var(--bg-card);border-radius:16px;border:1px dashed var(--border-lt)">
      <i class="fas fa-seedling" style="font-size:2.5rem;opacity:.3;display:block;margin-bottom:14px;color:var(--verde)"></i>
      <p style="color:var(--texto-suave);font-size:.9rem;margin:0 0 16px">Aún no has compartido ningún aporte.</p>
      <a href="{{ route('aportar') }}" class="btn-submit" style="display:inline-flex;gap:8px;padding:10px 22px;font-size:.9rem;text-decoration:none">
        <i class="fas fa-plus"></i> Hacer mi primer aporte
      </a>
    </div>
  @else
    <div style="display:flex;flex-direction:column;gap:16px">
      @foreach($aportes as $ap)
        <div class="mi-aporte-card">
          {{-- Imagen(es) --}}
          <div class="mac-img">
            @php $imgs = $ap->imagenes; @endphp
            @if($imgs->count() > 0)
              <div style="position:relative;width:100%;height:100%">
                <img src="{{ asset('storage/' . $imgs->first()->img_path) }}" alt="{{ $ap->nombre_planta }}" style="width:100%;height:100%;object-fit:cover">
                @if($imgs->count() > 1)
                  <span style="position:absolute;bottom:6px;right:6px;background:rgba(0,0,0,.6);color:#fff;font-size:.65rem;padding:2px 7px;border-radius:10px">
                    +{{ $imgs->count() - 1 }}
                  </span>
                @endif
              </div>
            @elseif($ap->img_path)
              <img src="{{ asset('storage/' . $ap->img_path) }}" alt="{{ $ap->nombre_planta }}">
            @else
              <div class="mac-noimg"><i class="fas fa-seedling"></i></div>
            @endif
          </div>

          {{-- Info --}}
          <div class="mac-body">
            <div class="mac-top">
              <div>
                <strong class="mac-nombre">{{ $ap->nombre_planta }}</strong>
                @if($ap->cientifico)<em class="mac-cient">{{ $ap->cientifico }}</em>@endif
              </div>
              {{-- Estado badge --}}
              @if($ap->estado === 'aprobado')
                <span class="estado-badge est-ok"><i class="fas fa-check-circle"></i> Aprobado</span>
              @elseif($ap->estado === 'pendiente')
                <span class="estado-badge est-pend"><i class="fas fa-clock"></i> En revisión</span>
              @else
                <span class="estado-badge est-rej"><i class="fas fa-times-circle"></i> Rechazado</span>
              @endif
            </div>

            <div class="mac-meta">
              <span><i class="fas fa-folder"></i> {{ $ap->categoria }}</span>
              <span><i class="fas fa-calendar-alt"></i> {{ $ap->creado_en->format('d M Y') }}</span>
            </div>

            <p class="mac-uso"><strong>Uso:</strong> {{ $ap->uso }}</p>

            {{-- Motivo rechazo --}}
            @if($ap->estado === 'rechazado' && $ap->motivo_rechazo)
              <div class="mac-motivo">
                <i class="fas fa-exclamation-triangle"></i>
                <div>
                  <strong>Motivo del rechazo:</strong>
                  <p>{{ $ap->motivo_rechazo }}</p>
                </div>
              </div>
            @endif

            {{-- Acciones --}}
            <div class="mac-acciones">
              @if($ap->estado === 'aprobado')
                <a href="{{ route('aportes.show', $ap->id) }}" class="btn-cancel" style="font-size:.82rem;padding:7px 16px;text-decoration:none;display:inline-flex;align-items:center;gap:6px">
                  <i class="fas fa-eye"></i> Ver publicación
                </a>
              @elseif($ap->estado === 'rechazado')
                <a href="{{ route('lector.aporte.edit', $ap->id) }}" class="btn-submit" style="font-size:.82rem;padding:7px 16px;text-decoration:none;display:inline-flex;align-items:center;gap:6px">
                  <i class="fas fa-pen"></i> Editar y reenviar
                </a>
              @else
                <span style="font-size:.8rem;color:var(--texto-suave);font-style:italic">
                  <i class="fas fa-hourglass-half"></i> Esperando revisión del moderador…
                </span>
              @endif
            </div>
          </div>
        </div>
      @endforeach
    </div>

    <div style="margin-top:24px;text-align:center">
      <a href="{{ route('aportar') }}" class="btn-submit" style="display:inline-flex;gap:8px;padding:10px 22px;font-size:.9rem;text-decoration:none">
        <i class="fas fa-plus"></i> Nuevo aporte
      </a>
    </div>
  @endif
</div>

<style>
.mi-aporte-card {
  display: flex; gap: 0; background: var(--bg-card);
  border: 1px solid var(--border-lt); border-radius: 16px; overflow: hidden;
  box-shadow: var(--sombra);
}
.mac-img { width: 130px; flex-shrink: 0; }
.mac-img img { width: 100%; height: 100%; object-fit: cover; }
.mac-noimg {
  width: 100%; height: 100%; min-height: 130px;
  background: linear-gradient(135deg, var(--pale) 0%, #c8e6c9 100%);
  display: flex; align-items: center; justify-content: center;
  font-size: 2rem; color: var(--verde-mid); opacity: .45;
}
.mac-body { flex: 1; padding: 18px 20px; display: flex; flex-direction: column; gap: 8px; }
.mac-top { display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; flex-wrap: wrap; }
.mac-nombre { font-size: 1rem; color: var(--texto); font-weight: 700; display: block; }
.mac-cient { font-size: .78rem; color: var(--texto-suave); display: block; }
.mac-meta { display: flex; gap: 14px; flex-wrap: wrap; font-size: .78rem; color: var(--texto-suave); }
.mac-meta i { color: var(--verde-mid); margin-right: 3px; }
.mac-uso { font-size: .85rem; color: var(--texto); margin: 0; }
.mac-acciones { display: flex; gap: 10px; margin-top: 4px; flex-wrap: wrap; }

/* Estado badges */
.estado-badge {
  display: inline-flex; align-items: center; gap: 5px;
  padding: 4px 12px; border-radius: 20px; font-size: .76rem; font-weight: 700;
  white-space: nowrap; flex-shrink: 0;
}
.est-ok   { background: #e8f5e9; color: #1b5e20; }
.est-pend { background: #fff3e0; color: #c05000; }
.est-rej  { background: #fce4ec; color: #b71c1c; }

/* Motivo rechazo */
.mac-motivo {
  display: flex; gap: 10px; align-items: flex-start;
  background: #fff8f8; border: 1px solid #ffcdd2; border-radius: 10px;
  padding: 10px 14px; font-size: .83rem; color: #b71c1c;
}
.mac-motivo i { margin-top: 2px; flex-shrink: 0; }
.mac-motivo strong { display: block; margin-bottom: 2px; }
.mac-motivo p { margin: 0; color: #555; line-height: 1.5; }

@media (max-width: 500px) {
  .mac-img { width: 80px; }
  .mac-noimg { min-height: 80px; font-size: 1.4rem; }
}
</style>
@endsection
