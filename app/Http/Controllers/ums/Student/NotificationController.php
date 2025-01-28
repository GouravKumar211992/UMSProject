<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function showNotification()
    {
    	$notification= Notification::all();
    	return view('student.notifications.notification',['notifications'=>$notification]);
    }
}
