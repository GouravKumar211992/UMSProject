<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\DefaultGroupCompanyOrg;

use App\Traits\Deletable;

class ErpStore extends Model
{
    use HasFactory,Deletable,DefaultGroupCompanyOrg;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'erp_stores';
    
    protected $fillable = [
        'organization_id',
        'group_id',
        'company_id',
        'store_code',
        'store_name',
        'store_location_type',
        'status',
        'contact_person', 
        'contact_phone_no',
        'contact_email',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function racks()
    {
        return $this->hasMany(ErpRack::class);
    }
    
    public function shelfs()
    {
        return $this->hasManyThrough(ErpShelf::class, ErpRack::class);
    }
    
    public function bins()
    {
        return $this->hasMany(ErpBin::class);
    }
  

}
