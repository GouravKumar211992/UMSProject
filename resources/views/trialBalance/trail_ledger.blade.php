@extends('layouts.app')

@section('content')
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">Ledger Report</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('/') }}">Home</a></li>
                                    <li class="breadcrumb-item"><a href="javascript:;">Finance</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('trial_balance') }}">Trial Ledger</a></li>
                                    <li class="breadcrumb-item active">Ledger View</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-header-right text-sm-end col-md-6 mb-50 mb-sm-0">
                    <div class="form-group breadcrumb-right">
                        <button class="btn btn-warning btn-sm mb-50 mb-sm-0" data-bs-target="#filter"
                            data-bs-toggle="modal"><i data-feather="filter"></i> Filter</button>
                        <button class="btn btn-primary btn-sm mb-50 mb-sm-0"><i data-feather="printer"></i> Print</button>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <div class="newheader">
                                           <div>
                                               <h4 class="card-title text-theme text-dark">Ledger: <strong>{{ $ledger }}</strong></h4>
                                               <p class="card-text">{{ date('d-M-Y', strtotime($startDate)) }} to {{ date('d-M-Y', strtotime($endDate)) }}</p>
                                           </div>
                                       </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div
                                        class="col-md-12 earn-dedtable flex-column d-flex trail-balancefinance leadger-balancefinance">
                                        <div class="table-responsive">
                                            <table class="table border">
                                                <thead>
                                                    <tr>
                                                        <th width="100px">Date</th>
                                                        <th>Particulars</th>
                                                        <th>Series</th>
                                                        <th>Vch. Type</th>
                                                        <th>Vch. No.</th>
                                                        <th>Debit</th>
                                                        <th>Credit</th>
                                                    </tr>
                                                </thead>
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
                                                            <td>{{ $voucher->date }}</td>
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
                                                                                <td>{{ $item->ledger->name }}</td>
                                                                                <td>{{ $currentBalance }} {{ $currentBalanceType }}</td>
                                                                            </tr>
                                                                        @endif
                                                                    @endforeach
                                                                </table>
                                                            </td>
                                                            <td>
                                                                <a href="{{ route('vouchers.edit', ['voucher' => $voucher->id]) }}" style="font-weight: bold; color:black;">
                                                                    {{ $voucher->voucher_name }}
                                                                </a>
                                                            </td>
                                                            <td>{{ $voucher->documents['name'] ?? ""}}</td>
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

                                            </table>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>

                    </div>
                </div>
                <form method="GET" action="{{ route('trailLedger', $id) }}">
                    <div class="modal modal-slide-in fade filterpopuplabel" id="filter">
                        <div class="modal-dialog sidebar-sm">
                            <div class="modal-content pt-0">
                                <div class="modal-header mb-1">
                                    <h5 class="modal-title" id="exampleModalLabel">Apply Filter</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close">×</button>
                                </div>
                                <div class="modal-body flex-grow-1">
                                    <div class="mb-1">
                                        <label class="form-label" for="date">Select Period</label>
                                        <input type="text" id="date" name="date"
                                            class="form-control flatpickr-range bg-white"
                                            placeholder="YYYY-MM-DD to YYYY-MM-DD" value="{{ $startDate }} to {{ $endDate }}"/>
                                    </div>

                                    <div class="mb-1">
                                        <label class="form-label">Company</label>
                                        <select class="form-select" name="company_id" id="company_id">
                                            <option value="">Select Company</option>
                                            @foreach ($companies as $company)
                                                <option value="{{ $company->id }}">{{ $company->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-start">
                                    <button type="submit" class="btn btn-primary data-submit mr-1">Apply</button>
                                    <button type="reset" class="btn btn-outline-secondary"
                                        data-bs-dismiss="modal">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- END: Content-->
@endsection
