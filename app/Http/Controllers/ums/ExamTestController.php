<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExamTestController extends Controller
{

    /**
     * Display Exam Test.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $module=$id==2?'test-series':'practice-paper';
        return view('exam-test.show',['test_type_id'=>$id,'module'=>$id]);
    }

    /**
     * Display the Upcoming Exam Test.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showUpcoming($id)
    {
        return view('exam-test.upcoming',['exam_type_id'=>$id,'module'=>'upcoming']);
    }
    /**
     * Display the News Related  Exam Test.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showNews()
    {
        return view('exam-test.news',['module'=>'news']);
    }

    /**
     * Display the Exam Test Results.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function examTestResult($id)
    {
        return view('exam-test.result',['exam_test_id'=>$id,'module'=>'dashboard']);
    }
    /**
     * Display the Exam Mock Test Question Attempt.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function mockTestAttempt($id)
    {
        $module=$id==1?'mock-test':'practice-paper';
        return view('exam-test.mock-test',['exam_test_id'=>$id,'module'=>$module]);
    }
    /**
     * Display the Exam Test Question Attempt.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function examtestAttempt($id)
    {
        return view('practice-paper.test-attempt',['exam_test_id'=>$id,'module'=>$id]);
    }
    /**
     * Display the Exam Test Pages.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function testPage($id)
    {
        return view('practice-paper.test-page',['exam_test_id'=>$id,'module'=>$id]);
    }

}
