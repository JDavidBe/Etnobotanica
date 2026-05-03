{{--
  Componente reutilizable de comentarios.
  Requiere:
    $tipo        — 'planta' | 'aporte'
    $tipo_id     — id del recurso
    $comentarios — colección mapeada con likes_count y ya_likeado
--}}

<section class="comentarios-section" id="comentarios">
  <div class="com-header">
    <h3><i class="fas fa-comments"></i> Comentarios de la comunidad</h3>
    <span class="com-count">{{ count($comentarios) }} {{ count($comentarios) === 1 ? 'comentario' : 'comentarios' }}</span>
  </div>

  {{-- Lista --}}
  <div class="com-lista" id="com-lista-{{ $tipo }}-{{ $tipo_id }}">
    @forelse($comentarios as $c)
      <div class="com-item" id="com-item-{{ $c['id'] }}">
        <div class="com-avatar">
          <span>{{ strtoupper(substr($c['autor'], 0, 1)) }}</span>
        </div>
        <div class="com-body">
          <div class="com-meta">
            <strong class="com-autor">{{ $c['autor'] }}</strong>
            <span class="com-fecha">{{ \Carbon\Carbon::parse($c['creado_en'])->format('d M Y, H:i') }}</span>
          </div>
          <p class="com-texto">{{ $c['contenido'] }}</p>
          <button class="com-like-btn {{ $c['ya_likeado'] ? 'liked' : '' }}"
                  data-id="{{ $c['id'] }}"
                  onclick="toggleLike(this)">
            <i class="fas fa-heart"></i>
            <span class="like-count">{{ $c['likes_count'] }}</span>
          </button>
        </div>
      </div>
    @empty
      <div class="com-empty" id="com-empty-{{ $tipo }}-{{ $tipo_id }}">
        <i class="fas fa-comment-slash"></i>
        <p>Aún no hay comentarios. ¡Sé el primero!</p>
      </div>
    @endforelse
  </div>

  {{-- Formulario: solo usuarios autenticados --}}
  @auth
    <div class="com-form-wrap">
      <h4>Deja tu comentario</h4>
      <div class="com-form">
        <div class="cf-row">
          <textarea id="cf-contenido-{{ $tipo }}-{{ $tipo_id }}"
                    placeholder="Escribe tu comentario…"
                    rows="3"
                    maxlength="1000"
                    class="cf-textarea"></textarea>
          <span class="cf-chars" id="cf-chars-{{ $tipo }}-{{ $tipo_id }}">0 / 1000</span>
        </div>
        <div class="cf-actions">
          <span class="cf-user-hint">
            <i class="fas fa-user-circle"></i> Comentando como <strong>{{ auth()->user()->name }}</strong>
          </span>
          <button class="btn-submit"
                  style="padding:9px 22px;font-size:.88rem"
                  onclick="enviarComentario('{{ $tipo }}', {{ $tipo_id }})">
            <i class="fas fa-paper-plane"></i> Publicar
          </button>
        </div>
        <div class="cf-error" id="cf-error-{{ $tipo }}-{{ $tipo_id }}" style="display:none"></div>
      </div>
    </div>
  @else
    {{-- CTA para invitados --}}
    <div class="com-login-cta">
      <div class="clc-ico"><i class="fas fa-lock"></i></div>
      <div>
        <p class="clc-txt">Inicia sesión para dejar un comentario.</p>
        <a href="{{ route('login') }}" class="clc-btn">
          <i class="fas fa-sign-in-alt"></i> Iniciar sesión
        </a>
      </div>
    </div>
  @endauth
</section>

<style>
/* ── Sección comentarios ── */
.comentarios-section {
  margin-top: 40px;
  border-top: 2px solid var(--border-lt);
  padding-top: 28px;
}
.com-header {
  display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;
  margin-bottom: 22px;
}
.com-header h3 { font-family: 'Lora', serif; font-size: 1.15rem; color: var(--verde); margin: 0; }
.com-count {
  font-size: .8rem; color: var(--texto-suave);
  background: var(--pale); padding: 3px 10px; border-radius: 20px;
}
.com-lista { display: flex; flex-direction: column; gap: 14px; margin-bottom: 28px; }
.com-item {
  display: flex; gap: 14px; align-items: flex-start;
  background: var(--bg-card); border: 1px solid var(--border-lt);
  border-radius: 14px; padding: 16px 18px; transition: box-shadow .2s;
}
.com-item:hover { box-shadow: 0 2px 12px rgba(0,0,0,.07); }
.com-avatar {
  width: 40px; height: 40px; border-radius: 50%; flex-shrink: 0;
  background: linear-gradient(135deg, var(--verde), var(--verde-mid));
  display: flex; align-items: center; justify-content: center;
  color: #fff; font-weight: 700; font-size: .95rem;
}
.com-body { flex: 1; min-width: 0; }
.com-meta { display: flex; align-items: baseline; gap: 10px; margin-bottom: 6px; flex-wrap: wrap; }
.com-autor { font-size: .9rem; color: var(--texto); }
.com-fecha { font-size: .75rem; color: var(--texto-suave); }
.com-texto { font-size: .88rem; color: var(--texto); line-height: 1.6; margin: 0 0 10px; word-break: break-word; }
.com-like-btn {
  display: inline-flex; align-items: center; gap: 5px;
  background: none; border: 1px solid var(--border-lt);
  border-radius: 20px; padding: 3px 11px;
  font-size: .78rem; color: var(--texto-suave); cursor: pointer; transition: all .15s;
}
.com-like-btn:hover { border-color: #e57373; color: #e57373; }
.com-like-btn.liked { border-color: #e53935; color: #e53935; background: #fff0f0; }
.com-like-btn.liked i { animation: heartpop .25s ease; }
@keyframes heartpop { 0%,100%{transform:scale(1)} 50%{transform:scale(1.4)} }
.com-empty { text-align: center; padding: 32px; color: var(--texto-suave); }
.com-empty i { font-size: 2rem; opacity: .3; display: block; margin-bottom: 8px; }
.com-empty p { font-size: .88rem; margin: 0; }

/* ── Formulario autenticado ── */
.com-form-wrap {
  background: var(--bg-card); border: 1px solid var(--border-lt);
  border-radius: 14px; padding: 20px 22px;
}
.com-form-wrap h4 { font-size: .95rem; color: var(--verde); margin: 0 0 14px; font-family: 'Lora', serif; }
.com-form { display: flex; flex-direction: column; gap: 10px; }
.cf-row { position: relative; }
.cf-textarea {
  width: 100%; box-sizing: border-box;
  border: 1px solid var(--border-lt); border-radius: 9px;
  padding: 10px 14px; font-size: .88rem;
  font-family: 'Nunito', sans-serif;
  background: var(--fondo-card, #fafafa); color: var(--texto);
  transition: border-color .2s; resize: vertical;
}
.cf-textarea:focus {
  outline: none; border-color: var(--verde-mid);
  box-shadow: 0 0 0 3px rgba(46,139,87,.12);
}
.cf-chars { font-size: .72rem; color: var(--texto-suave); display: block; text-align: right; margin-top: 3px; }
.cf-actions { display: flex; align-items: center; justify-content: space-between; gap: 10px; flex-wrap: wrap; }
.cf-user-hint { font-size: .78rem; color: var(--texto-suave); }
.cf-user-hint i { color: var(--verde-mid); margin-right: 3px; }
.cf-error {
  font-size: .83rem; color: var(--danger, #e53935);
  background: #fff0f0; border: 1px solid #ffcdd2;
  border-radius: 8px; padding: 8px 12px; margin-top: 4px;
}

/* ── CTA invitado ── */
.com-login-cta {
  display: flex; align-items: center; gap: 16px;
  background: var(--pale); border: 1px dashed var(--verde-mid);
  border-radius: 14px; padding: 20px 24px;
}
.clc-ico {
  width: 44px; height: 44px; border-radius: 50%; flex-shrink: 0;
  background: rgba(46,125,50,.12);
  display: flex; align-items: center; justify-content: center;
  font-size: 1.1rem; color: var(--verde);
}
.clc-txt { font-size: .88rem; color: var(--texto-suave); margin: 0 0 10px; }
.clc-btn {
  display: inline-flex; align-items: center; gap: 7px;
  background: var(--verde); color: #fff;
  padding: 8px 18px; border-radius: 22px; font-size: .83rem;
  font-weight: 700; text-decoration: none; transition: background .2s;
}
.clc-btn:hover { background: var(--verde-mid); color: #fff; }
</style>

@push('scripts')
<script>
document.querySelectorAll('[id^="cf-contenido-"]').forEach(ta => {
  const key  = ta.id.replace('cf-contenido-', '');
  const chars = document.getElementById('cf-chars-' + key);
  ta.addEventListener('input', () => { if (chars) chars.textContent = ta.value.length + ' / 1000'; });
});

async function enviarComentario(tipo, tipoId) {
  const key        = tipo + '-' + tipoId;
  const contenidoEl = document.getElementById('cf-contenido-' + key);
  const errorEl    = document.getElementById('cf-error-'    + key);
  const listaEl    = document.getElementById('com-lista-'   + key);
  const emptyEl    = document.getElementById('com-empty-'   + key);

  const contenido = contenidoEl.value.trim();
  errorEl.style.display = 'none';

  if (!contenido) {
    errorEl.textContent = 'El comentario no puede estar vacío.';
    errorEl.style.display = 'block';
    contenidoEl.focus();
    return;
  }

  try {
    const res = await fetch('{{ route("comentarios.store") }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept':       'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
      },
      body: JSON.stringify({ tipo, tipo_id: tipoId, contenido }),
    });

    if (!res.ok) {
      const data = await res.json();
      errorEl.textContent = data.message || 'Error al publicar.';
      errorEl.style.display = 'block';
      return;
    }

    const c = await res.json();
    if (emptyEl) emptyEl.remove();

    const html = `
      <div class="com-item" id="com-item-${c.id}">
        <div class="com-avatar"><span>${c.autor.charAt(0).toUpperCase()}</span></div>
        <div class="com-body">
          <div class="com-meta">
            <strong class="com-autor">${escHtml(c.autor)}</strong>
            <span class="com-fecha">${c.creado_en}</span>
          </div>
          <p class="com-texto">${escHtml(c.contenido)}</p>
          <button class="com-like-btn" data-id="${c.id}" onclick="toggleLike(this)">
            <i class="fas fa-heart"></i>
            <span class="like-count">0</span>
          </button>
        </div>
      </div>`;
    listaEl.insertAdjacentHTML('afterbegin', html);

    const countEl = listaEl.closest('.comentarios-section')?.querySelector('.com-count');
    if (countEl) {
      const n = listaEl.querySelectorAll('.com-item').length;
      countEl.textContent = n + ' ' + (n === 1 ? 'comentario' : 'comentarios');
    }

    contenidoEl.value = '';
    const chars = document.getElementById('cf-chars-' + key);
    if (chars) chars.textContent = '0 / 1000';

    mostrarToast('¡Comentario publicado!');

  } catch (e) {
    errorEl.textContent = 'Error de red. Intenta de nuevo.';
    errorEl.style.display = 'block';
  }
}

async function toggleLike(btn) {
  const id = btn.dataset.id;
  try {
    const res = await fetch(`/comentarios/${id}/like`, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
        'Accept': 'application/json',
      },
    });
    const data = await res.json();
    btn.querySelector('.like-count').textContent = data.likes;
    btn.classList.toggle('liked', data.liked);
  } catch (e) {}
}

function escHtml(str) {
  return str.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

function mostrarToast(msg) {
  const t = document.getElementById('toast');
  const s = document.getElementById('toast-txt');
  if (!t || !s) return;
  s.textContent = msg;
  t.classList.add('show');
  setTimeout(() => t.classList.remove('show'), 3000);
}
</script>
@endpush
