<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
        @if($settings['logo_light'] ?? null)
            <img src="{{ $settings['logo_light'] }}" alt="{{ $settings['website_name'] ?? 'Logo' }}" style="max-height: 40px; max-width: 150px; margin-right: 10px;">
        @else
            <div class="sidebar-brand-icon rotate-n-15">
                <i class="fas fa-laugh-wink"></i>
            </div>
        @endif
        <div class="sidebar-brand-text mx-3">
            @php
                $websiteName = $settings['website_name'] ?? '';
                $firstWord = explode(' ', $websiteName)[0];
            @endphp
            {{ $firstWord }}
        </div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Nav Item - Tools -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.tools.index') }}">
            <i class="fas fa-fw fa-tools"></i>
            <span>Tools Management</span></a>
    </li>

    <!-- Nav Item - Portfolio -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.portfolios.index') }}">
            <i class="fas fa-fw fa-images"></i>
            <span>Portfolio Management</span></a>
    </li>

    <!-- Nav Item - Settings -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.settings.index') }}">
            <i class="fas fa-fw fa-cog"></i>
            <span>Settings</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

    <!-- Sidebar Message -->

</ul>
<!-- End of Sidebar -->