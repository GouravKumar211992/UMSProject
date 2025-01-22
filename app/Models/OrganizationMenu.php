<?php

// app/Models/OrganizationMenu.php

namespace App\Models;

use App\Traits\DefaultGroupCompanyOrg;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Helpers\Helper;

class OrganizationMenu extends Model
{
    use HasFactory, SoftDeletes,DefaultGroupCompanyOrg;
    protected $table = 'organizations_menu';
    protected $appends = ['menu_link'];
    protected $fillable = [
        'organization_id',
        'group_id',
        'company_id',
        'menu_id',
        'service_id',
        'name',
        'alias',
        'parent_id',
        'sequence',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function getMenuLinkAttribute()
    {
        return str_replace('_', '/', $this->alias);
    }
    public function group()
    {
        return $this->belongsTo(OrganizationGroup::class, 'group_id', 'id');
    }
    public function service()
    {
        return $this->belongsTo(Services::class, 'service_id');
    }

    public function serviceMenu()
    {
        return $this->belongsTo(ServiceMenu::class, 'menu_id');
    }
    // public function childMenus1()
    // {
    //     return $this->hasMany(OrganizationMenu::class, 'parent_id', 'menu_id')->with('childMenus');
    // }

    public function childMenus()
    {
        return $this->hasMany(OrganizationMenu::class, 'parent_id', 'menu_id')
                    ->where('group_id', Helper::getAuthenticatedUser()->organization->group_id);
    }
}