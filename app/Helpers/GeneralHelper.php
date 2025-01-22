<?php

namespace App\Helpers;

use App\Models\Country;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;

class GeneralHelper
{
    public static function checkFileExtension($url)
    {
        $ext = File::extension($url);
        if (in_array($ext, ['jpeg', 'jpg', 'png', 'svg'])) {
            return 'image';
        }
        return 'file';
    }

    public static function applyUserFilter($query,$model=null)
    {
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();

            $query->where(function($q) use($user){
                $q->where('organization_id', $user->organization_id);
            });
        } elseif (Auth::guard('web2')->check()) {
            $user = Auth::guard('web2')->user();
            $teamIds = self::getTeam($user);

            if($model == 'ErpCustomer'){
                $query->where(function($q) use($teamIds){
                    $q->whereIn('sales_person_id',$teamIds);
                });
                
            }else{
                $query->where(function($q) use($teamIds,$user){
                    $q->whereHas('customer',function($query) use($teamIds){
                            $query->whereIn('sales_person_id',$teamIds);
                        });
                });
            }

        }

        return $query;
    }

    public static function applyDiaryFilter($query,$model=null)
    {
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();

            $query->where(function($q) use($user){
                $q->where('organization_id', $user->organization_id);
            });
        } elseif (Auth::guard('web2')->check()) {
            $user = Auth::guard('web2')->user();
            $teamIds = self::getTeam($user);
            
            $query->where(function($q) use($teamIds,$user){
                $q->where('created_by',$user->id)
                    ->orWhereHas('customer',function($query) use($teamIds){
                        $query->whereIn('sales_person_id',$teamIds);
                    });
            });

        }

        return $query;
    }

    public static function applyDateFilter($query, $request, $dateColumn){
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

        return $query;
    }

    public static function loginUserType()
    {
        if (Auth::guard('web')->check()) {
            $type = 'user';
        } elseif (Auth::guard('web2')->check()) {
            $type = 'employee';
        }

        return $type;
    }

    public static function dateFormat($date)
    {
        $date = $date ? date('d/m/Y', strtotime($date)) : '';
        return $date;
    }

    public static function getTeam($user){
        $teamIds = Employee::where(function($q) use($user){
            $q->where('manager_id', $user->id)
            ->orWhere('id',$user->id);
        })
        ->pluck('id')
        ->toArray();

        return $teamIds;
    }
}