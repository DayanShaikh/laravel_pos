@props(['activePage'])
<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3   bg-gradient-dark" id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0 d-flex text-wrap align-items-center" href=" {{ route('dashboard') }} ">
            <img src="{{ asset('assets') }}/img/logo-ct.png" class="navbar-brand-img h-100" alt="main_logo">
            <span class="ms-2 font-weight-bold text-white">Material Dashboard 2 Laravel</span>
        </a>
    </div>
    <hr class="horizontal light mt-0 mb-2">
    <div class="w-auto  max-height-vh-100" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs text-white font-weight-bolder opacity-8">Menu</h6>
            </li>
            @if(auth()->user()->hasrole("admin"))
            <div class="side-bar">
                <div class="menu">
                    <div class="item">
                        <a class="sub-btn"><i class="material-icons ms-2">house</i>Dashboard<i class="fas fa-angle-right dropdown"></i></a>
                        <div class="sub-menu">
                            <a href="{{ route('config.index', 1) }}" class="sub-item"><i class="material-icons">settings</i>General Settings</a>
                            <a href="{{ route('user.index') }}" class="sub-item"><i class="material-icons">person</i>User</a>
                            <a href="{{ route('role.index') }}" class="sub-item"><i class="fa fa-user-lock"></i>Role</a>
                            <a href="{{ route('permission.index') }}" class="sub-item"><i class="fa fa-lock-open"></i>Permission</a>
                        </div>
                    </div>
                </div>
            </div>
            @endif
             {!! \App\Utility::getMenu() !!}
            <li class="nav-item">
                <form method="POST" action="{{ route('logout') }}" class="d-none" id="logout-form">
                    @csrf
                </form>
                <a class="nav-link text-white " href="">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons ms-2 me-sm-1">logout</i>
                    </div>
                    <span onclick="event.preventDefault();document.getElementById('logout-form').submit();">Logout</span>
                </a>
            </li>
        </ul>
    </div>
</aside>
