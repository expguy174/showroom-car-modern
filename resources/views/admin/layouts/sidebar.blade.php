<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Logo + Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('admin.dashboard') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-car"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Showroom Admin</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Dashboard -->
    <li class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Hãng xe -->
    <div class="sidebar-heading">Car Brands</div>

    <li class="nav-item {{ request()->routeIs('admin.cars.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.cars.index') }}">
            <i class="fas fa-warehouse"></i>
            <span>Car Brands</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Car Management -->
    <div class="sidebar-heading">Car Management</div>

    <li class="nav-item {{ request()->routeIs('admin.carmodels.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.carmodels.index') }}">
            <i class="fas fa-car-side"></i>
            <span>Car Models</span>
        </a>
    </li>

    <li class="nav-item {{ request()->routeIs('admin.carvariants.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.carvariants.index') }}">
            <i class="fas fa-layer-group"></i>
            <span>Car Variants</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Sales -->
    <div class="sidebar-heading">Orders & Products</div>

    <li class="nav-item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.orders.index') }}">
            <i class="fas fa-receipt"></i>
            <span>Orders</span>
        </a>
    </li>

    <li class="nav-item {{ request()->routeIs('admin.accessories.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.accessories.index') }}">
            <i class="fas fa-tools"></i>
            <span>Accessories</span>
        </a>
    </li>

    {{-- Inventory module removed --}}

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Blog & User -->
    <div class="sidebar-heading">Content</div>

    <li class="nav-item {{ request()->routeIs('admin.blogs.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.blogs.index') }}">
            <i class="fas fa-blog"></i>
            <span>Blogs</span>
        </a>
    </li>

    <li class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.users.index') }}">
            <i class="fas fa-users"></i>
            <span>Users</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Logout (Optional quick link) -->
    <li class="nav-item">
        <form action="{{ route('logout') }}" method="POST" class="nav-link px-3">
            @csrf
            <button type="submit" class="btn btn-danger btn-sm w-100 text-left">
                <i class="fas fa-sign-out-alt"></i> Logout
            </button>
        </form>
    </li>

</ul>
<!-- End of Sidebar -->