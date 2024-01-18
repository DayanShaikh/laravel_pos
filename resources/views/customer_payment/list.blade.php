<x-layout bodyClass="g-sidenav-show  bg-gray-200">
    <x-navbars.sidebar activePage="user-management"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="User Management"></x-navbars.navs.auth>
        <!-- End Navbar -->
        <div class="container-fluid py-4">
            <div class="card-header p-0 my-3 mx-3">
                <form method="GET" action="{{ route('customer.index') }}">
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
                            <button type="submit" class="btn btn-primary m-0">Search</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="row">
                <div class="col-12">

                    <div class="card my-4">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                                <h6 class="text-white mx-3">Manage Customer Payment</h6>
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
                            <a class="btn bg-gradient-dark mb-0" href="{{ route('customer_payment.create')}}"><i class="material-icons text-sm">add</i></a>
                        </div>
                        <form method="POST" action="{{ route('customer_payment.bulkAction') }}" id="myForm">
                            @csrf
                            @method('POST')
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
                                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder">Name</th>
                                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder">Date</th>
                                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder">Payment</th>
                                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder">Details</th>
                                                <th class="text-secondary opacity-7"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($customer_payment as $customer_payments)
                                            <tr>
                                                <td class="align-middle text-center">
                                                    <div class="form-check check-tables">
                                                        <input class="form-check-input" name="multidelete[]" type="checkbox" value="{{$customer_payments->id}}">
                                                    </div>
                                                </td>
                                                <td class="align-middle text-center">
                                                    <span class="text-secondary text-sm">{{$sn++}}</span>
                                                </td>
                                                <td class="align-middle text-center">
                                                    <span class="text-secondary text-sm">
                                                        {{--@if ($customer->id == $customer_payments->customer_id)--}}
                                                        {{$customer_payments->customer->name}}
                                                     {{--@endif--}}
                                                </span>
                                                </td>
                                                <td class="align-middle text-center">
                                                    <span class="text-secondary text-sm">{{ $customer_payments->date}}</span>
                                                </td>
                                                <td class="align-middle text-center">
                                                    <span class="text-secondary text-sm">{{ $customer_payments->payment}}</span>
                                                </td>
                                                <td class="align-middle text-center">
                                                    <span class="text-secondary text-sm">{{ $customer_payments->details}}</span>
                                                </td>
                                                <td class="align-middle text-end px-4">
                                                    {{-- <a href="{{ route('customer.ledger', $customers->id) }}" class="btn text-success btn-link pbtn fs-6 p-2" title="Ledger">
                                                        <i class="fa fa-print"></i>
                                                    </a> --}}
                                                    <a rel="tooltip" class="btn text-success btn-link pbtn fs-6 p-2" href="{{ route('customer_payment.edit', $customer_payments->id)}}" title="Edit">
                                                        <i class="material-icons">edit</i>
                                                        <div class="ripple-container"></div>
                                                    </a>
                                                    @if($customer_payments->status == 0)
                                                    <a href=" {{ route('customer_payment_status', [$customer_payments->id, 1]) }}" class="btn text-danger btn-link pbtn fs-6 p-2" title="Status OFF">
                                                        <i class="fa fa-eye-slash"></i>
                                                    </a>
                                                    @elseif($customer_payments->status == 1)
                                                    <a href="{{ route('customer_payment_status', [$customer_payments->id, 0]) }}" class="btn text-success btn-link pbtn fs-6 p-2" title="Status On">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                    @endif
                                                    <a href="javascript:void(0)" id="delete-user" data-url="{{ route('customer_payment.destroy', $customer_payments->id) }}" class="btn text-danger btn-link pbtn fs-6 p-2" title="delete">
                                                        <i class="fa fa-trash"></i>
                                                        <div class="ripple-container"></div>
                                                    </a>
                                                </td>
                                            </tr>
                                            @endforeach
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
                            <div class="me-5 text-start ml-260 ">
                                <div class="input-group input-group-outline is-filled form-select d-inline-flex w-40 float-start ">
                                    <span class="my-2 mx-1 w-100">Show Page:</span>
                                    <select onchange="window.location.href=this.value" class="form-control">
                                        @for ($i = 1; $i <= $customer_payment->lastPage(); $i++)
                                            <option value="{{ $customer_payment->url($i) }}" {{ $customer_payment->currentPage() == $i ? 'selected' : '' }}>
                                                {{ $i }}
                                            </option>
                                            @endfor
                                    </select>
                                </div>
                                <form action="{{ route('customer.index') }}" method="get">
                                    @csrf
                                    <div class="input-group input-group-outline is-filled form-select d-inline-flex w-50 ">
                                        <span class="my-2 mx-1 w-100">Show Page:</span>
                                        <select name="rowsPerPage" class="form-control" id="change-row" onchange="this.form.submit()">
                                            <option value="10" {{ $rowsPerPage == 10 ? 'selected' : '' }}>10</option>
                                            <option value="25" {{ $rowsPerPage == 25 ? 'selected' : '' }}>25</option>
                                            <option value="100" {{ $rowsPerPage == 100 ? 'selected' : '' }}>100</option>
                                            <option value="1000" {{ $rowsPerPage == 1000 ? 'selected' : '' }}>1000</option>
                                        </select>
                                    </div>
                                </form>
                                {{$customer_payment->links()}}
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
