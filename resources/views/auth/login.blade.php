<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Acceso Admin — Etnobotánica</title>
  <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,600;0,700;1,400&family=Nunito:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="login-bg">

<div class="login-card">
  <div class="login-logo"><i class="fas fa-leaf"></i></div>
  <h2>Login</h2>
  <p>Hola, bienvenid@ de nuevo a tu cuenta</p>

  @if($errors->any())
    <div class="login-err show">
      <i class="fas fa-exclamation-circle"></i> Usuario o contraseña incorrectos
    </div>
  @endif

  <form method="POST" action="{{ route('login') }}">
    @csrf
    <div class="f-group">
      <label>Correo electrónico</label>
      <input type="email" name="email" value="{{ old('email') }}" autofocus required>
    </div>
        <div class="f-group" style="margin-bottom:22px">
      <label>Contraseña</label>
      <div class="pass-wrap">
        <input type="password" name="password" required>
        <button type="button" class="pass-toggle" onclick="togglePassword(this)" tabindex="-1" aria-label="Mostrar contraseña"><i class="fas fa-eye"></i></button>
      </div>
    </div>
    <button type="submit" class="btn-submit" style="width:100%;justify-content:center">
      <i class="fas fa-sign-in-alt"></i> Ingresar
    </button>
  </form>

  <a href="{{ route('home') }}"
     style="margin-top:13px;display:block;text-align:center;color:var(--texto-suave);font-size:.84rem;text-decoration:none">
    ← Volver al sitio
  </a>
  
  <a href="{{ route('register') }}"
   style="margin-top:16px;display:block;text-align:center;color:var(--verde-mid);font-size:.84rem;text-decoration:none;font-weight:700">
  ¿No tienes cuenta? Regístrate
</a>
</div>

<script>
  const savedTheme = localStorage.getItem('etno_theme');
  if (savedTheme) {
    document.documentElement.setAttribute('data-theme', savedTheme);
  }
</script>
</body>
</html>
