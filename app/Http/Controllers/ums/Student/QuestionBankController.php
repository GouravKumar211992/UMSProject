<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\QuestionBank;

class QuestionBankController extends Controller
{
    public function index()
    {
       $data=QuestionBank::paginate(10);
     return view('student.questionbank.view',['items'=>$data]);

    }
}
