<x-layout bodyClass="g-sidenav-show  bg-gray-200">
    <x-navbars.sidebar activePage="user-management"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="User Management"></x-navbars.navs.auth>
        <!-- End Navbar -->
        <div class="container-fluid py-4">
            <div class="card-header p-0 my-3 mx-3">
                <form method="GET" action="{{ route('supplier.index') }}">
                    @csrf
                    <div class="row justify-content-end text-end">
                        <div class="col-lg-3 col-md-6"></div>
                        <div class="col-lg-4 col-md-6"></div>
                        <div class="col-lg-3 col-md-6">
                            <div class="input-group input-group-outline ">
                                <label class="form-label">Search Name</label>
                                <input type="text" class="form-control" name="name" value="">
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
                                <h6 class="text-white mx-3">({{$supplier->name}}) Ledger</h6>
                            </div>
                        </div>
                        <div class=" me-3 my-3 text-end">
                            {{-- <a class="btn bg-gradient-dark mb-0" href="{{ route('supplier.create')}}"><i class="material-icons text-sm">add</i></a> --}}
                        </div>
                        <div class="card-body px-0 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <td class="align-middle text-end" colspan="5" style="padding: 0 90px;">Opening Balance</td>
                                            <td class="text-center">{{$supplier->balance}}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder">S.no</th>
                                            <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder">Date</th>
                                            <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder">Details</th>
                                            <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder">Debit</th>
                                            <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder">Credit</th>
                                            <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder">Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                        $balance = $credit = $debit = 0;
                                        @endphp
                                        @foreach($ledger as $ledgers)
                                        @php
                                        $credit += $ledgers->credit;
                                        $debit += $ledgers->debit;
                                        @endphp
                                        <tr>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-sm">{{$sn++}}</span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-sm">{{\Carbon\Carbon::parse($ledgers->date)->format('d-m-Y')}}</span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-sm">{{$ledgers->details}}</span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-sm">{{$ledgers->debit}}</span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-sm">{{$ledgers->credit}}</span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-sm">{{$balance = $supplier->balance+($credit-$debit)}}</span>
                                            </td>
                                        </tr>
                                        @endforeach
                                        <tr>
                                            <td class="align-middle text-end" colspan="5" style="padding: 0 90px;">Closing Balance</td>
                                            <td class="text-center">{{$balance}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row text-end my-2">
                            <div class="col-lg-2 col-md-6"></div>
                            <div class="col-lg-6 col-md-6">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-layout>
