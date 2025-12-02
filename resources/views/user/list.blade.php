<x-layout bodyClass="g-sidenav-show  bg-gray-200">
    <x-navbars.sidebar activePage="user-management"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="User Management"></x-navbars.navs.auth>
        <!-- End Navbar -->
        <div class="container-fluid py-4">
            <div class="card-header p-0 my-3 mx-3">
                <form method="GET" action="{{ route('user.index') }}">
                    @csrf
                    <div class="row justify-content-end text-end">
                        <div class="col-lg-3 col-md-6"></div>
                        <div class="col-lg-4 col-md-6"></div>
                        <div class="col-lg-3 col-md-6">
                            <div class="input-group input-group-outline @if($name) null is-filled @endif">
                                <label class="form-label">Search Name</label>
                                <input type="text" class="form-control" name="name" value="{{$name}}">
                            </div>
                        </div>
                        <div class="col-lg-1 col-md-3 m-0">
                            <button type="submit" class="btn btn-info m-0">Search</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card my-4">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div class="bg-gradient-info shadow-info border-radius-lg pt-4 pb-3">
                                <h6 class="text-white mx-3">Manage User</h6>
                            </div>
                        </div>
                        @if (session()->has('message'))
                        <div class="alert alert-success alert-dismissible text-white card-header py-1 px-3 mx-3 z-index-2 mt-2" role="alert">
                            <strong>{{ session()->get('message') }}</strong>
                            <button type="button" class="btn-close text-lg py-1 opacity-10" data-bs-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        @endif
                        @can('create',App\Models\User::class)
                            <div class=" me-3 my-3 text-end">
                                <a class="btn bg-gradient-dark mb-0" href="{{ route('user.create')}}"><i class="material-icons text-sm">add</i></a>
                            </div>
                        @endcan
                        <form method="POST" action="{{ route('user.bulkAction') }}" id="myForm">
                            @csrf
                            @method('POST')
                            <input type="hidden" id="actionField" name="action">
                            <div class="card-body px-0 pb-2">
                                <div class="table-responsive p-0">
                                    <table class="table align-items-center mb-0">
                                        <thead>
                                            <tr>
                                               <th width="2%" class="align-middle text-center">
                                                    <div class="form-check check-tables">
                                                        <label class="check-wrap">
                                                            <input type="checkbox" id="select-all">
                                                            <span class="custom-box"></span>
                                                        </label>
                                                    </div>
                                                </th>
                                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">s.no</th>
                                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">name</th>
                                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Email</th>
                                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Admin Type</th>
                                                <th class="text-secondary opacity-7"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if($users->count()>0)
                                                @foreach($users as $user)
                                                    <tr>
                                                        <td class="align-middle text-center">
                                                            <div class="form-check check-tables">
                                                                <label class="check-wrap">
                                                                    <input type="checkbox" name="multidelete[]" value="{{$user->id}}">
                                                                    <span class="custom-box"></span>
                                                                </label>
                                                            </div>
                                                        </td>
                                                        <td class="align-middle text-center">
                                                            <span class="text-secondary text-xs font-weight-bold">{{$loop->index+1}}</span>
                                                        </td>
                                                        <td class="align-middle text-center">
                                                            <span class="text-secondary text-xs font-weight-bold">{{$user->name}}</span>
                                                        </td>
                                                        <td class="align-middle text-center">
                                                            <span class="text-secondary text-xs font-weight-bold">{{$user->email}}</span>
                                                        </td>
                                                        <td class="align-middle text-center">
                                                            {{-- @foreach ($users->roles as $role) --}}
                                                            <span class="text-secondary text-xs font-weight-bold">{{ implode(',', $user->userRoles()->pluck('title')->toArray()) }}</span>
                                                            {{-- @endforeach --}}
                                                        </td>
                                                        <td class="align-middle text-end px-4">
                                                            @can('update',App\Models\User::class)
                                                                <a rel="tooltip" class="btn text-success btn-link pbtn fs-6 p-2" href="{{ route('user.edit', $user->id)}}" data-original-title="" title="Edit">
                                                                    <i class="material-icons">edit</i>
                                                                    <div class="ripple-container"></div>
                                                                </a>
                                                            @endcan
                                                            @if($user->status == 0)
                                                            <a href=" {{ route('users.status', [$user->id, 1]) }}" class="btn text-danger btn-link pbtn fs-6 p-2" title="Status OFF">
                                                                <i class="material-icons">visibility_off</i>
                                                            </a>
                                                            @elseif($user->status == 1)
                                                            <a href="{{ route('users.status', [$user->id, 0]) }}" class="btn text-success btn-link pbtn fs-6 p-2" title="Status On">
                                                                <i class="material-icons">visibility</i>
                                                            </a>
                                                            @endif
                                                            @can('delete',App\Models\User::class)
                                                            <a href="javascript:void(0)" id="delete-user" data-url="{{ route('user.destroy', $user->id) }}" class="btn text-danger btn-link pbtn fs-6 p-2" title="delete">
                                                                <i class="material-icons">delete</i>
                                                                <div class="ripple-container"></div>
                                                            </a>
                                                            @endcan
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr class="text-center">
                                                    <td colspan="4">Record Not Found</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </form>
                        <div class="row text-end my-2">
                            <div class="col-md-6 d-flex h-25">
                                <div class="input-group input-group-outline is-filled form-select w-30 me-2 ms-5">
                                    <select name="action" id="action" class="form-control" onchange="confirmAndSubmit()">
                                        <option value="">Bulk Action</option>
                                        <option value="delete">Delete</option>
                                        <option value="status_on">Status ON</option>
                                        <option value="status_off">Status OFF</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="me-5 text-start ml-260">
                                    <div class="input-group input-group-outline is-filled form-select d-inline-flex w-50 float-start">
                                        <span class="my-2 mx-1">Show Page:</span>
                                        <select onchange="window.location.href=this.value" class="form-control">
                                            @for ($i = 1; $i <= $users->lastPage(); $i++)
                                                <option value="{{ $users->url($i) }}" {{ $users->currentPage() == $i ? 'selected' : '' }}>
                                                    {{ $i }}
                                                </option>
                                                @endfor
                                        </select>
                                    </div>
                                    <form action="{{ route('item.index') }}" method="get">
                                        @csrf
                                        <div class="input-group input-group-outline is-filled form-select d-inline-flex w-50">
                                            <span class="my-2 mx-1">Show Page:</span>
                                            <select name="rowsPerPage" class="form-control" id="change-row" onchange="this.form.submit()">
                                                <option value="10" {{ $rowsPerPage == 10 ? 'selected' : '' }}>10</option>
                                                <option value="25" {{ $rowsPerPage == 25 ? 'selected' : '' }}>25</option>
                                                <option value="100" {{ $rowsPerPage == 100 ? 'selected' : '' }}>100</option>
                                                <option value="1000" {{ $rowsPerPage == 1000 ? 'selected' : '' }}>1000</option>
                                            </select>
                                        </div>
                                    </form>
                                    {{$users->links()}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    {{-- <x-plugins></x-plugins> --}}
</x-layout>
