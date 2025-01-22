<?php

namespace App\Http\Controllers\CRM;

use App\Helpers\ConstantHelper;
use App\Helpers\GeneralHelper;
use App\Helpers\Helper;
use Auth;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CustomerController;
use App\Models\CRM\ErpDiary;
use App\Models\CRM\LmsLead;
use App\Models\CRM\LmsRating;
use App\Models\CRM\ErpSaleOrderSummary;
use App\Lib\Validation\ErpDiary as Validator;
use App\Models\City;
use App\Models\Country;
use App\Models\CRM\ErpCustomerTarget;
use App\Models\CRM\ErpMeetingStatus;
use App\Models\CRM\ErpOrderSummary;
use App\Models\Employee;
use App\Models\ErpCurrencyMaster;
use App\Models\ErpCustomer;
use App\Models\ErpOrderHeader;
use App\Models\State;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{
    public function index(Request $request)
    {
        $user = Helper::getAuthenticatedUser();
        $teams = $this->getTeamMembers($user);
        $salesSummary = $this->getSalesSummary($user,$request);
        $meetingSummary = $this->meetingSummary($user,$request);
        // $orderSummary = $this->getOrderSummary($user,$request);
        $topCustomersData = $this->getTopCustomersData($user,$request);
        // dd($topCustomersData);

        $user = Helper::getAuthenticatedUser();
        $userType = GeneralHelper::loginUserType();

        $customers = ErpCustomer::where(function($query){
                            GeneralHelper::applyUserFilter($query,'ErpCustomer');
                    })
                    ->orderBy('company_name','ASC')
                    ->get();

        $teamsIds = GeneralHelper::getTeam($user);
        $salesTeam = Employee::where(function($query) use($user,$userType, $teamsIds){
                        if($userType == 'employee'){
                            $query->whereIn('id', $teamsIds);
                        }else{
                            $query->where('organization_id', $user->organization_id);
                        }
                    })->get();

        $currencyMaster = ErpCurrencyMaster::where('organization_id', $user->organization_id)
                            ->where('status',ConstantHelper::ACTIVE)
                            ->first();

        return view('crm.index', [
            'teams' => $teams,
            'salesSummary' => $salesSummary,
            'salesTeam' => $salesTeam,
            'customers' => $customers,
            'meetingSummary' => $meetingSummary,
            // 'orderSummary' => $orderSummary,
            'topCustomersData' => $topCustomersData,
            'userType' => $userType,
            'currencyMaster' => $currencyMaster,
        ] );
    }

    public function getTeamMembers($user)
    {
        $data = Employee::where('manager_id', $user->id )
        ->where('organization_id', $user->organization_id)
        ->get();

        return $data;
    }

    public function getSalesSummary($user, $request){
        $endOfThisMonth = Carbon::now()->endOfMonth();
        $currentMonth = Carbon::now()->month;
        $currentYears = Carbon::now()->year;

        if ($currentMonth < 4) {
            $financialYearStart = $currentYears - 1;
            $financialYearEnd = $currentYears;
        } else {
            $financialYearStart = $currentYears;
            $financialYearEnd = $currentYears + 1;
        }

        $financialYear = "{$financialYearStart}-{$financialYearEnd}";
        
        $salesData = ErpSaleOrderSummary::where(function($query){
                            GeneralHelper::applyUserFilter($query);
                        })
                        ->where(function($query) use($request){
                            $this->applyFilter($query, $request, 'date');
                        });
        $totalAchieved = $salesData->sum('total_sale_value');
        $totalSalesValue = Helper::currencyFormat($salesData->sum('total_sale_value'),'display');

        // $totalOrderData = ErpOrderHeader::where(function($query){
        //                     GeneralHelper::applyUserFilter($query);
        //                 })
        //                 ->where(function($query) use($request){
        //                     $this->applyFilter($query, $request, 'order_date');
        //                 });
                        
        // $totalOrderValue = Helper::currencyFormat($totalOrderData->sum('total_order_value'),'display');
        
        $customerCount = ErpCustomer::where(function($query){
                            GeneralHelper::applyUserFilter($query,'ErpCustomer');
                        })
                        ->where(function($query) use($request){
                            $this->applyFilter($query, $request, 'created_at','ErpCustomer');
                        })
                        ->where('status',ConstantHelper::ACTIVE)
                        ->count();

        $totalProspectsValue = ErpCustomer::where(function($query){
                GeneralHelper::applyUserFilter($query,'ErpCustomer');
            })
            ->where(function($query) use($request){
                $this->applyFilter($query, $request, 'created_at','ErpCustomer');
            })
            ->sum('sales_figure');

        $totalProspectsValue = Helper::currencyFormat($totalProspectsValue,'display');
        
        // $newLeadCount = ErpDiary::where(function($query){
        //                     GeneralHelper::applyDiaryFilter($query);
        //                 })
        //                 ->where(function($query) use($request){
        //                     $this->applyFilter($query, $request, 'created_at');
        //                 })
        //                 ->where('customer_type','New')->count();

        $currMonth = Carbon::now()->format('M');
        $currYear = Carbon::now()->format('Y');
        $totalTarget = 0;
        $targetData = ErpCustomerTarget::where(function($query){
                            GeneralHelper::applyUserFilter($query);
                        })
                        ->where(function($query) use($request){
                            $this->applyDataFilter($query, $request);
                        });

        $achievementData = ErpSaleOrderSummary::where(function($query){
                            GeneralHelper::applyUserFilter($query);
                        })
                        ->where(function($query) use($request){
                            $this->applyDataFilter($query, $request);
                        });

        if(in_array($request->date_filter, ['today','month','week','ytd'])){
            $totalTarget = $targetData->sum(strtolower($currMonth));
            $totalTargetValue = Helper::currencyFormat($totalTarget,'display');

            $achievementData->whereYear('date', $currYear) 
                        ->whereMonth('date', $currentMonth);
                        
        }else{
            $totalTarget = $targetData->sum('total_target');
            $totalTargetValue = Helper::currencyFormat($totalTarget,'display');
        }

        $totalAchievementValue = Helper::currencyFormat($achievementData->sum('total_sale_value'),'display');

        $saleGraphData = [];
        if(in_array($request->date_filter, ['today','month','week','ytd'])){
            $saleGraphData['labels'][$currMonth] = $currMonth;
    
            $val = ErpSaleOrderSummary::where(function($query){
                            GeneralHelper::applyUserFilter($query);
                        })
                        ->where(function($query) use($request){
                            $this->applyDataFilter($query, $request);
                        })
                        ->whereYear('date', $currYear) 
                        ->whereMonth('date', $currentMonth)
                        ->sum('total_sale_value');

            $saleGraphData['salesOrderSummary'][$currMonth] = Helper::currencyFormat($val);

            $val =  ErpCustomerTarget::where(function($query){
                            GeneralHelper::applyUserFilter($query);
                        })
                        ->where(function($query) use($request){
                            $this->applyDataFilter($query, $request);
                        })
                        ->where('year', $financialYear)
                        ->sum(strtolower($currMonth));      

            $saleGraphData['customerTarget'][$currMonth] = Helper::currencyFormat($val);
            
        }else{
            foreach (Carbon::now()->subMonths(5)->monthsUntil($endOfThisMonth) as $month) {
                $saleGraphData['labels'][$month->format('M Y')] = $month->format('M');
    
                $val = ErpSaleOrderSummary::where(function($query){
                                GeneralHelper::applyUserFilter($query);
                            })
                            ->where(function($query) use($request){
                                $this->applyDataFilter($query, $request);
                            })
                            ->whereYear('date', $month->year) 
                            ->whereMonth('date', $month->month)
                            ->sum('total_sale_value');
    
                $saleGraphData['salesOrderSummary'][$month->format('M Y')] = Helper::currencyFormat($val);
    
                $val =  ErpCustomerTarget::where(function($query){
                                GeneralHelper::applyUserFilter($query);
                            })
                            ->where(function($query) use($request){
                                $this->applyDataFilter($query, $request);
                            })
                            ->where('year', $financialYear)
                            ->sum(strtolower($month->format('M')));      
    
                $saleGraphData['customerTarget'][$month->format('M Y')] = Helper::currencyFormat($val);
            }
        }

        $budgetProgress = ($totalTarget > 0) ? min(round(($totalAchieved / $totalTarget) * 100, 2), 100) : 0;
        
        return [
            'totalSalesValue' => $totalSalesValue,
            // 'totalOrderValue' => $totalOrderValue,
            'customerCount' => $customerCount,
            // 'newLeadCount' => $newLeadCount,
            'totalTargetValue' => $totalTargetValue,
            'saleGraphData' => $saleGraphData,
            'totalAchievementValue' => $totalAchievementValue,
            'budgetProgress' => $budgetProgress,
            'totalProspectsValue' => $totalProspectsValue,
        ];
    }

    public function meetingSummary($user, $request){
        $erpDiaries = ErpDiary::with(['customer','attachments','createdByEmployee' => function($q){
            $q->select('id','name');
        },'createdByUser' => function($q){
            $q->select('id','name');
        }])
            ->where(function($query){
                GeneralHelper::applyDiaryFilter($query);
            })
            ->where(function($query) use($request){
                $this->applyFilter($query, $request, 'created_at');
            })
            ->orderBy('id','desc')
            ->limit(5)
            ->get();

        // Prospect chart
        $prospectsGraphData = [];

        // $prospectData = ErpCustomer::where(function($query){
        //         GeneralHelper::applyUserFilter($query,'ErpCustomer');
        //     })
        //     ->where(function($query) use($request){
        //         $this->applyFilter($query, $request, 'created_at','ErpCustomer');
        //     })
        //     ->sum('sales_figure');

        if ($request->date_filter == 'today') {
            $date = Carbon::now()->toDateString();
            $totalProspectsValue = ErpCustomer::where(function($query){
                                    GeneralHelper::applyUserFilter($query,'ErpCustomer');
                                })
                                ->whereDate('created_at',  $date)
                                ->sum('sales_figure');
            $prospectsGraphData['data'][$date] = Helper::currencyFormat($totalProspectsValue);
            $prospectsGraphData['labels'][$date] = $date;
        }
        
        elseif ($request->date_filter == 'week') {
            $startOfWeek = Carbon::now()->startOfWeek()->toDateString();
            $endOfWeek = Carbon::now()->endOfWeek()->toDateString();
            for ($date = Carbon::parse($startOfWeek); $date->lte($endOfWeek); $date->addDay()) {
                $prospectsGraphData['labels'][$date->format('D')] = $date->format('D');
                $totalProspectsValue = ErpCustomer::where(function($query){
                                    GeneralHelper::applyUserFilter($query,'ErpCustomer');
                                })
                                ->whereDate('created_at',  $date)
                                ->sum('sales_figure');

                $prospectsGraphData['data'][$date->format('D')] = Helper::currencyFormat($totalProspectsValue);
            }
        }

        elseif ($request->date_filter == 'month') {
            $startOfMonth = Carbon::now()->startOfMonth()->toDateString();
            $endOfMonth = Carbon::now()->endOfMonth()->toDateString();
            for ($date = Carbon::parse($startOfMonth); $date->lte($endOfMonth); $date->addDay()) {
                $prospectsGraphData['labels'][$date->format('d M')] = $date->format('d M');
                $totalProspectsValue = ErpCustomer::where(function($query){
                                    GeneralHelper::applyUserFilter($query,'ErpCustomer');
                                })
                                ->whereDate('created_at',  $date->format('Y-m-d'))
                                ->sum('sales_figure');
                $prospectsGraphData['data'][$date->format('d M')] = Helper::currencyFormat($totalProspectsValue);
            }
        }
        elseif ($request->date_filter == 'ytd') {
            $startOfYear = Carbon::now()->startOfYear()->toDateString();
            $currentMonth = Carbon::now()->toDateString();
            for ($date = Carbon::parse($startOfYear); $date->lte($currentMonth); $date->addMonth()) {
                $prospectsGraphData['labels'][$date->format('F Y')] = $date->format('F Y');
                $totalProspectsValue = ErpCustomer::where(function($query){
                                    GeneralHelper::applyUserFilter($query,'ErpCustomer');
                                })
                                ->whereYear('created_at', $date->year)
                                ->whereMonth('created_at', $date->month)
                                ->sum('sales_figure');
                $prospectsGraphData['data'][$date->format('F Y')] = Helper::currencyFormat($totalProspectsValue);
            }
        }
        else{
            $endOfThisMonth = Carbon::now()->endOfMonth();

            foreach (Carbon::now()->subMonths(5)->monthsUntil($endOfThisMonth) as $month) {
                $prospectsGraphData['labels'][$month->format('M Y')] = $month->format('M Y');
                $totalProspectsValue = ErpCustomer::where(function($query){
                                    GeneralHelper::applyUserFilter($query,'ErpCustomer');
                                })
                                ->whereMonth('created_at', $month)
                                ->sum('sales_figure');

                $prospectsGraphData['data'][$month->format('M Y')] = Helper::currencyFormat($totalProspectsValue);
            }
        }       

        return [
            'erpDiaries' => $erpDiaries,
            'prospectsGraphData' => $prospectsGraphData,
        ];
    }


    public function getTopCustomersData($user,$request)
    {
        $limit = 5; 

        $erpSaleOrderSummary = ErpSaleOrderSummary::join('erp_customers', 'erp_sale_order_summaries.customer_code', 'erp_customers.customer_code')
                ->join('erp_industries', 'erp_industries.id','erp_customers.industry_id')
                ->where(function($query) use($user) {
                    if (Auth::guard('web')->check()) {
                        $query->where('erp_sale_order_summaries.organization_id', $user->organization_id);
                    } elseif (Auth::guard('web2')->check()) {
                        $teamIds = GeneralHelper::getTeam($user);
                        $query->whereIn('erp_customers.sales_person_id', $teamIds);
                    }
                })
                ->where(function($query) use($request) {
                    if ($request->date_filter == 'today') {
                        $query->whereDate('erp_sale_order_summaries.date', date('Y-m-d'));
                    }

                    if ($request->date_filter == 'week') {
                        $startOfWeek = Carbon::now()->startOfWeek()->toDateString();
                        $endOfWeek = Carbon::now()->endOfWeek()->toDateString();
                        $query->whereDate('erp_sale_order_summaries.date', '>=', $startOfWeek)
                                ->whereDate('erp_sale_order_summaries.date', '<=', $endOfWeek);
                    }

                    if ($request->date_filter == 'month') {
                        $startOfMonth = Carbon::now()->startOfMonth()->toDateString();
                        $endOfMonth = Carbon::now()->endOfMonth()->toDateString();
                        $query->whereDate('erp_sale_order_summaries.date', '>=', $startOfMonth)
                                ->whereDate('erp_sale_order_summaries.date', '<=', $endOfMonth);
                    }

                    if ($request->date_filter == 'ytd') {
                        $startOfYear = Carbon::now()->startOfYear()->toDateString();
                        $today = Carbon::now()->toDateString();
                        $query->whereDate('erp_sale_order_summaries.date', '>=', $startOfYear)
                                ->whereDate('erp_sale_order_summaries.date', '<=', $today);
                    }
                });

        $topCustomerData = $erpSaleOrderSummary->clone()->select('erp_industries.name as industry_name','erp_customers.industry_id', DB::raw('SUM(total_sale_value) as total_sale_value'))
                            ->whereNotNull('industry_id') 
                            ->groupBy('erp_customers.industry_id') 
                            ->orderBy('total_sale_value', 'desc') 
                            ->limit($limit)
                            ->get();

        $top5TotalSales = $topCustomerData->sum('total_sale_value');
        $totalSales = $erpSaleOrderSummary->clone()->sum('total_sale_value');

        $topProspectsSplitByIndustry = [];
        
        foreach($topCustomerData as $key => $value){
            $sales_percentage = (($value->total_sale_value)/$totalSales)*100;
            $topProspectsSplitByIndustry[] = [
                'industry' => $value->industry_name,
                'total_sale_value' => Helper::currencyFormat($value->total_sale_value,'display'),
                'sales_percentage' => round($sales_percentage, 2),
                'color_code' => sprintf('#%06X', mt_rand(0, 0xFFFFFF))
            ];
        }

        $top5TotalSales = round($top5TotalSales, 2);
        $totalSales = round($totalSales, 2);
        $otherSales = $totalSales > $top5TotalSales  ? $totalSales - $top5TotalSales : 0;
        $otherSalesPrc = ($totalSales > 0) ? min(round(($otherSales / $totalSales) * 100, 2), 100) : 0;     
        
        $topProspectsSplitByIndustry[] = [
            'industry' => 'All Other',
            'total_sale_value' => Helper::currencyFormat($otherSales,'display'),
            'sales_percentage' => $otherSalesPrc,
            'color_code' => sprintf('#%06X', mt_rand(0, 0xFFFFFF)),
        ];

        return [
            'topProspectsSplitByIndustry' => $topProspectsSplitByIndustry,
        ];
    }

    // public function getOrderSummary($user, $request){
    //     $endOfThisMonth = Carbon::now()->endOfMonth();

    //     $ordersGraphData = [];

    //     if ($request->date_filter == 'today') {
    //         $ordersGraphData['labels'][] = Carbon::today()->format('d-m-y');
    //         $totalOrderValue = ErpOrderHeader::where(function($query){
    //                                 GeneralHelper::applyUserFilter($query);
    //                             })
    //                             ->whereDate('order_date',date('Y-m-d'))
    //                             ->sum('total_order_value');

    //         $ordersGraphData['data'][] = Helper::currencyFormat($totalOrderValue);

    //     }elseif($request->date_filter == 'week'){

    //         $startOfWeek = Carbon::now()->startOfWeek();;
    //         $endOfWeek = Carbon::now()->endOfWeek();

    //         for ($date = $startOfWeek; $date->lte($endOfWeek); $date->addDay()) {
    //             $ordersGraphData['labels'][] = $date->format('D');
    //             $totalOrderValue = ErpOrderHeader::where(function($query){
    //                                             GeneralHelper::applyUserFilter($query);
    //                                         })
    //                                         ->whereDate('order_date', $date->toDateString())
    //                                         ->sum('total_order_value');
    //             $ordersGraphData['data'][] = Helper::currencyFormat($totalOrderValue);
    //         }

    //     }elseif($request->date_filter == 'month'){
    //         $startOfMonth = Carbon::now()->startOfMonth()->toDateString();
    //         $endOfMonth = Carbon::now()->endOfMonth()->toDateString();
    //         $ordersGraphData['labels'][] = Carbon::now()->format('F Y');
    //         $totalOrderValue = ErpOrderHeader::where(function($query){
    //                             GeneralHelper::applyUserFilter($query);
    //                         })
    //                         ->whereBetween('order_date', [$startOfMonth, $endOfMonth])
    //                         ->sum('total_order_value');
    //         $ordersGraphData['data'][] = Helper::currencyFormat($totalOrderValue);
            
    //     }else{
    //         foreach (Carbon::now()->subMonths(5)->monthsUntil($endOfThisMonth) as $month) {
    //             $ordersGraphData['labels'][$month->month] = $month->format('M');
    
    //             $totalOrderValue = ErpOrderHeader::where(function($query){
    //                                 GeneralHelper::applyUserFilter($query);
    //                             })
    //                             ->where(function($query) use($request){
    //                                 $this->applyFilter($query, $request, 'order_date');
    //                             })
    //                             ->whereYear('order_date', $month->year) 
    //                             ->whereMonth('order_date', $month->month)
    //                             ->sum('total_order_value');
    //             $ordersGraphData['data'][] = Helper::currencyFormat($totalOrderValue);
    //         }
    //     }
    //     return [
    //         'ordersGraphData' => $ordersGraphData 
    //     ];
    // }

    // function getTopCustomersData($user, $request)
    // {
    //     $limit = 10; // count for top customers for whom data to be fetched
    //     $currentMonthString = strtolower(Carbon::now()->format('M'));
    //     $currentMonth = Carbon::now()->month;
    //     $currentYears = Carbon::now()->year;

    //     if ($currentMonth < 4) {
    //         $financialYearStart = $currentYears - 1;
    //         $financialYearEnd = $currentYears;
    //     } else {
    //         $financialYearStart = $currentYears;
    //         $financialYearEnd = $currentYears + 1;
    //     }

    //     $financialYear = "{$financialYearStart}-{$financialYearEnd}";

    //     $topSalesData = ErpSaleOrderSummary::with(['customerTarget' => function($q) use($currentMonthString,$financialYear){
    //                     $q->select('customer_code', DB::raw("`{$currentMonthString}` as target_value"))
    //                     ->whereNotNull($currentMonthString)
    //                     ->where('year','=', $financialYear);
    //                 },'customer' => function($query){
    //                     $query->select('customer_code','company_name');
    //                 }])
    //                 ->where(function($query){
    //                     GeneralHelper::applyUserFilter($query);
    //                 })
    //                 ->select('customer_code', DB::raw('SUM(total_sale_value) as total_sale_value'))
    //                 ->where(function ($query) use($request) {
    //                     if ($request) {
    //                         $this->applyFilter($query, $request, 'date');
    //                     }
    //                 })
    //                 ->groupBy('customer_code')
    //                 ->orderBy('total_sale_value', 'desc')
    //                 ->limit($limit)
    //                 ->get();

    //     $totalTopSales = Helper::currencyFormat($topSalesData->sum('total_sale_value'),'display');
        
    //     return [
    //         'totalTopSales' => $totalTopSales,
    //         'topSalesData' => $topSalesData,
    //         'limit' => $limit,
    //     ];;
    // }

    private function applyFilter($query, $request, $dateColumn,$model=null){

        if ($request->date_filter == 'today') {
            $query->whereDate($dateColumn,date('Y-m-d'));
        }
        
        if ($request->date_filter == 'week') {
            $startOfWeek = Carbon::now()->startOfWeek()->toDateString();
            $endOfWeek = Carbon::now()->endOfWeek()->toDateString();
            $query->whereDate($dateColumn, '>=', $startOfWeek)
            ->whereDate($dateColumn, '<=', $endOfWeek);
        }

        if ($request->date_filter == 'month') {
            $startOfMonth = Carbon::now()->startOfMonth()->toDateString();
            $endOfMonth = Carbon::now()->endOfMonth()->toDateString();
            $query->whereDate($dateColumn, '>=', $startOfMonth)
            ->whereDate($dateColumn, '<=', $endOfMonth);
        }

        if ($request->date_filter == 'ytd') {
            $startOfYear = Carbon::now()->startOfYear()->toDateString();
            $today = Carbon::now()->toDateString();
            $query->whereDate($dateColumn, '>=', $startOfYear)
            ->whereDate($dateColumn, '<=', $today);
        }

        if ($request->date_range) {
            $duration = explode(' to ', $request->date_range);
            $from_date = Carbon::parse($duration[0]);
            $to_date = isset($duration[1]) ? Carbon::parse($duration[1]) : Carbon::parse($duration[0]);

            $query->whereDate($dateColumn, '<=', $to_date)
            ->whereDate($dateColumn, '>=', $from_date);
        }

        if ($request->customer_code) {
            $query->where('customer_code', $request->customer_code);
        }

        if ($request->sales_team_id) {
            if($model == 'ErpCustomer'){
                $query->where('sales_person_id',$request->sales_team_id);
            }else{
                $query->whereHas('customer', function($q) use($request){
                    $q->where('sales_person_id',$request->sales_team_id);
                });
            }
        }

        if ($request->type && $request->type_id) {
            if ($request->type == 'domestic') {
                $column = 'state_id';
            }

            elseif ($request->type == 'international') {
                $column = 'country_id';
            }

            if (isset($column)) {
                if ($model == 'ErpCustomer') {
                    $query->whereIn($column, $request->type_id);
                } else {
                    $query->whereHas('customer', function($q) use($column, $request) {
                        $q->whereIn($column, $request->type_id);
                    });
                }
            }
        }

        if ($request->type && !$request->type_id) {
            $country = Country::where('code','AU')->first();
            $countryCondition = ($request->type == 'domestic') ? '=' : '!=';
            
            if ($model == 'ErpCustomer') {
                $query->where('country_id', $countryCondition, $country->id);
            } else {
                $query->whereHas('customer', function ($q) use ($country, $countryCondition) {
                    $q->where('country_id', $countryCondition, $country->id);
                });
            }
        }

        return $query;
    }

    private function applyDataFilter($query, $request){
        if ($request->customer_code) {
            $query->where('customer_code', $request->customer_code);
        }

        if ($request->sales_team_id) {
            $query->whereHas('customer', function($q) use($request){
                $q->where('sales_person_id',$request->sales_team_id);
            });
        }

        if ($request->type && $request->type_id) {
            if ($request->type == 'domestic') {
                $column = 'state_id';
            }

            elseif ($request->type == 'international') {
                $column = 'country_id';
            }

            if (isset($column)) {
                $query->whereHas('customer', function($q) use($column, $request) {
                    $q->whereIn($column, $request->type_id);
                });
            }
        }

        if ($request->type && !$request->type_id) {
            $country = Country::where('code','AU')->first();
            $countryCondition = ($request->type == 'domestic') ? '=' : '!=';
            
            $query->whereHas('customer', function ($q) use ($country, $countryCondition) {
                $q->where('country_id', $countryCondition, $country->id);
            });
        }
        
        return $query;
    }

    public function view(){
        return view('crm.notes.view');
    }

    public function getStates(Country $country){
        $states = State::where('country_id',$country->id)->get();
        return [
            'data' => $states
        ];
    }

    public function getCities(State $state){
        $cities = City::where('state_id',$state->id)->get();
        return [
            'data' => $cities
        ];
    }

    public function getCountriesStates($type){
        $country = Country::where('code','AU')->first();
        if($type == 'international'){
            $data = Country::where('id','!=',$country->id)->get();
        }else{
            $data = State::where('country_id',$country->id)->get();
        }
        return [
            'data' => $data
        ];
    }

}


