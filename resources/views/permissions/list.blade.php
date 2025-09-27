<x-layout bodyClass="g-sidenav-show bg-gray-200">
    <x-navbars.sidebar activePage="user-management"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="User Management"></x-navbars.navs.auth>
        <!-- End Navbar -->
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <form method="POST" action="{{ route('permission.bulkAction') }}">
                        @csrf
                        @method('POST')
                        <div class="card my-4">
                            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                                <div class="bg-gradient-info shadow-primary border-radius-lg pt-4 pb-3">
                                    <h6 class="text-white mx-3">Manage Permission</h6>
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
                                <a class="btn bg-gradient-dark mb-0" href="{{ route('permission.create')}}"><i class="material-icons text-sm">add</i></a>
                            </div>
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
                                                <th width="10%"class="text-center text-uppercase text-secondary text-xs font-weight-bolder">s.no</th>
                                                <th width="20%" class="text-uppercase text-secondary text-xs font-weight-bolder">Name</th>
                                                <th class="text-secondary opacity-7"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if($permission->count()>0)
                                                
                                                @foreach($permission as $permissions)
                                                <tr>
                                                    <td class="align-middle text-center">
                                                        <div class="form-check check-tables">
                                                            <input class="form-check-input" name="multidelete[]" type="checkbox" value="{{$permissions->id}}">
                                                        </div>
                                                    </td>
                                                    <td class="align-middle text-center">
                                                        <span class="text-secondary text-sm font-weight-bold">{{$sn++}}</span>
                                                    </td>
                                                    <td class="align-middle text-center">
                                                        <span class="text-secondary text-sm font-weight-bold">{{$permissions->name}}</span>
                                                    </td>
                                                    <td class="align-middle text-end px-4">
                                                        <a rel="tooltip" class="btn text-success btn-link pbtn fs-6 p-2" href="{{ route('permission.edit', $permissions->id)}}" data-original-title="" title="">
                                                            <i class="material-icons">edit</i>
                                                            <div class="ripple-container"></div>
                                                        </a>
                                                        <a href="javascript:void(0)" id="delete-user" data-url="{{ route('permission.destroy', $permissions->id) }}" class="btn text-danger btn-link pbtn fs-6 p-2" title="delete">
                                                            {{-- <i class="material-icons">close</i> --}}
                                                            <i class="fa fa-trash"></i>
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
                            <div class="row ms-5 mb-3">
                                <div class="col-2 p-0 pe-2 w-10">
                                    <div class="input-group input-group-outline is-filled form-select">
                                        <select name="action" id="action" class="form-control ps-3 py-0">
                                            <option value="">Bulk Action</option>
                                            <option value="delete">Delete</option>
                                            {{-- <option value="status_on">Status ON</option>
                                            <option value="status_off">Status OFF</option> --}}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6 p-0">
                                    <button type="submit" class="btn btn-primary bulk_btn my-1">Apply</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
    {{-- <x-plugins></x-plugins> --}}
</x-layout>
