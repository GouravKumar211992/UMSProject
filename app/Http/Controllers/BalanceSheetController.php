<?php

namespace App\Http\Controllers;

use App\Exports\balanceSheetReportExport;
use App\Helpers\Helper;
use App\Models\Group;
use App\Models\Ledger;
use App\Models\Organization;
use App\Models\PLGroups;
use App\Models\UserOrganizationMapping;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class BalanceSheetController extends Controller
{
    public function exportBalanceSheet(Request $r){

        $dateRange = $r->date;
        $currency = "org";
        if ($r->currency!="") {
            $currency = $r->currency;
        };

        if ($r->date=="") {
            $financialYear=Helper::getCurrentFinancialYear();
            $startDate=$financialYear['start'];
            $endDate=$financialYear['end'];
            $dateRange = $startDate.' to '.$endDate;
        }
        else {
            $dates = explode(' to ', $r->date);
            $startDate = date('Y-m-d', strtotime($dates[0]));
            $endDate = date('Y-m-d', strtotime($dates[1]));
        }
        $organizations=[];
        if ($r->organization_id && is_array($r->organization_id)) {
            $organizations[] = $r->organization_id;
        };
        if(count($organizations) == 0){
            $organizations[]=Helper::getAuthenticatedUser()->organization_id;
        }
        $organizationName=implode(",",DB::table('organizations')->whereIn('id',$organizations)->pluck('name')->toArray());

        $liabilities=Group::where('parent_group_id',Group::where('name','Liabilities')->value('id'))->select('id','name')->get();
        $assets=Group::where('parent_group_id',Group::where('name','Assets')->value('id'))->select('id','name')->get();

        // Get Reserves & Surplus
        $reservesSurplus=Helper::getReservesSurplus($endDate,$organizations,'balanceSheet',$currency);

        $liabilitiesData=Helper::getBalanceSheetData($liabilities, $startDate, $endDate,$organizations,'liabilities',$currency);
        $assetsData=Helper::getBalanceSheetData($assets, $startDate, $endDate,$organizations,'assets',$currency);

        $data = [];
        $liabilitiesTotal = 0;
        $assetsTotal = 0;
        $loopLength=Helper::checkCount($liabilitiesData)>Helper::checkCount($assetsData) ? Helper::checkCount($liabilitiesData) : Helper::checkCount($assetsData);
        for ($i=0; $i <$loopLength ; $i++) {

            $secName1=$liabilitiesData->get($i)->name ?? '';

            if ($secName1=="Reserves & Surplus") {
                $secAmount1=$reservesSurplus;
            } else {
                $secAmount1=$liabilitiesData->get($i)->closing ?? 0;
            }

            $secName2=$assetsData->get($i)->name ?? '';
            $secAmount2=$assetsData->get($i)->closing ?? 0;

            $liabilitiesTotal = $liabilitiesTotal + $secAmount1;
            $assetsTotal = $assetsTotal + $secAmount2;

            $data[] = [$secName1,'','', Helper::formatIndianNumber($secAmount1),$secName2,'','', Helper::formatIndianNumber($secAmount2)];

            if ($r->level==2) {
                $liabilitiesGroupId=$liabilitiesData->get($i)->id ?? '';
                $assetsGroupId=$assetsData->get($i)->id ?? '';
                $liabilitiesLedgerData=[];
                $assetsLedgerData=[];
                if ($secName1=="Reserves & Surplus") {
                    $liabilitiesLedgerData = collect([
                        ['name' => 'Profit & Loss', 'closing' => $reservesSurplus]
                    ]);
                } else {
                    if ($liabilitiesGroupId) {
                        $liabilitiesLedgerData=Helper::getBalanceSheetLedgers($liabilitiesGroupId, $startDate, $endDate,$organizations,$currency);
                    }
                }

                if ($assetsGroupId) {
                    $assetsLedgerData=Helper::getBalanceSheetLedgers($assetsGroupId, $startDate, $endDate,$organizations,$currency);
                }

                $loopLengthLevel2=Helper::checkCount($liabilitiesLedgerData)>Helper::checkCount($assetsLedgerData) ? Helper::checkCount($liabilitiesLedgerData) : Helper::checkCount($assetsLedgerData);
                for ($j=0; $j <$loopLengthLevel2 ; $j++) {
                    if ($secName1=="Reserves & Surplus") {
                        $ledgerName1='Profit & Loss';
                        $ledgerClosing1=$reservesSurplus;
                    }
                    else {
                        $ledgerName1=$liabilitiesLedgerData->get($j)->name ?? '';
                        $ledgerClosing1=$liabilitiesLedgerData->get($j)->closing ?? 0;
                    }
                    $ledgerName2=$assetsLedgerData->get($j)->name ?? '';
                    $ledgerClosing2=$assetsLedgerData->get($j)->closing ?? 0;
                    $data[] = ['',$ledgerName1, Helper::formatIndianNumber($ledgerClosing1),'','',$ledgerName2, Helper::formatIndianNumber($ledgerClosing2),''];
                }

            }
        }
        $data[] = ['Total','','', Helper::formatIndianNumber($liabilitiesTotal),'Total','','', Helper::formatIndianNumber($assetsTotal)];

        return Excel::download(new balanceSheetReportExport($organizationName, $dateRange, $data), 'balanceSheetReport.xlsx');
    }

    public function balanceSheet()
    {
        $user = Helper::getAuthenticatedUser();
        $userId = $user->id;
        $organizationId = $user->organization_id;
        $companies = UserOrganizationMapping::where('user_id', $user->id)
        ->whereHas('organization', function($query) {
            $query->select('id', 'name');
        })
        ->get();
        $organization=Organization::where('id',Helper::getAuthenticatedUser()->organization_id)->value('name');
        return view('balanceSheet.balanceSheet',compact('organizationId','companies','organization'));
    }

    public function balanceSheetInitialGroups(Request $r)
    {
        $currency = "org";
        if ($r->currency!="") {
            $currency = $r->currency;
        };
        if ($r->date=="") {
            $financialYear=Helper::getCurrentFinancialYear();
            $startDate=$financialYear['start'];
            $endDate=$financialYear['end'];
        }
        else {
            $dates = explode(' to ', $r->date);
            $startDate = date('Y-m-d', strtotime($dates[0]));
            $endDate = date('Y-m-d', strtotime($dates[1]));
        }

        $organizations=[];
        if ($r->organization_id && is_array($r->organization_id)) {
            $organizations[] = $r->organization_id;
        };
        if(count($organizations) == 0){
            $organizations[]=Helper::getAuthenticatedUser()->organization_id;
        }

        $liabilities=Group::where('parent_group_id',Group::where('name','Liabilities')->value('id'))->select('id','name')->get();
        $assets=Group::where('parent_group_id',Group::where('name','Assets')->value('id'))->select('id','name')->get();

        // Get Reserves & Surplus
        $reservesSurplus=Helper::getReservesSurplus($endDate,$organizations,'balanceSheet',$currency);

        $liabilitiesData=Helper::getBalanceSheetData($liabilities, $startDate, $endDate,$organizations,'liabilities',$currency);
        $assetsData=Helper::getBalanceSheetData($assets, $startDate, $endDate,$organizations,'assets',$currency);
        return response()->json(['liabilitiesData'=>$liabilitiesData,'assetsData'=>$assetsData,'startDate'=>date('d-M-Y',strtotime($startDate)),'endDate'=>date('d-M-Y',strtotime($endDate)),'reservesSurplus'=>$reservesSurplus]);
    }

    public function getBalanceSheetLedgers(Request $r)
    {
        $currency = "org";
        if ($r->currency!="") {
            $currency = $r->currency;
        };
        if ($r->date=="") {
            $financialYear=Helper::getCurrentFinancialYear();
            $startDate=$financialYear['start'];
            $endDate=$financialYear['end'];
        }
        else {
            $dates = explode(' to ', $r->date);
            $startDate = date('Y-m-d', strtotime($dates[0]));
            $endDate = date('Y-m-d', strtotime($dates[1]));
        }

        $organizations=[];
        if ($r->organization_id && is_array($r->organization_id)) {
            $organizations[] = $r->organization_id;
        };
        if(count($organizations) == 0){
            $organizations[]=Helper::getAuthenticatedUser()->organization_id;
        }

        $data=Helper::getBalanceSheetLedgers($r->id, $startDate, $endDate,$organizations,$currency);

        return response()->json(['data'=>$data]);
    }

    public function getBalanceSheetLedgersMultiple(Request $r)
    {
        $currency = "org";
        if ($r->currency!="") {
            $currency = $r->currency;
        };
        if ($r->date=="") {
            $financialYear=Helper::getCurrentFinancialYear();
            $startDate=$financialYear['start'];
            $endDate=$financialYear['end'];
        }
        else {
            $dates = explode(' to ', $r->date);
            $startDate = date('Y-m-d', strtotime($dates[0]));
            $endDate = date('Y-m-d', strtotime($dates[1]));
        }

        $organizations=[];
        if ($r->organization_id && is_array($r->organization_id)) {
            $organizations[] = $r->organization_id;
        };
        if(count($organizations) == 0){
            $organizations[]=Helper::getAuthenticatedUser()->organization_id;
        }

        $allData=[];
        foreach ($r->ids as $id) {

            $data=Helper::getBalanceSheetLedgers($id, $startDate, $endDate,$organizations,$currency);

            $gData['id']=$id;
            $gData['data']=$data;
            $allData[]=$gData;
        }

        return response()->json(['data'=>$allData]);
    }
}
