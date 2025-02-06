<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Migration;
use App\Models\MigrationPayment;
use App\Http\Traits\PaymentTraitHDFC;

class MigrationPaymentController extends Controller
{
    use PaymentTraitHDFC;
    public function paymentSuccessSave(Request $request){
        $payment_success = false;
		$paymentDetails_array = MigrationPayment::where('migration_id',$request->id)->orderBy('txn_date','DESC')->get();
        foreach($paymentDetails_array as $paymentDetails){
            if($this->verifyPayment($paymentDetails->transaction_id,'success')){
                $paymentDetails->txn_status = 'SUCCESS';
                $paymentDetails->save();
                $payment_success = true;
            }
        }
        if($payment_success==true){
            return back();
        }
        $key = $this->hdfcKey();
        $salt = $this->hdfcSalt();

        $action = $this->hdfcUrl();
        return view('student.certificate.razorpayView',compact('key','salt','action'));
    }
    public function showPaymentData(Request $request){
		$data = Migration::find($request->id);
        if(!$data){
            dd('Some Error Occurred!');
        }
        $payment = new MigrationPayment;
        $payment->migration_id = $request->id;
        $payment->roll_no = $data->roll_number;
        $payment->order_id = $request->mihpayid;
        $payment->transaction_id = $request->txnid;
        $payment->paid_amount = $request->amount;
        $payment->txn_date = $request->addedon;
        $payment->created_at = date('Y-m-d');
        $payment->updated_at = date('Y-m-d');
        if($request->status=='success'){
            $payment->txn_status = 'SUCCESS';
            $payment->save();
            return redirect('migration/pay-success?id='.$request->id)->with('success','Payment Done');
        }else{
            $payment->txn_status = $request->status;
            $payment->save();
            return redirect('migration/pay-success?id='.$request->id)->with('error','Payment Failed');
        }
    }

    public function paymentSuccess(Request $request){
        $migration_id = $request->id;
		$paymentDetails_array = MigrationPayment::where('migration_id',$migration_id)->orderBy('txn_date','DESC')->get();
        if($paymentDetails_array->count()==0){
            return back()->with('error','Some Error Occurred');
        }
        $download = $request->download;
        view()->share(compact('paymentDetails_array','download'));
        if($request->has('download')){
            $htmlfile = view('student.certificate.success-payment')->render();
            $pdf = app()->make('dompdf.wrapper');
            $pdf->loadHTML($htmlfile);
            return $pdf->download('Migration Payment Slip.pdf');
        }

        return view('student.certificate.success-payment');
    }


    



}

