<x-layout bodyClass="g-sidenav-show  bg-gray-200">
    <x-navbars.sidebar activePage="user-management"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="User Management"></x-navbars.navs.auth>
        <!-- End Navbar -->
        <div class="container-fluid py-4">
            <div class="card-body p-0 my-3 mx-3">
                <form method="GET" action="{{ route('supplier_payment.index') }}">
                    @csrf
                    <div class="row justify-content-end text-end">
                        <div class="col-md-8"></div>
                        <div class="col-md-3 d-flex align-items-center">
                            <select class="select2 w-100" name="supplier_id">
                                <option value="0" selected>Search Supplier</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{$supplier->id}}" {{$supplier_id == $supplier->id?'selected':''}}>{{$supplier->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-1">
                            <div class="m-0">
                                <button type="submit" class="btn btn-info m-0">Search</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card my-4">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div class="bg-gradient-info shadow-info border-radius-lg pt-4 pb-3">
                                <h6 class="text-white mx-3">Manage Supplier Payment</h6>
                            </div>
                        </div>
                        @if (session()->has('message'))
                        <div class="alert alert-success alert-dismissible text-white card-header px-3 p-1 mx-3 my-2 z-index-2" role="alert">
                            <strong>{{ session()->get('message') }}</strong>
                            <button type="button" class="btn-close text-lg py-1 opacity-10" data-bs-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        @endif
                        @if (session()->has('error'))
                        <div class="alert alert-danger alert-dismissible text-white card-header px-3 p-1 mx-3 my-2 z-index-2" role="alert">
                            <strong>{{ session()->get('error') }}</strong>
                            <button type="button" class="btn-close text-lg py-1 opacity-10" data-bs-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        @endif
                        <div class="me-3 my-3 text-end">
                            @can('create', App\Model\SupplierPayments::class)
                                <a class="btn bg-gradient-dark mb-0" href="{{ route('supplier_payment.create')}}"><i class="material-icons text-sm">add</i></a>
                            @endcan
                        </div>
                        <form method="POST" action="{{ route('supplier_payment.bulkAction') }}" id="myForm">
                            <input type="hidden" id="actionField" name="action">
                            @csrf
                            @method('GET')
                            <input type="hidden" id="actionField" name="action">
                            <div class="card-body px-0 pb-2">
                                <div class="table-responsive p-0">
                                    <table class="table align-items-center mb-0">
                                        <thead>
                                            <tr>
                                                <th width="2%" class="align-middle text-center">
                                                   <div class="form-check check-tables">
                                                        <label class="check-wrap">
                                                            <input type="checkbox" id="select-all" name="multidelete[]">
                                                            <span class="custom-box"></span>
                                                        </label>
                                                    </div>
                                                </th>
                                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder">s.no</th>
                                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder">Name</th>
                                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder">Date</th>
                                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder">Payment</th>
                                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder">Details</th>
                                                <th class="text-secondary opacity-7"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if($supplier_payment->count()>0)
                                                @foreach($supplier_payment as $supplier_payments)
                                                    <tr>
                                                        <td width="2%" class="align-middle text-center">
                                                            <div class="form-check check-tables">
                                                                <label class="check-wrap">
                                                                    <input type="checkbox" id="select-all" value="{{$supplier_payments->id}}">
                                                                    <span class="custom-box"></span>
                                                                </label>
                                                            </div>
                                                        </td>
                                                        <td class="align-middle text-center">
                                                            <span class="text-secondary text-sm">{{$loop->index+1}}</span>
                                                        </td>
                                                        <td class="align-middle text-center">
                                                            <span class="text-secondary text-sm">
                                                                {{$supplier_payments?->supplier?->name}}
                                                            </span>
                                                        </td>
                                                        <td class="align-middle text-center">
                                                            <span class="text-secondary text-sm">{{Carbon\Carbon::parse($supplier_payments->date)->format('d-M-Y') }}</span>
                                                        </td>
                                                        <td class="align-middle text-center">
                                                            <span class="text-secondary text-sm">{{ $supplier_payments->payment}}</span>
                                                        </td>
                                                        <td class="align-middle text-center">
                                                            <span class="text-secondary text-sm">{{ $supplier_payments->details}}</span>
                                                        </td>
                                                        <td class="align-middle text-end px-4">
                                                            @can('update', App\Model\SupplierPayments::class)
                                                                <a rel="tooltip" class="btn text-success btn-link pbtn fs-6 p-2" href="{{ route('supplier_payment.edit', $supplier_payments->id)}}" title="Edit">
                                                                    <i class="material-icons">edit</i>
                                                                    <div class="ripple-container"></div>
                                                                </a>
                                                                @if($supplier_payments->status == 0)
                                                                    <a href=" {{ route('supplier_payment_status', [$supplier_payments->id, 1]) }}" class="btn text-danger btn-link pbtn fs-6 p-2" title="Status OFF">
                                                                        <i class="material-icons">visibility_off</i>
                                                                    </a>
                                                                @elseif($supplier_payments->status == 1)
                                                                    <a href="{{ route('supplier_payment_status', [$supplier_payments->id, 0]) }}" class="btn text-success btn-link pbtn fs-6 p-2" title="Status On">
                                                                        <i class="material-icons">visibility</i>
                                                                    </a>
                                                                @endif
                                                            @endcan
                                                            @can('delete', App\Model\SupplierPayments::class)
                                                                <a href="javascript:void(0)" id="delete-user" data-url="{{ route('supplier_payment.destroy', $supplier_payments->id) }}" class="btn text-danger btn-link pbtn fs-6 p-2" title="delete">
                                                                    <i class="material-icons">delete</i>
                                                                    <div class="ripple-container"></div>
                                                                </a>
                                                            @endcan
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td class="text-center" colspan="6">No Record</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </form>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                @can('delete', App\Model\SupplierPayments::class)
                                    <div class="input-group input-group-outline is-filled form-select w-30 me-2 ms-5">
                                        <select id="action" class="form-control" onchange="confirmAndSubmit()">
                                            <option value="">Bulk Action</option>
                                            <option value="delete">Delete</option>
                                            {{-- <option value="status_on">Status ON</option>
                                                <option value="status_off">Status OFF</option> --}}
                                        </select>
                                    </div>
                                @endcan
                            </div>
                            <div class="col-md-6">
                                <div class="me-5 text-start ml-260">
                                    <div class="input-group input-group-outline is-filled form-select d-inline-flex w-50 float-start">
                                        <span class="my-2 mx-1">Show Page:</span>
                                        <select onchange="window.location.href=this.value" class="form-control">
                                            @for ($i = 1; $i <= $supplier_payment->lastPage(); $i++)
                                                <option value="{{ $supplier_payment->url($i) }}" {{ $supplier_payment->currentPage() == $i ? 'selected' : '' }}>
                                                    {{ $i }}
                                                </option>
                                                @endfor
                                        </select>
                                    </div>
                                    <form action="{{ route('supplier_payment.index') }}" method="get">
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
                                    {{$supplier_payment->links()}}
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
