<?php

namespace App\Http\Controllers;

use App\Exports\LedgerReportExport;
use App\Exports\TrialBalanceReportExport;
use App\Helpers\Helper;
use App\Models\Group;
use App\Models\ItemDetail;
use App\Models\Ledger;
use App\Models\Organization;
use App\Models\OrganizationCompany;
use App\Models\UserOrganizationMapping;
use Illuminate\Http\Request;
use App\Models\TrialBalance;
use App\Models\Voucher;
use Auth;
use Carbon\Carbon;
use App\Helpers\CurrencyHelper;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class TrialBalanceController extends Controller
{
    public function updateLedgerOpening(Request $r)
    { ///// temp method to reset opening
        DB::table('erp_item_details')
            ->whereIn('ledger_id', Ledger::pluck('id'))
            ->orderBy('date')
            ->limit(Ledger::count())
            ->update(['opening' => 0, 'opening_type' => null]);
    }

    public function exportTrialBalanceReport(Request $r)
    {
        $dateRange = $r->date;
        $currency = "org";
        if ($r->currency!="") {
            $currency = $r->currency;
        };
        if ($r->date == "") {
            $financialYear = Helper::getCurrentFinancialYear();
            $startDate = $financialYear['start'];
            $endDate = $financialYear['end'];
            $dateRange = $startDate . ' to ' . $endDate;
        } else {
            $dates = explode(' to ', $r->date);
            $startDate = date('Y-m-d', strtotime($dates[0]));
            $endDate = date('Y-m-d', strtotime($dates[1]));
        }

        $organizations = [];
        if ($r->organization_id && is_array($r->organization_id)) {
            $organizations[] = $r->organization_id;
        };
        if (count($organizations) == 0) {
            $organizations[] = Helper::getAuthenticatedUser()->organization_id;
        }

        if ($r->group_id) {
            $groups = Group::whereIn("organization_id", $organizations)->where('id', $r->group_id)->select('id', 'name')->with('children.children')->get();
        } else {
            $groups = Group::where('status', 'active')->whereNull('parent_group_id')->where(function ($q) use ($organizations) {
                $q->where(function ($own) use ($organizations) {
                    $own->whereNull('parent_group_id')->orWhereIn('organization_id', $organizations);
                });
            })->select('id', 'name')->with('children.children')->get();
        }


        // Get Reserves & Surplus
        $profitLoss = Helper::getReservesSurplus($startDate, $organizations, 'trialBalance',$currency);

        $trialData = Helper::getGroupsData($groups, $startDate, $endDate, $organizations,$currency);
        $grandDebitTotal = 0;
        $grandCreditTotal = 0;
        foreach ($trialData as $trialGroup) {

            $total_debit = $trialGroup->total_debit;
            $total_credit = $trialGroup->total_credit;

            $opening = $trialGroup->opening;
            $opening_type = '';
            $closingText = '';
            $closing = $total_debit - $total_credit;

            if ($closing != 0) {
                $closingText = $closing > 0 ? 'Dr' : 'Cr';
            }
            $closing = $closing > 0 ? $closing : -$closing;

            if ($trialGroup->name == "Liabilities") {
                if ($profitLoss['closing_type'] == $trialGroup->opening_type) {
                    $opening_type = $trialGroup->opening_type;
                    $opening = $opening + $profitLoss['closingFinal'];
                } else {
                    $openingDiff = $opening - $profitLoss['closingFinal'];
                    if ($openingDiff != 0) {
                        $openingDiff = $openingDiff > 0 ? $openingDiff : -$openingDiff;
                        if ($opening > $profitLoss['closingFinal']) {
                            $opening_type = $trialGroup->opening_type;
                        } else {
                            $opening_type = $profitLoss['closing_type'];
                        }
                        $opening = $openingDiff;
                    }
                }

                if ($opening_type == $closingText) {
                    $closingText = $closingText;
                    $closing = $opening + $closing;
                } else {
                    $closingDiff = $opening - $closing;
                    if ($closingDiff != 0) {
                        $closingDiff = $closingDiff > 0 ? $closingDiff : -$closingDiff;
                        if ($opening > $closing) {
                            $closingText = $opening_type;
                        }
                        $closing = $closingDiff;
                    }
                }
            }

            $grandDebitTotal = $grandDebitTotal + $total_debit;
            $grandCreditTotal = $grandCreditTotal + $total_credit;

            $data[] = [$trialGroup->name, '', '', Helper::formatIndianNumber($opening) . $opening_type, Helper::formatIndianNumber($total_debit), Helper::formatIndianNumber($total_credit), Helper::formatIndianNumber($closing) . $closingText];

            if ($r->level == 2 || $r->level == 3) {
                $groupLedgers = Helper::getTrialBalanceGroupLedgers($trialGroup->id, $startDate, $endDate, $organizations,$currency);
                $groupLedgersData = $groupLedgers['data'];
                foreach ($groupLedgersData as $groupLedger) {
                    if ($groupLedgers['type'] == 'group') {
                        if ($groupLedger->name == "Reserves & Surplus") {
                            $data[] = ['', 'Reserves & Surplus', '', Helper::formatIndianNumber($profitLoss['closingFinal']) . $profitLoss['closing_type'], 0, 0, Helper::formatIndianNumber($profitLoss['closingFinal']) . $profitLoss['closing_type']];
                        } else {
                            $ledgerClosingText = '';
                            $ledgerClosing = $groupLedger->total_debit - $groupLedger->total_credit;
                            if ($ledgerClosing != 0) {
                                $ledgerClosingText = $ledgerClosing > 0 ? 'Dr' : 'Cr';
                            }
                            $data[] = ['', $groupLedger->name, '', Helper::formatIndianNumber($groupLedger->opening) . $groupLedger->opening_type, Helper::formatIndianNumber($groupLedger->total_debit), Helper::formatIndianNumber($groupLedger->total_credit), Helper::formatIndianNumber($ledgerClosing > 0 ? $ledgerClosing : -$ledgerClosing) . $ledgerClosingText];
                        }
                    } else {
                        $ledgerClosingText = '';
                        $ledgerClosing = $groupLedger->details_sum_debit_amt - $groupLedger->details_sum_credit_amt;
                        if ($ledgerClosing != 0) {
                            $ledgerClosingText = $ledgerClosing > 0 ? 'Dr' : 'Cr';
                        }
                        $data[] = ['', $groupLedger->name, '', Helper::formatIndianNumber($groupLedger->opening) . $groupLedger->opening_type, Helper::formatIndianNumber($groupLedger->details_sum_debit_amt), Helper::formatIndianNumber($groupLedger->details_sum_credit_amt), Helper::formatIndianNumber($ledgerClosing > 0 ? $ledgerClosing : -$ledgerClosing) . $ledgerClosingText];
                    }

                    if ($r->level == 3) {
                        if ($groupLedger->name == "Reserves & Surplus") {
                            $data[] = ['', '', 'Profit & Loss', Helper::formatIndianNumber($profitLoss['closingFinal']) . $profitLoss['closing_type'], 0, 0, Helper::formatIndianNumber($profitLoss['closingFinal']) . $profitLoss['closing_type']];
                        } else {
                            $subGroupLedgers = Helper::getTrialBalanceGroupLedgers($groupLedger->id, $startDate, $endDate, $organizations,$currency);
                            $subGroupLedgersData = $subGroupLedgers['data'];
                            foreach ($subGroupLedgersData as $subGroupLedger) {
                                if ($subGroupLedgers['type'] == 'group') {
                                    $subLedgerClosingText = '';
                                    $subLedgerClosing = $subGroupLedger->total_debit - $subGroupLedger->total_credit;
                                    if ($subLedgerClosing != 0) {
                                        $subLedgerClosingText = $subLedgerClosing > 0 ? 'Dr' : 'Cr';
                                    }
                                    $data[] = ['', '', $subGroupLedger->name, Helper::formatIndianNumber($subGroupLedger->opening) . $subGroupLedger->opening_type, Helper::formatIndianNumber($subGroupLedger->total_debit), Helper::formatIndianNumber($subGroupLedger->total_credit), Helper::formatIndianNumber($subLedgerClosing > 0 ? $subLedgerClosing : -$subLedgerClosing) . $subLedgerClosingText];
                                } else {
                                    $subLedgerClosingText = '';
                                    $subLedgerClosing = $subGroupLedger->details_sum_debit_amt - $subGroupLedger->details_sum_credit_amt;
                                    if ($subLedgerClosing != 0) {
                                        $subLedgerClosingText = $subLedgerClosing > 0 ? 'Dr' : 'Cr';
                                    }
                                    $data[] = ['', '', $subGroupLedger->name, Helper::formatIndianNumber($subGroupLedger->opening) . $subGroupLedger->opening_type, Helper::formatIndianNumber($subGroupLedger->details_sum_debit_amt), Helper::formatIndianNumber($subGroupLedger->details_sum_credit_amt), Helper::formatIndianNumber($subLedgerClosing > 0 ? $subLedgerClosing : -$subLedgerClosing) . $subLedgerClosingText];
                                }
                            }
                        }
                    }
                }
            }
        }

        $data[] = ['', '', '', 'Grand Total', Helper::formatIndianNumber($grandDebitTotal), Helper::formatIndianNumber($grandCreditTotal), '', ''];

        $organizationName = DB::table('organizations')->where('id', $r->organization_id)->value('name');
        return Excel::download(new TrialBalanceReportExport($organizationName, $dateRange, $data), 'tiralBalanceReport.xlsx');
    }

    public function exportLedgerReport(Request $r)
    {

        $dateRange = $r->date;
        $currency = "org";
        if ($r->currency!="") {
            $currency = $r->currency;
        };
        $currency = "org";
        if ($r->currency!="") {
            $currency = $r->currency;
        };
        $organizationName = DB::table('organizations')->where('id', $r->organization_id)->value('name');
        $ledgerName = Ledger::where('id', $r->ledger_id)->value('name');

        $dates = explode(' to ', $r->date);
        $startDate = date('Y-m-d', strtotime($dates[0]));
        $endDate = date('Y-m-d', strtotime($dates[1]));

        $ledgerData = Helper::getLedgerData($r->ledger_id, $startDate, $endDate, $r->company_id, $r->organization_id,$currency);
        $totalDebit = 0;
        $totalCredit = 0;
        $data = [['', '', '', '', '', '', '']];

        // Get first opening of ledger
        $opening = ItemDetail::where('ledger_id', $r->ledger_id)->whereBetween('date', [$startDate, $endDate])->orderBy('date', 'asc')->first();
        if ($opening && $opening->opening > 0) {
            $data[] = [$opening->date, ucfirst($opening->opening_type), 'Opening Balance', '', '', $opening->opening_type == 'Cr' ? $opening->opening : '', $opening->opening_type == 'Dr' ? $opening->opening : ''];
            $totalDebit = $totalDebit + $opening->debit_amt;
            $totalCredit = $totalCredit + $opening->credit_amt;
        }

        foreach ($ledgerData as $voucher) {
            $myVoucherData = [];
            $otherVoucherData = [];
            foreach ($voucher->items as $item) {
                $voucherData = [];
                $currentBalance = $item->debit_amt - $item->credit_amt;
                $currentBalanceType = $currentBalance >= 0 ? 'Cr' : 'Dr';

                if ($item->ledger_id == $r->ledger_id) {
                    $totalDebit = $totalDebit + $item->debit_amt;
                    $totalCredit = $totalCredit + $item->credit_amt;

                    $voucherData[] = $voucher->date;
                    $voucherData[] = $currentBalanceType;
                    $voucherData[] = $item->ledger->name;
                    $voucherData[] = $voucher->voucher_name;
                    $voucherData[] = $voucher->voucher_no;
                } else {
                    $voucherData[] = ''; //insert empty date for opponents
                    $voucherData[] = ''; //insert empty Cr\Dr for opponents
                    $voucherData[] = $item->ledger->name;
                    $voucherData[] = ''; //insert empty voucher_name for opponents
                    $voucherData[] = ''; //insert empty voucher_no for opponents
                }
                $voucherData[] = Helper::formatIndianNumber(abs($item->debit_amt));
                $voucherData[] = Helper::formatIndianNumber(abs($item->credit_amt));

                if ($item->ledger_id == $r->ledger_id) {
                    $myVoucherData = $voucherData;
                } else {
                    $otherVoucherData[] = $voucherData;
                }
            }

            $data[] = $myVoucherData;
            foreach ($otherVoucherData as $other) {
                $data[] = $other;
            }
        }

        $finalBalance = $totalDebit - $totalCredit;
        $finalBalanceType = $finalBalance >= 0 ? 'Dr' : 'Cr';
        $finalBalance = abs($finalBalance);

        $data[] = ['', '', '', '', '', '', '', ''];
        $data[] = ['', '', '', '', 'Total', Helper::formatIndianNumber($totalDebit), Helper::formatIndianNumber($totalCredit)];
        $data[] = ['', $finalBalanceType, '', '', 'Closing Balance', $totalDebit > $totalCredit ? abs($finalBalance) : '', $totalCredit > $totalDebit ? abs($finalBalance) : ''];
        $data[] = ['', '', '', '', '', $totalDebit > $totalCredit ? abs($totalDebit) : abs($totalCredit), $totalDebit > $totalCredit ? abs($totalDebit) : abs($totalCredit)];

        return Excel::download(new LedgerReportExport($organizationName, $ledgerName, $dateRange, $data), 'ledgerReport.xlsx');
    }

    public function filterLedgerReport(Request $r)
    {
        $currency = "org";
        if ($r->currency!="") {
            $currency = $r->currency;
        };
        $dates = explode(' to ', $r->date);
        $startDate = date('Y-m-d', strtotime($dates[0]));
        $endDate = date('Y-m-d', strtotime($dates[1]));

        $data = Helper::getLedgerData($r->ledger_id, $startDate, $endDate, $r->company_id, $r->organization_id,$currency);

        $id = $r->ledger_id;

        $opening = ItemDetail::where('ledger_id', $id)->whereDate('date', '>=', $startDate)->orderBy('date', 'asc')->select('opening', 'opening_type')->first();
        $closing = ItemDetail::where('ledger_id', $id)->whereDate('date', '<=', $endDate)->orderBy('date', 'desc')->select('closing', 'closing_type')->first();

        $html = view('ledgers.filterLedgerData', compact('data', 'id', 'opening', 'closing'))->render();
        return response()->json($html);
    }

    public function getLedgerReport()
    {
        $companies = OrganizationCompany::whereIn('id', Organization::whereIn('id', UserOrganizationMapping::where('user_id', Helper::getAuthenticatedUser()->id)->pluck('organization_id')->toArray())->pluck('company_id')->toArray())->with('organizations')->select('id', 'name')->get();
        return view('ledgers.getLedgerReport', compact('companies'));
    }

    public function get_org_ledgers($id)
    {
        $data = Ledger::where('organization_id', $id)->select('id', 'name')->orderBy('name', 'asc')->get();
        return response()->json($data);
    }

    public function index($id = null)
    {
        $user = Helper::getAuthenticatedUser();
        $userId = $user->id;
        $organizationId = $user->organization_id;
        $companies = UserOrganizationMapping::where('user_id', $user->id)
            ->whereHas('organization', function ($query) {
                $query->select('id', 'name');
            })
            ->get();
        return view('trialBalance.view-trial-balance', compact('companies', 'organizationId', 'id'));
    }

    public function getInitialGroups(Request $r)
    {
        if ($r->date == "") {
            $financialYear = Helper::getCurrentFinancialYear();
            $startDate = $financialYear['start'];
            $endDate = $financialYear['end'];
        } else {
            $dates = explode(' to ', $r->date);
            $startDate = date('Y-m-d', strtotime($dates[0]));
            $endDate = date('Y-m-d', strtotime($dates[1]));
        }

        $organizations = [];
        if ($r->organization_id && is_array($r->organization_id)) {
            $organizations[] = $r->organization_id;
        };
        if (count($organizations) == 0) {
            $organizations[] = Helper::getAuthenticatedUser()->organization_id;
        }
        $currency = "org";
        if ($r->currency!="") {
            $currency = $r->currency;
        };

        if ($r->group_id) {
            $groups = Group::where('id', $r->group_id)->select('id', 'name')->with('children.children')->get();
        } else {
            $groups = Group::where('status', 'active')->whereNull('parent_group_id')->where(function ($q) use ($organizations) {
                $q->where(function ($own) use ($organizations) {
                    $own->whereNull('parent_group_id')->orWhereIn('organization_id', $organizations);
                });
            })->select('id', 'name')->with('children.children')->get();
        }

        // Get Reserves & Surplus
        $profitLoss = Helper::getReservesSurplus($startDate, $organizations, 'trialBalance', $currency);

        $data = Helper::getGroupsData($groups, $startDate, $endDate, $organizations, $currency);
        return response()->json(['currency' => $currency,'data' => $data, 'type' => 'group', 'startDate' => date('d-M-Y', strtotime($startDate)), 'endDate' => date('d-M-Y', strtotime($endDate)), 'profitLoss' => $profitLoss, 'groups' => $groups]);
    }

    public function getSubGroups(Request $r)
    {
        $currency = "org";
        if ($r->currency!="") {
            $currency = $r->currency;
        };
        if ($r->date == "") {
            $financialYear = Helper::getCurrentFinancialYear();
            $startDate = $financialYear['start'];
            $endDate = $financialYear['end'];
        } else {
            $dates = explode(' to ', $r->date);
            $startDate = date('Y-m-d', strtotime($dates[0]));
            $endDate = date('Y-m-d', strtotime($dates[1]));
        }

        $organizations = [];
        if ($r->organization_id && is_array($r->organization_id)) {
            $organizations[] = $r->organization_id;
        };
        if (count($organizations) == 0) {
            $organizations[] = Helper::getAuthenticatedUser()->organization_id;
        }

        $groupLedgers = Helper::getTrialBalanceGroupLedgers($r->id, $startDate, $endDate, $organizations,$currency);
        return response()->json($groupLedgers);
    }

    public function getSubGroupsMultiple(Request $r)
    {
        $currency = "org";
        if ($r->currency!="") {
            $currency = $r->currency;
        };
        if ($r->date == "") {
            $financialYear = Helper::getCurrentFinancialYear();
            $startDate = $financialYear['start'];
            $endDate = $financialYear['end'];
        } else {
            $dates = explode(' to ', $r->date);
            $startDate = date('Y-m-d', strtotime($dates[0]));
            $endDate = date('Y-m-d', strtotime($dates[1]));
        }

        $organizations = [];
        if ($r->organization_id && is_array($r->organization_id)) {
            $organizations[] = $r->organization_id;
        };
        if (count($organizations) == 0) {
            $organizations[] = Helper::getAuthenticatedUser()->organization_id;
        }

        $allData = [];
        foreach ($r->ids as $id) {
            $groupLedgers = Helper::getTrialBalanceGroupLedgers($id, $startDate, $endDate, $organizations,$currency);
            $gData['id'] = $id;
            $gData['type'] = $groupLedgers['type'];
            $gData['data'] = $groupLedgers['data'];
            $allData[] = $gData;
        }

        return response()->json(['data' => $allData]);
    }

    public function trailLedger($id, Request $r)
    {
        $currency = "org";
        if ($r->currency!="") {
            $currency = $r->currency;
        };
        // Fetch companies based on the user's organization group
        $companies = DB::table('organization_companies')
            ->where('group_id', Helper::getAuthenticatedUser()->organization_id)
            ->get();
        // $companies = OrganizationCompany::whereIn('id',Organization::whereIn('id',UserOrganizationMapping::where('user_id', Helper::getAuthenticatedUser()->id)->pluck('organization_id')->toArray())->pluck('company_id')->toArray())->with('organizations')->select('id', 'name')->get();

        $organization = DB::table('organizations')->where('id', Helper::getAuthenticatedUser()->organization_id)->value('name');
        $ledger = Ledger::where('id', $id)->value('name');
        // Determine the date range
        if ($r->date) {
            $dates = explode(' to ', $r->date);
            $startDate = date('Y-m-d', strtotime($dates[0]));
            $endDate = date('Y-m-d', strtotime($dates[1]));
        } else {
            $financialYear = Helper::getCurrentFinancialYear();
            $startDate = $financialYear['start'];
            $endDate = $financialYear['end'];
        }

        $data = Helper::getLedgerData($id, $startDate, $endDate, $r->company_id, Helper::getAuthenticatedUser()->organization_id,$currency);

        $opening = ItemDetail::where('ledger_id', $id)->whereDate('date', '>=', $startDate)->orderBy('date', 'asc')->select('opening', 'opening_type')->first();
        $closing = ItemDetail::where('ledger_id', $id)->whereDate('date', '<=', $endDate)->orderBy('date', 'desc')->select('closing', 'closing_type')->first();
        // return response()->json($transactions);
        return view('trialBalance.trail_ledger', compact('data', 'companies', 'id', 'startDate', 'endDate', 'organization', 'ledger', 'opening', 'closing'));
    }
}
