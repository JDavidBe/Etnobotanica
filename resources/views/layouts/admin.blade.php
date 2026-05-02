<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Panel Admin') — Etnobotánica</title>
  <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,600;0,700;1,400&family=Nunito:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  @vite(['resources/css/app.css'])
  @stack('styles')
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <style>
    [x-cloak] { display: none !important; }
    .alpine-confirm-ov {
      position: fixed; inset: 0; z-index: 9999;
      background: rgba(0,0,0,.45);
      display: flex; align-items: center; justify-content: center;
    }
    .alpine-confirm-box {
      background: #fff; border-radius: 12px;
      padding: 28px 32px; max-width: 420px; width: 90%;
      box-shadow: 0 8px 40px rgba(0,0,0,.18);
      text-align: center;
    }
    .alpine-confirm-box .ac-icon {
      font-size: 2rem; margin-bottom: 12px; color: #e53935;
    }
    .alpine-confirm-box h4 {
      margin: 0 0 8px; font-size: 1.05rem; color: #1b1b1b;
    }
    .alpine-confirm-box p {
      margin: 0 0 22px; font-size: .88rem; color: #555; line-height: 1.5;
    }
    .alpine-confirm-box .ac-btns {
      display: flex; gap: 10px; justify-content: center;
    }
    .alpine-confirm-box .ac-cancel {
      padding: 8px 22px; border-radius: 7px; border: 1px solid #ccc;
      background: #f5f5f5; color: #333; font-size: .88rem; cursor: pointer;
    }
    .alpine-confirm-box .ac-ok {
      padding: 8px 22px; border-radius: 7px; border: none;
      background: #e53935; color: #fff; font-size: .88rem; cursor: pointer;
    }
    .alpine-confirm-box .ac-ok:hover { background: #c62828; }
  </style>
</head>
<body class="admin-body-bg"
      x-data="confirmModal()"
      @confirm-action.window="openConfirm($event.detail)"
      x-init="">

{{-- Modal de confirmación Alpine.js (global) --}}
<div x-cloak x-show="open" class="alpine-confirm-ov"
     x-transition:enter="transition ease-out duration-150"
     x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-100"
     x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
  <div class="alpine-confirm-box" @click.stop>
    <div class="ac-icon"><i class="fas fa-exclamation-triangle"></i></div>
    <h4 x-text="title"></h4>
    <p x-text="message"></p>
    <div class="ac-btns">
      <button class="ac-cancel" @click="cancel()">Cancelar</button>
      <button class="ac-ok" @click="confirm()">Confirmar</button>
    </div>
  </div>
</div>

<script>
function confirmModal() {
  return {
    open: false,
    title: '',
    message: '',
    _resolve: null,
    openConfirm({ title, message, formId }) {
      this.title   = title   || '¿Estás seguro?';
      this.message = message || '';
      this._formId = formId  || null;
      this.open    = true;
    },
    confirm() {
      this.open = false;
      if (this._formId) {
        const f = document.getElementById(this._formId);
        if (f) f.submit();
      }
      this._formId = null;
    },
    cancel() {
      this.open    = false;
      this._formId = null;
    }
  };
}

function alpineConfirm(title, message, formId) {
  window.dispatchEvent(new CustomEvent('confirm-action', {
    detail: { title, message, formId }
  }));
}
</script>

<div id="toast" class="toast"><i class="fas fa-check-circle"></i><span id="toast-txt"></span></div>

<div class="admin-wrap">
  @include('components.sidebar')

  <div class="admin-main">
    <div class="admin-bar">
      <h1 id="admin-tit">@yield('admin-title', 'Dashboard')</h1>
      <div style="display:flex;align-items:center;gap:14px">
        <a href="{{ route('home') }}" class="btn-site"><i class="fas fa-eye"></i> Ver sitio</a>
        <span style="font-size:.83rem;color:var(--texto-suave)">👤 {{ auth()->user()->name }}</span>
        <form method="POST" action="{{ route('logout') }}" style="margin:0">
          @csrf
          <button type="submit" class="btn-logout"><i class="fas fa-sign-out-alt"></i></button>
        </form>
      </div>
    </div>

    <div class="admin-body">
      @yield('content')
    </div>
  </div>
</div>

@vite(['resources/js/app.js'])
@stack('scripts')

</body>
</html>
