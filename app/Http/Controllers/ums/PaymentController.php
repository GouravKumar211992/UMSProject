<?php

namespace App\Http\Controllers;
use Mail;
use Illuminate\Http\Request;
use App\Payment;
use App\Models\ApplicationPayment;
use App\Models\Application;
use Auth;
use Razorpay\Api\Api;
use Carbon\Carbon;
use App\Http\Traits\PaymentTrait;
use App\Models\Course;

class PaymentController extends Controller
{
	use PaymentTrait;
    public function payment(){
        return view('payments.payment');
    }

    public function payNow(Request $request){
		// if(Auth::user()->course_type==1){
		// 	return redirect('phd-offline-payment?id='.$request->id);
		// }
        $applicationSerial = ApplicationPayment::orderBy('id','desc')->first();
		$serial = 1;
		if($applicationSerial){
			$serial = $applicationSerial->id + 1;
		}

		$applications_single = Application::where('id',$request->id)->first();
		$course = Course::find($applications_single->course_id);
		$late_fees = $course->late_fees;
		$total_fees = $applications_single->application_fees + $late_fees;
		$paymentDetails = ApplicationPayment::where('application_id',$request->id)
			->where('transaction_id','Initiated')
			->first();
		if($paymentDetails && $paymentDetails->paid_amount!=$total_fees){
			$paymentDetails->delete();
			$paymentDetails = null;
		}
		if($paymentDetails && $paymentDetails->paid_amount==$total_fees){
			$order_id = $paymentDetails->order_id;
		}
		if(!$paymentDetails){
			$order_id = $this->createPaymentOrder($request->id,$total_fees);
		}
		$data['order_id'] = $order_id;
		return view('payments.pay-now',compact('order_id','applications_single','course','total_fees'));
    }

    public function redirectPaymentGetway(Request $request){
		$data['amount'] = $request->amount;
		$data['order_id'] = $request->order_id;
		$data['return_url'] = $request->return_url;
		return view('payments.application-payment-getway',$data);
    }


	public function paymentSuccessSave(Request $request){
		$application_id = $request->application_id;
		$this->updatePayment($application_id,$request->razorpay_order_id);
        return redirect('pay-success?id='.$application_id);
    }
	public function paymentSuccess(Request $request){
		$application_id = $request->id;
		$application = Application::where('id',$application_id)->first();
		$paymentDetails = ApplicationPayment::where('application_id',$application_id)->where('txn_status','SUCCESS')->first();
		if(!$paymentDetails){
			$paymentDetails = ApplicationPayment::where('application_id',$application_id)->first();
			$this->updatePayment($application_id,$paymentDetails->order_id);
		}
		$application = Application::where('id',$application_id)->first();
        return view('payments.application-success-payment',compact('application','paymentDetails'));
    }

	public function paymentSave(Request $request){
		$courses = [];
		$applications = Application::whereIn('id',$request->application_id)->get();
		foreach($applications as $index=>$application){
			$courses[$index] = $application->course->name;
			$application_update = Application::where('id',$application->id)->first();
			if($application_update){
				$application_update->payment_mode = $request->payment_mode;
				$application_update->payment_status = ($request->response_code==0)?'1':'2';
				$application_update->save();
			}
			$ApplicationPayment = new ApplicationPayment;
			$ApplicationPayment->application_id = $application->id;
			$ApplicationPayment->order_id = $request->order_id;
			$ApplicationPayment->transaction_id = $request->transaction_id;
			$ApplicationPayment->paid_amount = $request->amount;
			$ApplicationPayment->txn_date = $request->payment_datetime;
			$ApplicationPayment->txn_status = $request->response_message;
			$ApplicationPayment->created_at = $request->payment_datetime;
			$ApplicationPayment->save();
			$ApplicationPayment->payment_mode = $request->payment_mode;
		}
		$courses = implode(', ',$courses);
        return redirect('pay-success?id='.$ApplicationPayment->id.'&courses='.base64_encode($courses))->with('success','Payment Done');
    }


	public function successPaymentViewPage(){
        $data=Payment::first();
        //dd($data);
        return view('payments.success-payment-page',['data'=>$data]);
    }
}

