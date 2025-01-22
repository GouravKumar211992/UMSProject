<tbody>
    @php  
        use App\Helpers\Helper;  
        $totalDebit=0;
        $totalCredit=0;
    @endphp 
    @foreach ($data as $voucher)
        @php
            $currentDebit=0;
            $currentCredit=0;
        @endphp
        <tr>
            <td>{{ date('d-m-Y',strtotime($voucher->date)) }}</td>
            <td>
                <table class="table mt-1 ledgersub-detailsnew">
                    @foreach ($voucher->items as $item)
                        @if ($item->ledger_id==$id)
                            @php
                                $totalDebit=$totalDebit+$item->debit_amt;
                                $totalCredit=$totalCredit+$item->credit_amt;
                                $currentDebit=$item->debit_amt;
                                $currentCredit=$item->credit_amt;
                            @endphp
                        @else
                            @php
                                $currentBalance = $item->debit_amt - $item->credit_amt;
                                $currentBalanceType = $currentBalance >= 0 ? 'Dr' : 'Cr';
                                $currentBalance = abs($currentBalance);
                            @endphp
                            <tr> 
                                <td  style="font-weight: bold; color:black;">{{ $item->ledger->name }}</td>
                                <td>{{ $currentBalance }} {{ $currentBalanceType }}</td> 
                            </tr>
                        @endif
                    @endforeach
                </table>
            </td>
            <td>
                <a href="{{ route('vouchers.edit', ['voucher' => $voucher->id]) }}">
                    {{ $voucher->voucher_name }}
                </a>
            </td>
            <td>{{ $voucher->documents['name'] }}</td>
            <td>{{ $voucher->voucher_no }}</td>
            <td>{{ Helper::formatIndianNumber($currentDebit) }}</td>
            <td>{{ Helper::formatIndianNumber($currentCredit) }}</td>
        </tr>
    @endforeach
</tbody>                

<tfoot>
    <tr class="ledfootnobg">
        <td colspan="5" class="text-end">Current Total</td>
        <td>{{ Helper::formatIndianNumber($totalDebit) }}</td>
        <td>{{ Helper::formatIndianNumber($totalCredit) }}</td>
    </tr>
    <tr class="ledfootnobg">
        <td colspan="5" class="text-end">Opening Balance</td>
        <td>@if($opening && $opening->opening_type=="Dr") {{ Helper::formatIndianNumber($opening->opening) }} @endif</td>
        <td>@if($opening && $opening->opening_type=="Cr") {{ Helper::formatIndianNumber($opening->opening) }} @endif</td>
    </tr>
    <td colspan="5" class="text-end">Closing Balance</td>
    <td>@if($closing && $closing->closing_type=="Dr") {{ Helper::formatIndianNumber($closing->closing) }} @endif</td>
    <td>@if($closing && $closing->closing_type=="Cr") {{ Helper::formatIndianNumber($closing->closing) }} @endif</td>
    </tr>
</tfoot>