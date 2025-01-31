<?php

namespace App\Http\Controllers\ums;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class UmsController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
