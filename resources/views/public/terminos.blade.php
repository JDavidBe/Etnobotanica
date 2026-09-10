@extends('layouts.app')

@section('title', 'Términos y Condiciones — Etnobotánica')

@section('breadcrumbs')
  <i class="fas fa-chevron-right sep"></i>
  <span style="color:rgba(255,255,255,.45)">Términos y condiciones</span>
@endsection

@section('content')

<div class="ficha-header">
  <div class="container" style="padding-top:2.5rem;padding-bottom:2.5rem">
    <h2 style="font-size:2rem;margin-bottom:.5rem">
      <i class="fas fa-file-contract" style="font-size:1.5rem;opacity:.85"></i> Términos y condiciones
    </h2>
    <p style="opacity:.75;font-size:1rem">Última actualización: {{ now()->format('d \d\e F \d\e Y') }}</p>
  </div>
</div>

<div class="container" style="max-width:820px;padding:2.5rem 1rem 4rem">
  <div style="background:var(--bg-card);border:1px solid var(--border-lt);border-radius:16px;padding:32px 34px;line-height:1.75;color:var(--texto-mid);font-size:.95rem">

    <p style="margin-bottom:18px">Al crear una cuenta en Etnobotánica — plataforma de catálogo etnobotánico colaborativo de Fusagasugá, Cundinamarca — aceptas los siguientes términos:</p>

    <h3 style="color:var(--verde);font-family:'Lora',serif;margin:22px 0 8px">1. Uso de la plataforma</h3>
    <p style="margin-bottom:18px">Etnobotánica es un espacio educativo y comunitario para compartir y consultar información sobre plantas y saberes etnobotánicos. Su uso debe limitarse a fines informativos, académicos y de intercambio respetuoso de conocimiento.</p>

    <h3 style="color:var(--verde);font-family:'Lora',serif;margin:22px 0 8px">2. Contenido publicado por los usuarios</h3>
    <p style="margin-bottom:18px">Los aportes, comentarios y temas del foro publicados por los usuarios son responsabilidad de quien los publica. La plataforma se reserva el derecho de moderar, editar o eliminar contenido que sea ofensivo, falso, spam, o que infrinja derechos de terceros.</p>

    <h3 style="color:var(--verde);font-family:'Lora',serif;margin:22px 0 8px">3. Cuentas de usuario</h3>
    <p style="margin-bottom:18px">Eres responsable de mantener la confidencialidad de tu contraseña y de toda actividad realizada desde tu cuenta. La plataforma puede suspender o desactivar cuentas que incumplan estos términos.</p>

    <h3 style="color:var(--verde);font-family:'Lora',serif;margin:22px 0 8px">4. Información no es asesoría profesional</h3>
    <p style="margin-bottom:18px">La información sobre usos medicinales o tradicionales de las plantas tiene fines educativos y culturales. No reemplaza el consejo de un profesional de la salud.</p>

    <h3 style="color:var(--verde);font-family:'Lora',serif;margin:22px 0 8px">5. Privacidad</h3>
    <p style="margin-bottom:18px">Los datos personales suministrados (nombre y correo electrónico) se usan únicamente para el funcionamiento de la plataforma (autenticación, notificaciones y atribución de aportes) y no se comparten con terceros.</p>

    <h3 style="color:var(--verde);font-family:'Lora',serif;margin:22px 0 8px">6. Cambios en estos términos</h3>
    <p style="margin-bottom:0">Estos términos pueden actualizarse en cualquier momento. El uso continuado de la plataforma después de un cambio implica la aceptación de los nuevos términos.</p>

  </div>

    <a href="{{ url()->previous(route('home')) }}" class="back-link" style="margin-top:20px">
        <i class="fas fa-arrow-left"></i> Volver
    </a>
  </a>
</div>

@endsection