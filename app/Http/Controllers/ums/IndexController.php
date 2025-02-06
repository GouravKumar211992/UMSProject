<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Validator;
use Auth;
use Hash;

class IndexController extends Controller
{
    /**
     * Display the User Dashboard
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('frontend.index.index');
    }
	

    public function logout() {
        \Auth::logout();
        return redirect()->intended('/admission-portal');
    }
}
