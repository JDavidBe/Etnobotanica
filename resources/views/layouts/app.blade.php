<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Etnobotánica Fusagasugá')</title>
  <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,600;0,700;1,400&family=Nunito:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  @vite(['resources/css/app.css'])
  @stack('styles')
</head>
<body>

<div id="toast" class="toast"><i class="fas fa-check-circle"></i><span id="toast-txt"></span></div>

@include('components.header')

<main id="main-content">
  @yield('content')
</main>

@vite(['resources/js/app.js'])
@stack('scripts')

</body>
</html>
