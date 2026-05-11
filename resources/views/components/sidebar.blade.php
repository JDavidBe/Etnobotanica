<aside class="sidebar">
  <div class="sb-top">
    <h2>🌿 Panel Admin</h2>
    <p>Etnobotánica Fusagasugá</p>
  </div>
  <nav>
    <div class="nav-lbl">Principal</div>
    @if(auth()->user()->hasAnyRole(['admin', 'moderador']))
      <a href="{{ route('admin.dashboard') }}"
         class="nav-item {{ request()->routeIs('admin.dashboard') ? 'on' : '' }}">
        <i class="fas fa-th-large"></i> Dashboard
      </a>
    @endif

    <div class="nav-lbl">Contenido</div>
    <a href="{{ route('admin.plantas.index') }}"
       class="nav-item {{ request()->routeIs('admin.plantas.*') ? 'on' : '' }}">
      <i class="fas fa-seedling"></i> Plantas
    </a>
    <a href="{{ route('admin.categorias.index') }}"
       class="nav-item {{ request()->routeIs('admin.categorias.*') ? 'on' : '' }}">
      <i class="fas fa-folder"></i> Categorías
    </a>
    <a href="{{ route('admin.subtemas.index') }}"
       class="nav-item {{ request()->routeIs('admin.subtemas.*') ? 'on' : '' }}">
      <i class="fas fa-tags"></i> Subtemas
    </a>
    <a href="{{ route('admin.moderacion.index') }}"
       class="nav-item {{ request()->routeIs('admin.moderacion.*') ? 'on' : '' }}">
      <i class="fas fa-clipboard-check"></i> Moderación
      @php
        $pendientesCount = isset($pendientes) && is_countable($pendientes)
          ? count($pendientes)
          : ($pendientes ?? 0);
      @endphp
      @if($pendientesCount > 0)
        <span class="nav-badge" style="background:var(--warning)">{{ $pendientesCount }}</span>
      @endif
    </a>

    <div class="nav-lbl">Gestión</div>
    <a href="{{ route('admin.usuarios.index') }}"
       class="nav-item {{ request()->routeIs('admin.usuarios.*') ? 'on' : '' }}">
      <i class="fas fa-users"></i> Usuarios
    </a>
    <a href="{{ route('admin.reportes') }}"
       class="nav-item {{ request()->routeIs('admin.reportes') ? 'on' : '' }}">
      <i class="fas fa-chart-bar"></i> Reportes
    </a>
    <a href="{{ route('admin.auditoria') }}"
       class="nav-item {{ request()->routeIs('admin.auditoria') ? 'on' : '' }}">
      <i class="fas fa-history"></i> Auditoría
    </a>

    <div class="nav-lbl">Sistema</div>
    <a href="{{ route('admin.config') }}"
       class="nav-item {{ request()->routeIs('admin.config') ? 'on' : '' }}">
      <i class="fas fa-cog"></i> Configuración
    </a>
  </nav>
</aside>
