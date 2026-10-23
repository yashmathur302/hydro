<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <div class="logo-header" data-background-color="dark">
            <a href="{{ route('admin.dashboard') }}" class="logo text-white fw-bold text-decoration-none">
                Semi Ecommerce
            </a>
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                    <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                    <i class="gg-menu-left"></i>
                </button>
            </div>
            <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
            </button>
        </div>
    </div>
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">
                
                <!-- Dashboard -->
                <li class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Modules</h4>
                </li>

                <!-- Pages Module -->
                <li class="nav-item {{ request()->routeIs('admin.pages.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.pages.index') }}">
                        <i class="fas fa-file-alt"></i>
                        <p>Pages</p>
                    </a>
                </li>
                <!-- Pages Module -->
                <li class="nav-item {{ request()->routeIs('#') ? 'active' : '' }}">
                    <a href="{{ route('admin.pages.index') }}">
                        <i class="fas fa-pen-nib"></i>
                        <p>Blogs</p>
                    </a>
                </li>
                <!-- Pages Module -->
                <li class="nav-item {{ request()->routeIs('#') ? 'active' : '' }}">
                    <a href="{{ route('admin.pages.index') }}">
                        <i class="fas fa-users"></i>
                        <p>Clients</p>
                    </a>
                </li>
                <!-- Pages Module -->
                <li class="nav-item {{ request()->routeIs('#') ? 'active' : '' }}">
                    <a href="{{ route('admin.pages.index') }}">
                        <i class="fas fa-tags"></i>
                        <p>Product Categories</p>
                    </a>
                </li>
                <!-- Pages Module -->
                <li class="nav-item {{ request()->routeIs('#') ? 'active' : '' }}">
                    <a href="{{ route('admin.pages.index') }}">
                        <i class="fas fa-box"></i>
                        <p>Product</p>
                    </a>
                </li>

            </ul>
        </div>
    </div>
</div>