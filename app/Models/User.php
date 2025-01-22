<?php

// User.php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'name',
        'email',
        'password',
        'organization_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id', 'id');
    }

    public function userRole()
    {
        return $this->hasOne(RoleUser::class);
    }

    public function vendor_portal()
    {
        return $this->hasOne(VendorPortalUser::class,'user_id');
    }

    public function vendor_portals()
    {
        return $this->hasMany(VendorPortalUser::class,'user_id');
    }

    public function vouchers()
    {
        return $this->morphMany(Voucher::class, 'voucherable');
    }

    public function loanApplicationLogs()
    {
        return $this->hasOne(LoanApplicationLog::class, 'user_id');
    }

    public function getInitials()
    {
        $nameParts = explode(' ', $this->name);
        $initials = '';

        foreach ($nameParts as $part) {
            $initials .= strtoupper(substr($part, 0, 1));
        }

        return $initials;
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
    }

    public function hasPermission($permission)
    {

        foreach ($this->roles as $role) {
            if ($role->permissions->contains('alias', $permission)) {
                return true;
            }
        }
        return false;
    }
}
