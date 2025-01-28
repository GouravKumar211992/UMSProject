<?php

namespace App\models\ums;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscription extends Model
{
    
    protected $fillable = ['organization_id','created_by','updated_by','starts_from','ends_to','status'];

    protected $hidden = ['deleted_at'];

    public function organization() {
        return $this->belongsTo(Organization::class);
    }

    public function subscriptionService()
    {
        return $this->hasMany(SubscriptionService::class);
    }
}
