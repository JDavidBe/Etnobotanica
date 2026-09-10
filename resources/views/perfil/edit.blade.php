@extends('layouts.app')

@section('title', 'Mi perfil — Etnobotánica')

@section('breadcrumbs')
  <i class="fas fa-chevron-right sep"></i>
  <span style="color:rgba(255,255,255,.45)">Mi perfil</span>
@endsection

@section('content')
<div class="container">
  <div class="sec-inner perfil-wrap">

    <p class="sec-titulo">Mi perfil</p>
    <p class="sec-sub">Actualiza tu información personal y tu contraseña.</p>

    @if(session('success'))
      <div class="foro-flash ok"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif

    {{-- ══ Avatar + rol ══ --}}
    <div class="perfil-header-card">
      <div class="perfil-avatar-wrap">
        <div class="com-avatar perfil-avatar-xl" id="perfilAvatarPreview">
          @if($user->avatarUrl())
            <img src="{{ $user->avatarUrl() }}" alt="Foto de perfil">
          @else
            <span>{{ strtoupper(substr($user->name, 0, 1)) }}</span>
          @endif
        </div>
      </div>
      <div>
        <p class="perfil-nombre">{{ $user->name }}</p>
        <p class="perfil-email">{{ $user->email }}</p>
        <span class="foro-badge cat" style="text-transform:capitalize">{{ $user->getRoleNames()->first() ?? 'Usuario' }}</span>
      </div>
    </div>

    {{-- ══ Datos básicos ══ --}}
    <div class="perfil-card">
      <h3 class="perfil-card-titulo"><i class="fas fa-id-card"></i> Datos personales</h3>

      @if($errors->updatePerfil->any() ?? false)
        <div class="alert-error">
          <i class="fas fa-circle-exclamation" style="margin-top:2px"></i>
          <ul style="margin:0;padding-left:18px">
            @foreach($errors->updatePerfil->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form method="POST" action="{{ route('perfil.update') }}" id="perfilDatosForm" enctype="multipart/form-data" novalidate>
        @csrf
        @method('PUT')

        <div class="f-group" style="margin-bottom:20px">
          <label>Foto de perfil</label>
          <div class="perfil-foto-picker">
            <div class="com-avatar perfil-avatar-md" id="perfilFotoBtnPreview">
              @if($user->avatarUrl())
                <img src="{{ $user->avatarUrl() }}" alt="Foto de perfil">
              @else
                <span>{{ strtoupper(substr($user->name, 0, 1)) }}</span>
              @endif
            </div>
            <label for="avatarInput" class="btn-cancel" style="cursor:pointer">
              <i class="fas fa-camera"></i> Cambiar foto
            </label>
            <input type="file" name="avatar" id="avatarInput" accept="image/png,image/jpeg,image/webp" style="display:none">
          </div>
          <p class="foro-hint">JPG, PNG o WEBP — máximo 2 MB.</p>
        </div>

        <div class="f-group">
          <label>Nombre completo</label>
          <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
        </div>
        <div class="f-group" style="margin-bottom:20px">
          <label>Correo electrónico</label>
          <input type="email" value="{{ $user->email }}" disabled
                 style="opacity:.6;cursor:not-allowed">
          <p class="foro-hint">El correo no se puede cambiar. Contacta a un administrador si lo necesitas actualizar.</p>
        </div>
        <button type="submit" class="btn-submit">
          <i class="fas fa-save"></i> Guardar cambios
        </button>
      </form>
    </div>

    {{-- ══ Cambiar contraseña ══ --}}
    <div class="perfil-card">
      <h3 class="perfil-card-titulo"><i class="fas fa-lock"></i> Cambiar contraseña</h3>

      @if($errors->updatePassword->any() ?? false)
        <div class="alert-error">
          <i class="fas fa-circle-exclamation" style="margin-top:2px"></i>
          <ul style="margin:0;padding-left:18px">
            @foreach($errors->updatePassword->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form method="POST" action="{{ route('perfil.password') }}" id="perfilPassForm" novalidate>
        @csrf
        @method('PUT')
        <div class="f-group">
          <label>Contraseña actual</label>
          <div class="pass-wrap">
            <input type="password" name="password_actual" required>
            <button type="button" class="pass-toggle" onclick="togglePassword(this)" tabindex="-1" aria-label="Mostrar contraseña"><i class="fas fa-eye"></i></button>
          </div>
        </div>
        <div class="f-group">
          <label>Contraseña nueva</label>
          <div class="pass-wrap">
            <input type="password" name="password" required minlength="8">
            <button type="button" class="pass-toggle" onclick="togglePassword(this)" tabindex="-1" aria-label="Mostrar contraseña"><i class="fas fa-eye"></i></button>
          </div>
          <p class="f-hint">Mínimo 8 caracteres</p>
        </div>
        <div class="f-group" style="margin-bottom:20px">
          <label>Confirmar contraseña nueva</label>
          <div class="pass-wrap">
            <input type="password" name="password_confirmation" required minlength="8">
            <button type="button" class="pass-toggle" onclick="togglePassword(this)" tabindex="-1" aria-label="Mostrar contraseña"><i class="fas fa-eye"></i></button>
          </div>
        </div>
        <button type="submit" class="btn-submit">
          <i class="fas fa-key"></i> Actualizar contraseña
        </button>
      </form>
    </div>

  </div>
</div>

<style>
.perfil-wrap { max-width: 640px; }

.perfil-header-card {
  display: flex; align-items: center; gap: 18px;
  background: var(--bg-card); border: 1px solid var(--border-lt);
  border-radius: 16px; padding: 22px 24px; margin-bottom: 24px;
}
.perfil-avatar-xl { width: 56px; height: 56px; font-size: 1.3rem; flex-shrink: 0; }
.perfil-nombre { font-weight: 800; font-size: 1.05rem; color: var(--texto); margin: 0 0 2px; }
.perfil-email { font-size: .84rem; color: var(--texto-suave); margin: 0 0 8px; }

.perfil-card {
  background: var(--bg-card); border: 1px solid var(--border-lt);
  border-radius: 16px; padding: 24px 26px; margin-bottom: 22px;
}
.perfil-card-titulo {
  font-family: 'Lora', serif; font-size: 1.05rem; color: var(--verde);
  margin: 0 0 18px; display: flex; align-items: center; gap: 9px;
}

.perfil-avatar-xl img, .perfil-avatar-md img { width:100%; height:100%; object-fit:cover; border-radius:50%; }
.perfil-foto-picker { display:flex; align-items:center; gap:14px; }
.perfil-avatar-md { width:54px; height:54px; font-size:1.15rem; flex-shrink:0; }
.btn-cancel { display:inline-flex; align-items:center; gap:7px; }

.campo-error input { border-color: #e53935 !important; background: rgba(229,57,53,.06) !important; }

@media (max-width: 700px) {
  .perfil-card, .perfil-header-card { padding: 18px; }
}
</style>

<script>
function activarValidacionRoja(formId) {
  const form = document.getElementById(formId);
  if (!form) return;
  const campos = form.querySelectorAll('input[required]');

  function marcar(input, esError) {
    const grupo = input.closest('.f-group');
    if (grupo) grupo.classList.toggle('campo-error', esError);
  }

  campos.forEach(input => {
    input.addEventListener('input', () => { if (input.checkValidity()) marcar(input, false); });
  });

  form.addEventListener('submit', (e) => {
    let valido = true;
    campos.forEach(input => {
      const ok = input.checkValidity();
      marcar(input, !ok);
      if (!ok) valido = false;
    });
    if (!valido) e.preventDefault();
  });
}
activarValidacionRoja('perfilDatosForm');
activarValidacionRoja('perfilPassForm');

// Vista previa en vivo de la foto elegida (antes de guardar)
const avatarInput = document.getElementById('avatarInput');
if (avatarInput) {
  avatarInput.addEventListener('change', () => {
    const file = avatarInput.files[0];
    if (!file) return;
    const url = URL.createObjectURL(file);
    ['perfilAvatarPreview', 'perfilFotoBtnPreview'].forEach(id => {
      const box = document.getElementById(id);
      box.innerHTML = `<img src="${url}" alt="Vista previa">`;
    });
  });
}
</script>
@endsection