<?php

namespace App\Http\Controllers;

use App\PaymentController;
use Illuminate\Http\Request;
use Razorpay\Api\Api;
use Session;
use Exception;
use App\Models\ApplicationPayment;
use App\Models\Application;
use Carbon\Carbon;

class RazorpayPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index(Request $request)
    // {
    //     return view('payments.razorpay.razorpayView');
    // }
  
    /**
     * Write code on Method
     *
     * @return response()
     */
    // public function store(Request $request)
    // {

        
    //     $input = $request->all();
	// 	if($request->id){
	// 		$appid = [$request->id];
	// 	}else{
	// 		$appid = $request->application_id;
	// 	}

    //     $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
  
    //     $payment = $api->payment->fetch($input['razorpay_payment_id']);
    //     if(count($input)  && !empty($input['razorpay_payment_id'])) {
    //         try {
    //             $fee_without_charges = ($payment['amount']-$payment['fee']);
    //             $response = $api->payment->fetch($input['razorpay_payment_id'])->capture(array('amount'=>$fee_without_charges)); 

    //             $courses = [];
	// 			$applications = Application::whereIn('id',$appid)->get();
	// 			foreach($applications as $index=>$application){
	// 				$courses[$index] = $application->course->name;
	// 				$application_update = Application::where('id',$application->id)->first();
	// 				if($application_update){
	// 					$application_update->payment_mode = $request->method;
	// 					$application_update->payment_status = ($response->status=='captured')?'1':'2';
	// 					$application_update->save();
	// 				}
	// 				$ApplicationPayment = new ApplicationPayment;
	// 				$ApplicationPayment->application_id = $application->id;
	// 				//$ApplicationPayment->order_id = $response->order_id;
	// 				$ApplicationPayment->order_id = ($response->order_id)?$response->order_id:'RAZORPAY';
	// 				$ApplicationPayment->transaction_id = $response->id;
	// 				$ApplicationPayment->paid_amount = ($response->amount/100);
	// 				$ApplicationPayment->txn_date = Carbon::now()->toDateTimeString();
	// 				$ApplicationPayment->txn_status = ($response->status=='captured')?'Transaction successful':$response->status;
	// 				$ApplicationPayment->created_at = Carbon::now()->toDateTimeString();
	// 				$ApplicationPayment->save();
	// 			}
	// 			$courses = implode(', ',$courses);
	// 			return redirect('pay-success?id='.$ApplicationPayment->id.'&courses='.base64_encode($courses))->with('success','Payment Done');

    //       } catch (Exception $e) {
    //             return  $e->getMessage();
    //             Session::put('error',$e->getMessage());
    //             return redirect()->back();
    //         }
    //     }
          
    //     Session::put('success', 'Payment successful');
    //     return redirect()->back();
    // }
}
