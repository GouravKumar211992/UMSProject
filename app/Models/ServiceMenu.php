<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceMenu extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'erp_service_id',
        'name',
        'alias',
    ];

    protected $table = 'services_menu';

    protected $casts = [
        'erp_service_id' => 'array'
    ];
}
