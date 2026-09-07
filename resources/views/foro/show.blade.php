@extends('layouts.app')

@section('title', $tema->titulo . ' — Foro')

@section('content')
<div class="container">
  <div class="sec-inner">

    <a href="{{ route('foro.index') }}" class="back-link">
      <i class="fas fa-arrow-left"></i> Volver al foro
    </a>

    @if(session('success'))
      <div class="foro-flash ok"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif
    @if(session('error'))
      <div class="foro-flash err"><i class="fas fa-times-circle"></i> {{ session('error') }}</div>
    @endif

    <div class="com-item foro-tema-card">
      <div class="foro-tema-head">
        <div class="foro-item-badges">
          @if($tema->fijado)
            <span class="foro-badge pin"><i class="fas fa-thumbtack"></i> Fijado</span>
          @endif
          @if($tema->cerrado)
            <span class="foro-badge cerrado"><i class="fas fa-lock"></i> Cerrado</span>
          @endif
          <span class="foro-badge cat">{{ $tema->categoriaLabel() }}</span>
        </div>

        @hasanyrole('admin|moderador')
          <div class="foro-admin-actions">
            <form method="POST" action="{{ route('admin.foro.fijar', $tema) }}">
              @csrf @method('PATCH')
              <button type="submit" class="foro-admin-btn" title="{{ $tema->fijado ? 'Desfijar' : 'Fijar' }}">
                <i class="fas fa-thumbtack"></i>
              </button>
            </form>
            <form method="POST" action="{{ route('admin.foro.cerrar', $tema) }}">
              @csrf @method('PATCH')
              <button type="submit" class="foro-admin-btn" title="{{ $tema->cerrado ? 'Reabrir' : 'Cerrar' }}">
                <i class="fas fa-lock"></i>
              </button>
            </form>
            <form method="POST" action="{{ route('admin.foro.destroy', $tema) }}"
                  onsubmit="return confirm('¿Eliminar este tema y todas sus respuestas? Esta acción no se puede deshacer.')">
              @csrf @method('DELETE')
              <button type="submit" class="foro-admin-btn danger" title="Eliminar">
                <i class="fas fa-trash"></i>
              </button>
            </form>
          </div>
        @endhasanyrole
      </div>

      <p class="sec-titulo" style="margin:8px 0">{{ $tema->titulo }}</p>
      <p class="com-meta" style="margin:0 0 10px">
        <strong class="com-autor">{{ $tema->user->name }}</strong>
        <span class="com-fecha">{{ $tema->created_at->format('d M Y, H:i') }}</span>
        <span class="com-fecha"><i class="fas fa-eye"></i> {{ $tema->vistas }} vistas</span>
      </p>
      <p class="com-texto" style="font-size:.95rem">{{ $tema->contenido }}</p>
    </div>

    <div class="com-header" style="margin-top:32px">
      <h3><i class="fas fa-comments"></i> Respuestas</h3>
      <span class="com-count">{{ $tema->respuestas->count() }} {{ $tema->respuestas->count() === 1 ? 'respuesta' : 'respuestas' }}</span>
    </div>

    <div class="com-lista">
      @forelse($tema->respuestas as $r)
        <div class="com-item">
          <div class="com-avatar"><span>{{ strtoupper(substr($r->user->name, 0, 1)) }}</span></div>
          <div class="com-body">
            <div class="com-meta">
              <strong class="com-autor">{{ $r->user->name }}</strong>
              <span class="com-fecha">{{ $r->created_at->diffForHumans() }}</span>
            </div>
            <p class="com-texto">{{ $r->contenido }}</p>
          </div>
        </div>
      @empty
        <div class="com-empty">
          <i class="fas fa-comment-slash"></i>
          <p>Sé el primero en responder.</p>
        </div>
      @endforelse
    </div>

    @auth
      @if(!$tema->cerrado)
        <div class="com-form-wrap" style="margin-top:20px">
          <h4>Responder</h4>
          <form method="POST" action="{{ route('foro.respuestas.store', $tema) }}" style="display:flex;flex-direction:column;gap:10px">
            @csrf
            <textarea name="contenido" rows="3" maxlength="2000" required class="cf-textarea" placeholder="Escribe tu respuesta…"></textarea>
            <button type="submit" class="btn-submit foro-submit">
              <i class="fas fa-paper-plane"></i> Responder
            </button>
          </form>
        </div>
      @endif
    @else
      <div class="com-login-cta" style="margin-top:20px">
        <div class="clc-ico"><i class="fas fa-lock"></i></div>
        <div>
          <p class="clc-txt">Inicia sesión para responder en este tema.</p>
          <a href="{{ route('login') }}" class="clc-btn"><i class="fas fa-sign-in-alt"></i> Iniciar sesión</a>
        </div>
      </div>
    @endauth

  </div>
</div>

<style>
.foro-flash { display:flex; align-items:center; gap:8px; padding:12px 16px; border-radius:10px; font-size:.88rem; margin-bottom:16px; }
.foro-flash.ok { background:var(--pale); color:var(--verde); border:1px solid var(--verde-mid); }
.foro-flash.err { background:#fff0f0; color:#c0392b; border:1px solid #ffcdd2; }

.foro-tema-card { flex-direction:column; align-items:stretch; }
.foro-tema-head { display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px; }
.foro-admin-actions { display:flex; gap:6px; }
.foro-admin-btn {
  width:34px; height:34px; border-radius:8px; border:1px solid var(--border-lt);
  background:var(--bg-card); color:var(--texto-suave); cursor:pointer; transition: all .15s;
}
.foro-admin-btn:hover { border-color:var(--verde-mid); color:var(--verde-mid); }
.foro-admin-btn.danger:hover { border-color:#c0392b; color:#c0392b; }

.foro-submit { align-self:flex-start; padding:9px 22px; font-size:.88rem; }

@media (max-width: 700px) {
  .foro-tema-head { flex-direction:column; align-items:flex-start; }
  .foro-admin-actions { width:100%; justify-content:flex-end; }
  .foro-submit { width:100%; justify-content:center; }
}
</style>
@endsection
