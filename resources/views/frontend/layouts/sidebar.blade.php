<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-smile"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Skill Test</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item @if (!empty($menu) && $menu == 'user_dashboard') active @endif">
        <a class="nav-link" href="{{ route('userDashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Quiz Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <li class="nav-item @if (!empty($menu) && $menu == 'user_leaderboard') active @endif">
        <a class="nav-link" href="{{ route('userLeaderBoard') }}">
            <i class="fas fa-fw fa-list"></i>
            <span>Leaderboard</span></a>
    </li>

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
