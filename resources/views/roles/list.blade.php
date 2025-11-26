<x-layout bodyClass="g-sidenav-show bg-gray-200">
    <x-navbars.sidebar activePage="user-management"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="User Management"></x-navbars.navs.auth>
        <!-- End Navbar -->
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card my-4">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div class="bg-gradient-info shadow-info border-radius-lg pt-4 pb-3">
                                <h6 class="text-white mx-3">Manage Roles</h6>
                            </div>
                        </div>
                        @if (session()->has('message'))
                        <div class="alert alert-success alert-dismissible text-white card-header px-3 p-1 mx-3 my-2 z-index-2" role="alert">
                            <strong>{{ session()->get('message') }}</strong>
                            {{-- <strong>This Is testing</strong> --}}
                            <button type="button" class="btn-close text-lg py-1 opacity-10" data-bs-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        @endif
                        @if (session()->has('error'))
                        <div class="alert alert-danger alert-dismissible text-white card-header px-3 p-1 mx-3 my-2 z-index-2" role="alert">
                            <strong>{{ session()->get('error') }}</strong>
                            {{-- <strong>This Is testing</strong> --}}
                            <button type="button" class="btn-close text-lg py-1 opacity-10" data-bs-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        @endif
                        <div class=" me-3 my-3 text-end">
                            <a class="btn bg-gradient-dark mb-0" href="{{ route('role.create')}}"><i class="material-icons text-sm">add</i></a>
                        </div>
                        <form method="POST" action="{{ route('role.bulkAction') }}" id="myForm">
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
                                                        <input class="form-check-input" id="select-all" type="checkbox" name="" value="">
                                                    </div>
                                                </th>
                                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder">s.no</th>
                                                <th class="text-left text-uppercase text-secondary text-xs font-weight-bolder">Name</th>
                                                <th class="text-secondary opacity-7"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if($role->count()>0)
                                            @foreach($role as $roles)
                                            <tr>
                                                <td class="align-middle text-center">
                                                    <div class="form-check check-tables">
                                                        <input class="form-check-input" name="multidelete[]" type="checkbox" value="{{$roles->id}}">
                                                    </div>
                                                </td>
                                                <td class="align-middle text-center">
                                                    <span class="text-secondary text-sm">{{$loop->index+1}}</span>
                                                </td>
                                                <td class="align-middle text-left">
                                                    <span class="text-secondary text-sm">{{$roles->title}}</span>
                                                </td>
                                                <td class="align-middle text-end px-4">
                                                    <a rel="tooltip" class="btn text-success btn-link pbtn fs-6 p-2" href="{{ route('role.edit', $roles->id)}}" data-original-title="" title="">
                                                        <i class="material-icons">edit</i>
                                                        <div class="ripple-container"></div>
                                                    </a>
                                                    <a href="javascript:void(0)" id="delete-user" data-url="{{ route('role.destroy', $roles->id) }}" class="btn text-danger btn-link pbtn fs-6 p-2" title="delete">
                                                        <i class="material-icons">delete</i>
                                                        <div class="ripple-container"></div>
                                                    </a>
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
                        <div class="row my-3">
                            <div class="col-md-6">
                                <div class="input-group input-group-outline is-filled form-select w-30 me-2 ms-5">
                                    <select name="action" id="action" class="form-control" onchange="confirmAndSubmit()">
                                        <option value="">Bulk Action</option>
                                        <option value="delete">Delete</option>
                                        {{-- <option value="status_on">Status ON</option>
                                                <option value="status_off">Status OFF</option> --}}
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="me-5 text-start ml-260">
                                    <div class="input-group input-group-outline is-filled form-select d-inline-flex w-50 float-start">
                                        <span class="my-2 mx-1">Show Page:</span>
                                        <select onchange="window.location.href=this.value" class="form-control">
                                            @for ($i = 1; $i <= $role->lastPage(); $i++)
                                                <option value="{{ $role->url($i) }}" {{ $role->currentPage() == $i ? 'selected' : '' }}>
                                                    {{ $i }}
                                                </option>
                                                @endfor
                                        </select>
                                    </div>
                                    <form action="{{ route('customer.index') }}" method="get">
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
                                    {{$role->links()}}
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
