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
    <li class="nav-item @if (!empty($menu) && $menu == 'admin_dashboard') active @endif">
        <a class="nav-link" href="{{ route('adminDashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Users -->
    <li class="nav-item @if (!empty($menu) && $menu == 'users') active @endif">
        <a class="nav-link" href="{{ route('getAllRegistedUsers') }}">
            <i class="fas fa-fw fa-users"></i>
            <span>Users</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Exam Module
    </div>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item @if (!empty($menu) && ($menu == 'exams')) active @endif">
        <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="true"
            aria-controls="collapsePages">
            <i class="fas fa-fw fa-folder"></i>
            <span>Exam management</span>
        </a>
        <div id="collapsePages" class="collapse @if (!empty($menu) && ($menu == 'exams')) show @endif" aria-labelledby="headingPages"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Exam management:</h6>
                <a class="collapse-item @if (!empty($menu) && $menu == 'exams') active @endif" href="{{ route('getExams') }}">Exams</a>
                <div class="collapse-divider"></div>
            </div>
        </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <li class="nav-item @if (!empty($menu) && $menu == 'leaderboard') active @endif">
        <a class="nav-link" href="{{ route('LeaderBoard') }}">
            <i class="fas fa-fw fa-list"></i>
            <span>Leaderboard</span></a>
    </li>

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
