<?php

namespace App\models\ums;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubscriptionService extends Model
{
	use SoftDeletes;
        
    protected $hidden = ['deleted_at'];
    protected $fillable = [
        'subscription_id',
        'service_id',
        'plan_id',
        'no_of_users',
        'starts_from',
        'ends_to',
        'is_trial',
        'status',
        'created_by',
        'updated_by'
    ];
    
    
    public function service() {
        return $this->belongsTo(Service::class);
    }
    
    public function subscription() {
        return $this->belongsTo(Subscription::class);
    }
    
    public function plan() {
        return $this->belongsTo(Plan::class);
    }
    
    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }

}
