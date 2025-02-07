<?php
namespace App\Http\Traits;

use App\Models\ApplicationPayment;
use App\Models\Application;
use App\Models\ExamFee;
use App\Models\ExamPayment;
use Auth;
use Razorpay\Api\Api;
use Carbon\Carbon;

trait PaymentTrait {

	public function createPaymentOrderOnly($application_id,$fees){
        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
        $order_details = $api->order->create(array('receipt'=>$application_id,'amount' => ($fees*100), 'currency' => 'INR','notes'=>array('key1'=> 'value3')));
		return $order_details->id;
	}
	public function createPaymentOrder($application_id,$fees){
		// dd(env('RAZORPAY_KEY'));
        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
        $order_details = $api->order->create(array('receipt'=>$application_id,'amount' => ($fees*100), 'currency' => 'INR','notes'=>array('key1'=> 'value3')));
        $order_id = $order_details->id;

        $application = new ApplicationPayment;
		$application->application_id = $application_id;
		$application->order_id = $order_id;
		$application->transaction_id = 'Initiated';
		$application->paid_amount = $fees;
		$application->txn_status = 'Payment Initiated';
		$application->created_at = date('Y-m-d');
		$application->save();
		return $order_id;
	}

	public function updatePayment($application_id,$order_id){
		$api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
		$orderDetails = $api->order->fetch($order_id)->payments();
		foreach($orderDetails->items as $response){
			$application_update = Application::find($application_id);
			$ApplicationPayment = ApplicationPayment::where('application_id',$application_id)
				->where('order_id',$order_id)
				->first();
			if($ApplicationPayment->txn_status=='SUCCESS'){
				exit();
				return '';
			}
			if($application_update){
				$application_update->payment_mode = 'ONLINE';
				$application_update->payment_status = ($response->status=='captured')?'1':'2';
				$application_update->save();
			}
			if($response->status=='captured'){
				$ApplicationPayment->transaction_id = $response->id;
			}
			$ApplicationPayment->paid_amount = ($response->amount/100);
			$ApplicationPayment->txn_date = Carbon::now()->toDateTimeString();
			$ApplicationPayment->txn_status = ($response->status=='captured')?'SUCCESS':$response->status;
			$ApplicationPayment->created_at = Carbon::now()->toDateTimeString();
			$ApplicationPayment->save();
		}
	}

	public function updatePaymentBackPapers($exam_fee_id,$order_id){
		$ApplicationPayment = ExamPayment::where('exam_fee_id',$exam_fee_id)
			->where('order_id',$order_id)
			->first();
		if($ApplicationPayment->txn_status=='SUCCESS'){
			return '';
		}
		$api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
		$orderDetails = $api->order->fetch($order_id)->payments();
		foreach($orderDetails->items as $response){
			if($response->status=='captured'){
				$ApplicationPayment->transaction_id = $response->id;
			}
			$ApplicationPayment->paid_amount = ($response->amount/100);
			$ApplicationPayment->txn_date = Carbon::now()->toDateTimeString();
			$ApplicationPayment->txn_status = ($response->status=='captured')?'SUCCESS':$response->status;
			$ApplicationPayment->created_at = Carbon::now()->toDateTimeString();
			$ApplicationPayment->save();
		}
	}

}

