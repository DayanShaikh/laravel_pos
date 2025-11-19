<style>
    #d-n {
        display: none;
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

</style>
<table width="100%" cellspacing="0" cellpadding="0">
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
        <td class="text-center">{{$loop->index + 1}}</td>
        <td align="center">{{\Carbon\Carbon::parse($ledgers->date)->format('d-m-Y')}}</td>
        <td align="center">{{preg_replace('/<a\b[^>]*>(.*?)<\/a>/is', '', $ledgers->details)}}</td>
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
