<x-layout bodyClass="g-sidenav-show  bg-gray-200">
    <x-navbars.sidebar activePage="user-management"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="User Management"></x-navbars.navs.auth>
        <!-- End Navbar -->
        <div class="container-fluid py-2 px-2">
            <div class="card-header p-0 my-3 mx-3">
                <form method="GET" action="">
                    @csrf
                    <div class="row justify-content-end text-end">
                        <div class="col-lg-2 col-md-6">
                            <div class="input-group input-group-outline datepicker-container">
                                {{-- <label for="datepicker" class="form-label">from date</label> --}}
                                <input type="text" class="form-control" id="dateRangePicker1" name="from_date" placeholder="From date" value="{{$from_date??''}}" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6">
                            <div class="input-group input-group-outline datepicker-container">
                                {{-- <label for="datepicker" class="form-label">To date</label> --}}
                                <input type="text" class="form-control" id="dateRangePicker2" name="to_date" placeholder="To date" value="{{$to_date??''}}" autocomplete="off">
                            </div>
                        </div>
                        {{-- <div class="col-lg-2 col-md-6">
                            <div class="input-group input-group-outline ">
                                <label class="form-label">Search Name</label>
                                <input type="text" class="form-control" name="q" value="">
                            </div>
                        </div> --}}
                        <div class="col-lg-2 col-md-6">
                            <div class="input-group input-group-outline is-filled form-select w-100 h-100">
                                <select name="supplier_id" id="action" class="form-control">
                                    <option value="0">Select Suppliers</option>
                                    @foreach($suppliers as $supplier)
                                    <option value="{{$supplier->id}}" @if($supplier_id==$supplier->id) selected @endif>{{$supplier->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        {{-- <div class="col-lg-2 col-md-6"></div>
                        <div class="col-lg-1 col-md-6"></div> --}}
                        <div class="col-lg-1 col-md-3 ">
                            <button type="submit" class="btn btn-primary m-0 p-2 w-100">Search</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card my-4">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                                <h6 class="text-white mx-3">Manage Purchase</h6>
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
                        <div class=" me-3 my-3 text-end">
                            <a class="btn bg-gradient-dark mb-0" href="{{ route('purchase.create')}}"><i class="material-icons text-sm">add</i></a>
                            <a class="btn bg-gradient-dark mb-0" href="{{ route('purchase.return')}}">Purchase Return</a>
                        </div>
                        <form method="POST" action="{{route('purchase.bulkAction')}}" id="myForm">
                            @csrf
                            @method('POST')
                            <div class="card-body">
                                <div class="table-responsive p-0">
                                    <table class="table align-items-center mb-0">
                                        <thead>
                                            <tr>
                                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder display-1">S.no</th>
                                                <th width="2%" class="align-middle text-center">
                                                    <div class="form-check check-tables">
                                                        <input class="form-check-input" id="select-all" type="checkbox" name="" value="">
                                                    </div>
                                                </th>
                                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder display-1">ID</th>
                                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder display-1">Date</th>
                                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder display-1">Supplier</th>
                                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder display-1">Total Price</th>
                                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder display-1">Discount</th>
                                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder display-1">Net Price</th>
                                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder display-1"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if($purchase->count()>0)
                                                @foreach($purchase as $purchases)
                                                    <tr>
                                                        <td class="align-middle text-center">
                                                            <span class="text-secondary text-sm">{{$loop->index ++}}</span>
                                                        </td>
                                                        <td class="align-middle text-center">
                                                            <div class="form-check check-tables">
                                                                <input class="form-check-input" name="multidelete[]" type="checkbox" value="{{$purchases->id}}">
                                                            </div>
                                                        </td>
                                                        <td class="align-middle text-center">
                                                            <span class="text-secondary text-sm">{{$purchases->id}}</span>
                                                        </td>
                                                        <td class="align-middle text-center">
                                                            <span class="text-secondary text-sm">{{\Carbon\Carbon::parse($purchases->date)->format('d-m-Y')}}</span>
                                                        </td>
                                                        <td class="align-middle text-center">
                                                            <span class="text-secondary text-sm">
                                                                {{!empty($purchases->supplier)? $purchases->supplier->name:'Select Supplier'}}
                                                            </span>
                                                        </td>
                                                        <td class="align-middle text-center">
                                                            <span class="text-secondary text-sm">{{$purchases->total_price}}</span>
                                                        </td>
                                                        <td class="align-middle text-center">
                                                            <span class="text-secondary text-sm">{{$purchases->discount}}</span>
                                                        </td>
                                                        <td class="align-middle text-center">
                                                            <span class="text-secondary text-sm">{{$purchases->net_price}}</span>
                                                        </td>
                                                        <td class="align-middle text-end px-4">
                                                            <a rel="tooltip" class="btn text-success btn-link pbtn fs-6 p-2" href="{{ route('purchase.edit', $purchases->id)}}" title="Edit">
                                                                <i class="material-icons">edit</i>
                                                                <div class="ripple-container"></div>
                                                            </a>
                                                            @if($purchases->status == 0)
                                                            <a href=" {{ route('purchase.status', [$purchases->id, 1]) }}" class="btn text-danger btn-link pbtn fs-6 p-2" title="Status OFF">
                                                                <i class="fa fa-eye-slash"></i>
                                                            </a>
                                                            @elseif($purchases->status == 1)
                                                            <a href="{{ route('purchase.status', [$purchases->id, 0]) }}" class="btn text-success btn-link pbtn fs-6 p-2" title="Status On">
                                                                <i class="fa fa-eye"></i>
                                                            </a>
                                                            @endif
                                                            <a href="javascript:void(0)" id="delete-user" data-url="{{ route('purchase.delete', $purchases->id) }}" class="btn text-danger btn-link pbtn fs-6 p-2" title="delete">
                                                                <i class="fa fa-trash"></i>
                                                                <div class="ripple-container"></div>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                @else
                                                <tr class="text-center">
                                                    <td colspan="8">Record Not Found</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row text-end my-2">
                                <div class="col-lg-4 col-md-6 d-flex h-25">
                                    <div class="input-group input-group-outline is-filled form-select w-30 me-2 ms-5 h-100">
                                        <select name="action" id="action" class="form-control" onchange="confirmAndSubmit()">
                                            <option value="">Bulk Action</option>
                                            <option value="delete">Delete</option>
                                            <option value="status_on">Status ON</option>
                                            <option value="status_off">Status OFF</option>
                                        </select>
                                    </div>
                                    {{-- <button type="submit" class="btn btn-primary bulk_btn my-1">Apply</button> --}}
                                </div>
                        </form>
                        <div class="col-lg-2 col-md-6"></div>
                        <div class="col-lg-6 col-md-6">
                            <div class="me-5 text-start ml-260">
                                <div class="input-group input-group-outline is-filled form-select d-inline-flex w-40 float-start">
                                    <span class="my-2 mx-1">Show Page:</span>
                                    <select onchange="window.location.href=this.value" class="form-control">
                                        @for ($i = 1; $i <= $purchase->lastPage(); $i++)
                                            <option value="{{ $purchase->url($i) }}" {{ $purchase->currentPage() == $i ? 'selected' : '' }}>
                                                {{ $i }}
                                            </option>
                                            @endfor
                                    </select>
                                </div>
                                <form action="{{ route('purchase.index') }}" method="get">
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
                                {{$purchase->links()}}
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
