<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Crear cuenta — Etnobotánica</title>
  <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,600;0,700;1,400&family=Nunito:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="login-bg">

<div class="login-card">
  <div class="login-logo"><i class="fas fa-leaf"></i></div>
  <h2>Crear cuenta</h2>
  <p>Únete a la comunidad de Etnobotánica</p>

  @if($errors->any())
    <div class="login-err show">
      <i class="fas fa-exclamation-circle"></i>
      <span>
        @foreach($errors->all() as $error)
          {{ $error }}@if(!$loop->last)<br>@endif
        @endforeach
      </span>
    </div>
  @endif

  <form method="POST" action="{{ route('register') }}" id="registroForm" novalidate>
    @csrf
    <div class="f-group">
      <label>Nombre completo</label>
      <input type="text" name="name" value="{{ old('name') }}" autofocus required>
    </div>
    <div class="f-group">
      <label>Correo electrónico</label>
      <input type="email" name="email" value="{{ old('email') }}" required>
    </div>
        <div class="f-group">
      <label>Contraseña</label>
      <div class="pass-wrap">
        <input type="password" name="password" required minlength="8">
        <button type="button" class="pass-toggle" onclick="togglePassword(this)" tabindex="-1" aria-label="Mostrar contraseña"><i class="fas fa-eye"></i></button>
      </div>
      <p class="f-hint">Mínimo 8 caracteres</p>
    </div>
    <div class="f-group" style="margin-bottom:22px">
      <label>Confirmar contraseña</label>
      <div class="pass-wrap">
        <input type="password" name="password_confirmation" required minlength="8">
        <button type="button" class="pass-toggle" onclick="togglePassword(this)" tabindex="-1" aria-label="Mostrar contraseña"><i class="fas fa-eye"></i></button>
      </div>
    </div>
    <div class="f-group" id="terminosGroup" style="margin-bottom:22px">
      <label style="display:flex;align-items:flex-start;gap:9px;font-weight:400;cursor:pointer">
        <input type="checkbox" name="terminos" value="1" required style="margin-top:3px;width:16px;height:16px;flex-shrink:0" {{ old('terminos') ? 'checked' : '' }}>
        <span>He leído y acepto los <a href="{{ route('terminos') }}" target="_blank" style="color:var(--verde-mid);font-weight:700;text-decoration:underline">Términos y Condiciones</a></span>
      </label>
    </div>
    <button type="submit" class="btn-submit" style="width:100%;justify-content:center">
      <i class="fas fa-user-plus"></i> Crear cuenta
    </button>
  </form>

  <a href="{{ route('login') }}"
     style="margin-top:16px;display:block;text-align:center;color:var(--verde-mid);font-size:.84rem;text-decoration:none;font-weight:700">
    ¿Ya tienes cuenta? Inicia sesión
  </a>
  <a href="{{ route('home') }}"
     style="margin-top:13px;display:block;text-align:center;color:var(--texto-suave);font-size:.84rem;text-decoration:none">
    ← Volver al sitio
  </a>
</div>

<style>
.campo-error input[type="text"],
.campo-error input[type="email"],
.campo-error input[type="password"] {
  border-color: #e53935 !important;
  background: rgba(229, 57, 53, .06) !important;
}
#terminosGroup.campo-error {
  border: 1px solid #e53935;
  background: rgba(229, 57, 53, .06);
  border-radius: 10px;
  padding: 8px 10px;
}
#terminosGroup.campo-error input[type="checkbox"] {
  outline: 2px solid #e53935;
  outline-offset: 2px;
}
</style>

<script>
  const savedTheme = localStorage.getItem('etno_theme');
  if (savedTheme) {
    document.documentElement.setAttribute('data-theme', savedTheme);
  }

  const registroForm = document.getElementById('registroForm');
  if (registroForm) {
    const campos = registroForm.querySelectorAll('input[required]');

    function marcarError(input, esError) {
      const grupo = input.closest('.f-group');
      if (!grupo) return;
      grupo.classList.toggle('campo-error', esError);
    }

    campos.forEach(input => {
      const evento = input.type === 'checkbox' ? 'change' : 'input';
      input.addEventListener(evento, () => {
        if (input.checkValidity()) marcarError(input, false);
      });
    });

    registroForm.addEventListener('submit', (e) => {
      let valido = true;
      campos.forEach(input => {
        if (!input.checkValidity()) {
          marcarError(input, true);
          valido = false;
        } else {
          marcarError(input, false);
        }
      });
      if (!valido) e.preventDefault();
    });
  }
</script>
</body>
</html>