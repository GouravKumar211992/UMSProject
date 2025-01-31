<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\StudentSemesterFee;
use App\Models\CourseFee;
use Validator;

class PaymentController extends Controller
{
    //
	    public function payNow(Request $request){
			//dd($request->all());
			$id=$request->id;
        $application = Application::where('id',$id)->first();
		$serial = 1;
		if($application){
			$serial = $application->id + 1;
		}
		$data['lastApplication']=$application;
		$coursefee=CourseFee::where('course_id',$application->course_id)->get();
		$total_non_disabled_fees = $coursefee->sum('non_disabled_fees');
		$total_disabled_fees = $coursefee->sum('disabled_fees');
		$data['total_non_disabled_fees']=$total_non_disabled_fees;	
		$data['total_disabled_fees']=$total_disabled_fees;	
		$data['order_id'] = uniqid().sprintf('%05d', $serial);
		//dd($total_non_disabled_fees);
        return view('payments.semester-payments.pay-now',$data);
    }

    public function redirectPaymentGetway(Request $request){
		//dd($request->all());
		$data['amount'] = $request->amount;
		$data['order_id'] = $request->order_id;
		$data['return_url'] = $request->return_url;
		//dd($data);
		return view('payments.semester-payments.semester-payment-getway',$data);
    }


	public function paymentSuccess(Request $request){
	dd($request->all());
		$application = ApplicationPayment::find($request->id);
		$data['application'] = $application;
        return view('payments.semester-payments.semester-success-payment',$data);
    }

	public function paymentSave(Request $request){
			//dd($request->all());
			$application=Application::where('id',$request->application_id)->first();
		$semester_update = StudentSemesterFee::where('enrollment_no',$application->application_no)->orderBy('id','desc')->first();
		//dd($request->all(),$semester_update);
		if($request->payment_mode=='offline'){
				
			$validator = Validator::make($request->all(), [
				// 'order_id' => 'required',
				'transaction_id'=>'required|unique:student_semester_fees,receipt_number',
				'txn_date'=>'required',
				'payment_file'=>'required',
				]);
				if ($validator->fails()) {    
				return back()->withErrors($validator)->withInput();
			}
		}
			if($semester_update){
				if($request->payment_mode=='offline'){
					//dd($request->all(),$semester_update);
				$semester_update->semester_fee = $request->paid_amount;
				// $semester_update->order_id = $request->order_id;
				$semester_update->receipt_number = $request->transaction_id;
				// $semester_update->txn_status = $request->txn_status;
				// $semester_update->payment_mode = $request->payment_mode;
				$semester_update->receipt_date = $request->txn_date;
				if($request->payment_file)
					{
					$semester_update->addMediaFromRequest('payment_file')->toMediaCollection('payment_file');
					}
					//dd($semester_update);
				$semester_update->save();
			}
			else{
				// $semester_update->order_id = $request->order_id;
				$semester_update->challan_number = $request->transaction_id;
				$semester_update->amount = $request->amount;
				$semester_update->challan_reciept_date = $request->payment_datetime;
				$semester_update->txn_status = $request->response_message;
				$semester_update->created_at = $request->payment_datetime;
				$semester_update->payment_mode = $request->payment_mode;
				$semester_update->save();
			
			}
			}
			if($request->payment_mode=='offline'){
				return redirect('dashboard')->with('success','Semester Data Saved Please Wait For Approval');
			}
        return redirect('semester-payment-success?id='.$application->id)->with('success','Payment Done');
    }


	public function successPaymentViewPage(){
        $data=Payment::first();
        dd($data);
        return view('payments.success-payment-page',['data'=>$data]);
    }

}
