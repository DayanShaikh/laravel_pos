<div id="d-n">
    <x-layout bodyClass="g-sidenav-show  bg-gray-200">
        <x-navbars.sidebar activePage="user-management"></x-navbars.sidebar>
        <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
            <!-- Navbar -->
            <x-navbars.navs.auth titlePage="User Management"></x-navbars.navs.auth>
            <!-- End Navbar -->
            <div class="container-fluid py-4">
                <div class="card-header p-0 my-3 mx-3">
                    <form id="filters" method="GET" action="{{route('supplier.ledger', $supplier->id)}}">
                        @csrf
                        <div class="row justify-content-end text-end">
                            <div class="col-md-3">
                                <div class="input-group input-group-outline datepicker-container null is-filled">
                                    <label for="dates" class="form-label">Dates</label>
                                    <input type="text" class="form-control" id="dates" name="dates" autocomplete="off" readonly>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <button type="submit" class="btn btn-info m-0 p-2 w-100">Search</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card my-4">
                            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                                <div class="bg-gradient-info shadow-info border-radius-lg pt-4 pb-3">
                                    <h6 class="text-white mx-3">({{$supplier->name}}) Ledger</h6>
                                </div>
                            </div>
                            <div class="row my-3 mx-2">
                                <div class="col-md-6 text-start">
                                    <a onclick="window.print()" class="btn bg-gradient-dark" href="#"><i class="material-icons text-sm">print</i></a>
                                    <a href="{{ route('supplier.ledger.pdf', [$supplier->id, $format_from_date, $format_to_date]) }}" class="btn bg-gradient-dark" style="padding: 14px 25px"><i class="fa fa-file-pdf"></i></a>
                                    <button id="downloadImage" class="btn bg-gradient-dark" style="padding: 14px 25px"><i class="fa fa-image"></i></button>
                                </div>
                                <div class="col-md-6 text-end">
                                    <a href="{{route('supplier.index')}}" class="btn bg-gradient-dark" style="padding: 13px 25px"><i class="fa fa-arrow-right"></i></a>
                                </div>
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
                                                    <span class="text-secondary text-sm">{!!$ledgers->details!!}</span>
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
            <script>
                let storedStartDate = @json($format_from_date);
                let storedEndDate = @json($format_to_date);
            </script>
            <script>
                document.getElementById("downloadImage").addEventListener("click", function() {
                    const element = document.querySelector(".card-body"); // choose what you want to capture
                    html2canvas(element).then(canvas => {
                        const link = document.createElement('a');
                        link.download = 'supplier-ledger.png';
                        link.href = canvas.toDataURL("image/png");
                        link.click();
                    });
                });
            </script>
        </main>
    </x-layout>
</div>

{{-- print --}}
<style>
    .print_tab {
        display: none;
    }

    @media print {
        #d-n {
            display: none;
        }

        .print_tab {
            display: block;
        }

        h1,
        h2,
        h3,
        p {
            margin: 0 auto;
        }

        body {
            margin: 0;
            font-family: Arial;
            font-size: 11px;
        }

        .head th,
        .head td {
            border: 0;
        }

        th,
        td {
            border: solid 1px #000;
            padding: 5px 5px;
            font-size: 11px;
            vertical-align: top;
        }

        table table th,
        table table td {
            padding: 3px;
        }

        table {
            border-collapse: collapse;
            max-width: 1200px;
            margin: 0 auto;
        }
    }

</style>
<table width="100%" cellspacing="0" cellpadding="0" class="print_tab">
    <tr class="head">
        <th colspan="6">
            <h5>{!! \App\Utility::setting("site_title")!!}</h5>
            {{-- <h2>Supplier Ledger</h2> --}}
            <p><?php echo $supplier->name?></p>
        </th>
    </tr>
    <tr>
        <th width="5%" align="center">S.no</th>
        <th>Date</th>
        <th>Details</th>
        <th>Debit</th>
        <th>Credit</th>
        <th>Balance</th>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td colspan="3">Opening Balance</td>
        <td align="center">{{$supplier->balance}}</td>
    </tr>
    @php
    $balance = $credit = $debit = 0;
    @endphp
    @foreach($ledger as $ledgers)
    @php
    $credit += $ledgers->credit;
    $debit += $ledgers->debit;
    @endphp
    <tr>
        <td class="text-center"><?php echo $sn;?></td>
        <td align="center">{{\Carbon\Carbon::parse($ledgers->date)->format('d-m-Y')}}</td>
        <td align="center">{{$ledgers->details}}</td>
        <td align="center">{{$ledgers->debit}}</td>
        <td align="center">{{$ledgers->credit}}</td>
        <td align="center">{{$balance = $supplier->balance+($credit-$debit)}}</td>
    </tr>
    @endforeach
    <tr>
        <td></td>
        <td></td>
        <td colspan="3">Closing Balance</td>
        <td align="center">{{$balance}}</td>
    </tr>
</table>
