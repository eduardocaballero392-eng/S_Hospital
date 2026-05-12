{{-- Barra lateral: Reclamaciones solo visible dentro de la sección Pacientes --}}
@php
    $enSeccionPacientes = request()->routeIs('admin.pacientes.*', 'admin.reclamaciones.*');
@endphp
<aside class="admin-sidebar">
    <div class="sidebar-header">
        <div class="logo">
            <div class="logo-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.5">
                    <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>
                    <circle cx="12" cy="12" r="3"/>
                </svg>
            </div>
            <div class="logo-text">
                <span class="logo-main">E&M</span>
                <span class="logo-sub">Admin</span>
            </div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fas fa-chart-line"></i>
            <span>Dashboard</span>
        </a>

        @if($enSeccionPacientes)
            <a href="{{ route('admin.pacientes.index') }}" class="nav-item {{ request()->routeIs('admin.pacientes.*') && !request()->routeIs('admin.reclamaciones.*') ? 'active' : '' }}">
                <i class="fas fa-users"></i>
                <span>Pacientes</span>
            </a>
            <a href="{{ route('admin.reclamaciones.index') }}" class="nav-item nav-sub {{ request()->routeIs('admin.reclamaciones.*') ? 'active' : '' }}">
                <i class="fas fa-file-alt"></i>
                <span>Reclamaciones</span>
            </a>
        @else
            <a href="{{ route('admin.pacientes.index') }}" class="nav-item {{ request()->routeIs('admin.pacientes.*') ? 'active' : '' }}">
                <i class="fas fa-users"></i>
                <span>Pacientes</span>
            </a>
        @endif

        <a href="{{ route('admin.medicos.index') }}" class="nav-item {{ request()->routeIs('admin.medicos.*') ? 'active' : '' }}">
            <i class="fas fa-user-md"></i>
            <span>Médicos</span>
        </a>
        <a href="{{ route('admin.citas.index') }}" class="nav-item {{ request()->routeIs('admin.citas.*') ? 'active' : '' }}">
            <i class="fas fa-calendar-alt"></i>
            <span>Citas</span>
        </a>
        <a href="{{ route('admin.usuarios.index') }}" class="nav-item {{ request()->routeIs('admin.usuarios.*') ? 'active' : '' }}">
            <i class="fas fa-user-cog"></i>
            <span>Usuarios</span>
        </a>
    </nav>

    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i>
                <span>Cerrar sesión</span>
            </button>
        </form>
    </div>
</aside>
