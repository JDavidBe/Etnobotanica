@extends('layouts.app')

@section('title', $tema->titulo . ' — Foro')

@section('content')
<div class="container">
  <div class="sec-inner">

    <a href="{{ route('foro.index') }}" class="foro-back-link">
      <span class="foro-back-icon"><i class="fas fa-arrow-left"></i></span> Volver al foro
    </a>

    @if(session('success'))
      <div class="foro-flash ok"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif
    @if(session('error'))
      <div class="foro-flash err"><i class="fas fa-times-circle"></i> {{ session('error') }}</div>
    @endif

    {{-- ══ TEMA (post que abre la discusión) ══ --}}
    <div class="foro-tema-hero">
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

      <h1 class="foro-tema-titulo">{{ $tema->titulo }}</h1>

      <div class="foro-tema-autor">
        <div class="com-avatar foro-avatar-lg"><span>{{ strtoupper(substr($tema->user->name, 0, 1)) }}</span></div>
        <div>
          <p class="foro-autor-nombre">{{ $tema->user->name }} <span class="foro-badge autor">Autor del tema</span></p>
          <p class="com-fecha" style="margin:0">{{ $tema->created_at->format('d M Y, H:i') }} · <i class="fas fa-eye"></i> {{ $tema->vistas }} vistas</p>
        </div>
      </div>

      <p class="foro-tema-contenido">{{ $tema->contenido }}</p>
    </div>

    {{-- ══ RESPUESTAS ══ --}}
    <div class="foro-respuestas-wrap">
      <div class="com-header">
        <h3><i class="fas fa-comments"></i> Respuestas</h3>
        <span class="com-count">{{ $tema->respuestas->count() }} {{ $tema->respuestas->count() === 1 ? 'respuesta' : 'respuestas' }}</span>
      </div>

      <div class="com-lista foro-respuestas-lista">
        @forelse($tema->respuestas as $r)
          <div class="com-item foro-respuesta-item">
            <div class="com-avatar"><span>{{ strtoupper(substr($r->user->name, 0, 1)) }}</span></div>
            <div class="com-body">
              <div class="com-meta">
                <strong class="com-autor">{{ $r->user->name }}</strong>
                @if($r->user_id === $tema->user_id)
                  <span class="foro-badge autor sm">Autor del tema</span>
                @endif
                <span class="com-fecha">{{ $r->created_at->diffForHumans() }}</span>
              </div>
              <p class="com-texto">{{ $r->contenido }}</p>
            </div>
          </div>
        @empty
          <div class="com-empty">
            <i class="fas fa-comment-slash"></i>
            <p>Todavía no hay respuestas. ¡Sé el primero en participar!</p>
          </div>
        @endforelse
      </div>
    </div>

    {{-- ══ FORMULARIO DE RESPUESTA ══ --}}
    @auth
      @if(!$tema->cerrado)
        <div class="foro-responder-card">
          <h4 class="foro-responder-titulo"><i class="fas fa-reply"></i> Escribe una respuesta</h4>
          <form method="POST" action="{{ route('foro.respuestas.store', $tema) }}">
            @csrf
            <div class="f-group" style="margin-bottom:12px">
              <textarea name="contenido" id="foroRespuestaTexto" rows="3" maxlength="2000" required
                        placeholder="Comparte tu opinión, experiencia o solución…"></textarea>
              <p class="foro-hint"><span id="foroRespuestaCount">0</span>/2000 caracteres</p>
            </div>
            <button type="submit" class="btn-submit foro-submit">
              <i class="fas fa-paper-plane"></i> Responder
            </button>
          </form>
        </div>
      @else
        <div class="foro-cerrado-aviso">
          <i class="fas fa-lock"></i> Este tema está cerrado y ya no acepta nuevas respuestas.
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

.foro-flash { display:flex; align-items:center; gap:8px; padding:12px 16px; border-radius:10px; font-size:.88rem; margin-bottom:16px; }
.foro-flash.ok { background:var(--pale); color:var(--verde); border:1px solid var(--verde-mid); }
.foro-flash.err { background:#fff0f0; color:#c0392b; border:1px solid #ffcdd2; }

/* ══ Clases base reutilizadas del bloque de comentarios (copiadas aquí porque
   esta vista no carga components/comentarios.blade.php) ══ */
.com-header { display:flex; align-items:center; gap:10px; }
.com-header h3 { font-family:'Lora', serif; font-size:1.15rem; color:var(--verde); margin:0; display:flex; align-items:center; gap:8px; }
.com-count { font-size:.8rem; color:var(--texto-suave); background:var(--pale); padding:3px 10px; border-radius:20px; }
.com-lista { display:flex; flex-direction:column; gap:14px; }
.com-item { display:flex; gap:14px; align-items:flex-start; background:var(--bg-card); border:1px solid var(--border-lt); border-radius:14px; padding:16px 18px; transition:box-shadow .2s; }
.com-item:hover { box-shadow:0 2px 12px rgba(0,0,0,.07); }
.com-avatar { width:40px; height:40px; border-radius:50%; flex-shrink:0; background:linear-gradient(135deg, var(--verde), var(--verde-mid)); display:flex; align-items:center; justify-content:center; color:#fff; font-weight:700; font-size:.95rem; }
.com-body { flex:1; min-width:0; }
.com-meta { display:flex; align-items:baseline; gap:10px; margin-bottom:6px; flex-wrap:wrap; }
.com-autor { font-size:.9rem; color:var(--texto); }
.com-fecha { font-size:.75rem; color:var(--texto-suave); }
.com-texto { font-size:.88rem; color:var(--texto); line-height:1.6; margin:0; word-break:break-word; }
.com-empty { text-align:center; padding:32px; color:var(--texto-suave); }
.com-empty i { font-size:2rem; opacity:.3; display:block; margin-bottom:8px; }
.com-empty p { font-size:.88rem; margin:0; }

.com-login-cta { display:flex; align-items:center; gap:16px; background:var(--pale); border:1px dashed var(--verde-mid); border-radius:14px; padding:20px 24px; }
.clc-ico { width:44px; height:44px; border-radius:50%; flex-shrink:0; background:rgba(46,125,50,.12); display:flex; align-items:center; justify-content:center; font-size:1.1rem; color:var(--verde); }
.clc-txt { font-size:.88rem; color:var(--texto-suave); margin:0 0 10px; }
.clc-btn { display:inline-flex; align-items:center; gap:7px; background:var(--verde); color:#fff; padding:8px 18px; border-radius:22px; font-size:.83rem; font-weight:700; text-decoration:none; transition:background .2s; }
.clc-btn:hover { background:var(--verde-mid); color:#fff; }

/* ══ Tarjeta destacada del tema (post que abre la discusión) ══ */
.foro-tema-hero {
  background: var(--bg-card);
  border: 1px solid var(--border-lt);
  border-left: 4px solid var(--dorado);
  border-radius: 16px;
  padding: 26px 28px;
  box-shadow: var(--sombra-lg, 0 4px 20px rgba(0,0,0,.15));
  margin-bottom: 30px;
}
.foro-tema-head { display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px; margin-bottom: 14px; }
.foro-admin-actions { display:flex; gap:6px; }
.foro-admin-btn {
  width:34px; height:34px; border-radius:8px; border:1px solid var(--border-lt);
  background:var(--bg-card); color:var(--texto-suave); cursor:pointer; transition: all .15s;
}
.foro-admin-btn:hover { border-color:var(--verde-mid); color:var(--verde-mid); }
.foro-admin-btn.danger:hover { border-color:#c0392b; color:#c0392b; }

.foro-tema-titulo {
  font-family: 'Lora', serif;
  font-size: 1.65rem;
  color: var(--texto);
  margin: 0 0 18px;
  line-height: 1.3;
}

.foro-tema-autor { display:flex; align-items:center; gap:12px; margin-bottom:18px; }
.foro-avatar-lg { width:44px; height:44px; font-size:1rem; flex-shrink:0; }
.foro-autor-nombre { margin:0 0 3px; font-weight:700; color:var(--texto); display:flex; align-items:center; gap:8px; flex-wrap:wrap; }

.foro-badge.autor { background: var(--verde); color:#fff; font-size:.66rem; padding:2px 9px; border-radius:10px; font-weight:700; }
.foro-badge.autor.sm { font-size:.62rem; padding:1px 8px; }

.foro-tema-contenido {
  font-size: 1rem;
  line-height: 1.7;
  color: var(--texto-mid);
  white-space: pre-line;
  padding-top: 14px;
  border-top: 1px solid var(--border-lt);
}

/* ══ Bloque de respuestas, visualmente separado del tema ══ */
.foro-respuestas-wrap {
  background: var(--bg-input);
  border: 1px solid var(--border-lt);
  border-radius: 16px;
  padding: 22px 24px;
  margin-bottom: 24px;
}
.foro-respuestas-lista { margin-top: 14px; }
.foro-respuesta-item { background: var(--bg-card); }

/* ══ Formulario de respuesta ══ */
.foro-responder-card {
  background: var(--bg-card);
  border: 1px solid var(--border-lt);
  border-radius: 16px;
  padding: 22px 24px;
}
.foro-responder-titulo { margin: 0 0 14px; font-size: .95rem; color: var(--verde); display:flex; align-items:center; gap:8px; }
.foro-hint { font-size: .76rem; color: var(--texto-suave); margin: 6px 2px 0; }
.foro-submit { align-self:flex-start; padding:9px 22px; font-size:.88rem; }

.foro-cerrado-aviso {
  display:flex; align-items:center; gap:9px;
  background: var(--pale); color: var(--texto-suave);
  border: 1px solid var(--border-lt); border-radius: 12px;
  padding: 14px 18px; font-size: .88rem;
}

@media (max-width: 700px) {
  .foro-tema-head { flex-direction:column; align-items:flex-start; }
  .foro-admin-actions { width:100%; justify-content:flex-end; }
  .foro-submit { width:100%; justify-content:center; }
  .foro-tema-hero, .foro-respuestas-wrap, .foro-responder-card { padding: 18px; }
}
</style>

<script>
(function () {
  const input = document.getElementById('foroRespuestaTexto');
  const counter = document.getElementById('foroRespuestaCount');
  if (!input || !counter) return;
  const update = () => counter.textContent = input.value.length;
  input.addEventListener('input', update);
  update();
})();
</script>
@endsection