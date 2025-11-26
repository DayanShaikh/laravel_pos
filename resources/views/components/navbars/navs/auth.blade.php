@props(['titlePage'])
<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" navbar-scroll="true">
    <div class="container-fluid py-1 px-3">
        <div class="col-md-6"></div>
        <div class="col-md-6" style="padding: 0px 20px;">
            <div class="mt-2 mt-sm-0 me-md-0 me-sm-4 me-sm-4 d-contents" id="navbar">
                <ul class="navbar-nav justify-content-end">
                    <li class="nav-item dropdown">
                        <a class="dropdown-toggle d-flex align-items-center py-0 text-body" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-user me-sm-1"></i><strong>{{ Auth::user()->name}}</strong>
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="d-none" id="logout-form">
                            @csrf
                        </form>
                        <ul class="dropdown-menu">
                            @if(auth()->user()->hasrole("admin"))
                            <li class="nav-item d-flex align-items-center"><a href="{{ route('user.edit', Auth::user()->id)}}" class="px-0 nav-link font-weight-bold lh-1 d-flex align-items-center"><i class="material-icons ms-2 me-sm-1"> account_circle </i> My Profile </a></li>
                            <hr class="m-0">
                            @endif
                            <li class="nav-item d-flex align-items-center"><a onclick="event.preventDefault();document.getElementById('logout-form').submit();" href="" class="px-0 nav-link font-weight-bold lh-1 d-flex align-items-center"><i class="material-icons ms-2 me-sm-1">logout</i> Logout </a></li>
                        </ul>
                    </li>
                    <li class="nav-item d-xl-none ps-3 d-flex align-items-center"><a href="#" class="p-0 nav-link text-body lh-1" id="iconNavbarSidenav">
                            <div class="sidenav-toggler-inner"><i class="sidenav-toggler-line"></i><i class="sidenav-toggler-line"></i><i class="sidenav-toggler-line"></i></div>
                        </a></li>
                    <li class="px-3 nav-item d-flex align-items-center"><a href="{{ route('config.index', 1)}}" title="Software Settings" class="p-0 nav-link lh-1 text-body"><i class="fa-solid fa-gear cursor-pointer"></i></a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>
