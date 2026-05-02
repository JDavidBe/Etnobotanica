<header id="main-hdr">
  <div id="hdr-marca" class="marca">
    <a href="{{ route('home') }}" class="marca-link">
      <div class="marca-ico"><i class="fas fa-leaf"></i></div>
      <div class="marca-txt">
        <strong>Etnobotánica</strong>
        <span>Fusagasugá · Cundinamarca</span>
      </div>
    </a>
  </div>

  {{-- Breadcrumbs (solo en vistas internas) --}}
  @hasSection('breadcrumbs')
  <div id="hdr-bc" class="breadcrumbs">
    <a href="{{ route('home') }}" class="crumb"><i class="fas fa-home"></i> Inicio</a>
    @yield('breadcrumbs')
  </div>
  @endif

  <div class="search-wrap">
    <i class="fas fa-search"></i>
    <input type="text"
           id="q-search"
           placeholder="Buscar planta…"
           autocomplete="off"
           hx-get="{{ route('plantas.buscar') }}"
           hx-trigger="input changed delay:300ms"
           hx-target="#search-results"
           hx-push-url="true">
  </div>

  <div class="hdr-right">
    <button class="theme-btn" id="theme-btn" onclick="toggleTheme()" title="Claro / Oscuro">
      <i class="fas fa-moon"></i>
    </button>
    <a href="{{ route('aportar') }}" class="btn-hdr btn-aportar">
      <i class="fas fa-plus"></i> Aportar
    </a>
    @auth
      @if(auth()->user()->hasAnyRole(['admin', 'moderador']))
        <a href="{{ route('admin.dashboard') }}" class="btn-hdr btn-admin">
          <i class="fas fa-th-large"></i> Admin
        </a>
      @endif
      <form method="POST" action="{{ route('logout') }}" style="display:inline">
        @csrf
        <button type="submit" class="btn-hdr" title="Cerrar sesión" style="background:var(--rojo-light);color:white">
          <i class="fas fa-sign-out-alt"></i> Salir
        </button>
      </form>
    @else
      <a href="{{ route('login') }}" class="btn-hdr btn-admin">
        <i class="fas fa-sign-in-alt"></i> Iniciar sesión
      </a>
    @endauth
  </div>
</header>
