<?php

namespace App\Http\Controllers\ums\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ums\Notification;

class NotificationController extends Controller
{
    public function showNotification()
    {
    	$notification= Notification::all();
    	return view('ums.student.notification',['notifications'=>$notification]);
    }
}
