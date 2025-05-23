@props(['titlePage'])

<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" navbar-scroll="true">
    <div class="container-fluid py-1 px-3">
        {{-- <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
                <li class="breadcrumb-item text-sm text-dark active" aria-current="page">{{ $titlePage }}</li>
        </ol>
        <h6 class="font-weight-bolder mb-0">{{ $titlePage }}</h6>
</nav> --}}
<div class="mt-2 mt-sm-0 me-md-0 me-sm-4 me-sm-4 d-contents" id="navbar">
    <div class="pe-md-3 d-flex align-items-center ms-md-auto">
        {{-- <div class="input-group input-group-outline null"><label class="form-label text-black">Search</label><input id="search" type="text" class="form-control form-control-default" name="" placeholder="" isrequired="false"></div> --}}
    </div>
    <ul class="navbar-nav justify-content-end">
        <li class="nav-item dropdown">
            <a class="dropdown-toggle d-flex align-items-center py-0 text-body" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="material-icons me-sm-1">account_circle</i><strong>{{ Auth::user()->name}}</strong>
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
        <li class="px-3 nav-item d-flex align-items-center"><a href="{{ route('config.index', 1)}}" title="Software Settings" class="p-0 nav-link lh-1 text-body"><i class="material-icons cursor-pointer"> settings </i></a></li>
        <li class="nav-item dropdown d-flex align-items-center pe-2"><a href="#" class="p-0 nav-link lh-1 text-body" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons cursor-pointer"> notifications </i></a>
            <ul class="px-2 py-3 dropdown-menu dropdown-menu-end me-sm-n4" aria-labelledby="dropdownMenuButton">
                <li class="mb-2"><a class="dropdown-item border-radius-md" href="javascript:;">
                        <div class="py-1 d-flex">
                            <div class="my-auto"><img src="/img/team-2.90c40d0c.jpg" class="avatar avatar-sm me-3" alt="user image"></div>
                            <div class="d-flex flex-column justify-content-center">
                                <h6 class="mb-1 text-sm font-weight-normal"><span class="font-weight-bold">New message</span> from Laur </h6>
                                <p class="mb-0 text-xs text-secondary"><i class="fa fa-clock me-1" aria-hidden="true"></i> 13 minutes ago </p>
                            </div>
                        </div>
                    </a></li>
                <li class="mb-2"><a class="dropdown-item border-radius-md" href="javascript:;">
                        <div class="py-1 d-flex">
                            <div class="my-auto"><img src="/img/logo-spotify.b0a5df30.svg" class="avatar avatar-sm bg-gradient-dark me-3" alt="logo spotify"></div>
                            <div class="d-flex flex-column justify-content-center">
                                <h6 class="mb-1 text-sm font-weight-normal"><span class="font-weight-bold">New album</span> by Travis Scott </h6>
                                <p class="mb-0 text-xs text-secondary"><i class="fa fa-clock me-1" aria-hidden="true"></i> 1 day </p>
                            </div>
                        </div>
                    </a></li>
                <li><a class="dropdown-item border-radius-md" href="javascript:;">
                        <div class="py-1 d-flex">
                            <div class="my-auto avatar avatar-sm bg-gradient-secondary me-3"><svg width="12px" height="12px" viewBox="0 0 43 36" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                    <title>credit-card</title>
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <g transform="translate(-2169.000000, -745.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                            <g transform="translate(1716.000000, 291.000000)">
                                                <g transform="translate(453.000000, 454.000000)">
                                                    <path class="color-background" d="M43,10.7482083 L43,3.58333333 C43,1.60354167 41.3964583,0 39.4166667,0 L3.58333333,0 C1.60354167,0 0,1.60354167 0,3.58333333 L0,10.7482083 L43,10.7482083 Z" opacity="0.593633743"></path>
                                                    <path class="color-background" d="M0,16.125 L0,32.25 C0,34.2297917 1.60354167,35.8333333 3.58333333,35.8333333 L39.4166667,35.8333333 C41.3964583,35.8333333 43,34.2297917 43,32.25 L43,16.125 L0,16.125 Z M19.7083333,26.875 L7.16666667,26.875 L7.16666667,23.2916667 L19.7083333,23.2916667 L19.7083333,26.875 Z M35.8333333,26.875 L28.6666667,26.875 L28.6666667,23.2916667 L35.8333333,23.2916667 L35.8333333,26.875 Z"></path>
                                                </g>
                                            </g>
                                        </g>
                                    </g>
                                </svg></div>
                            <div class="d-flex flex-column justify-content-center">
                                <h6 class="mb-1 text-sm font-weight-normal"> Payment successfully completed </h6>
                                <p class="mb-0 text-xs text-secondary"><i class="fa fa-clock me-1" aria-hidden="true"></i> 2 days </p>
                            </div>
                        </div>
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</div>
</div>
</nav>
