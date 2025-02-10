<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>SuccessPaymentViewPage</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" >
	<style>
	@media print{
		.remove_on_print{
			display:none;
		}
	}
  .container-fluid{
    max-width: 680px;
    margin: auto;
  }
	</style>
	
</head>
<body>

<center>
<div class="container-fluid">
@if($download==null)
<br/>
<a href="{{\Request::getRequestUri()}}&download=pdf" class="btn btn-info" target="_blank">Download Payment Slip</a>
@if($examFees->form_type=='regular')
<a href="{{url('student/exam-form-view')}}/{{$examFees->id}}" class="btn btn-warning">Go Back</a>
@else
<a href="{{url('student/exam-form')}}" class="btn btn-warning">Go Back</a>
@endif
@endif


  <br/>
	<br/>
  <div class="row">
    <img src="{{asset('assets/frontend/images/cerlogo.png')}}" alt="">
  <h4>Payment Slip</h4>
  @if($paymentDetails_array->count()>0)
  @foreach($paymentDetails_array as $paymentDetails)
   <table class="table" border="2">
     <thead>
      <tr>
      <th>Transfer To <b>DSMNRU</b></th>  
        @if($paymentDetails->txn_status=='SUCCESS')
        <th style="color:green;font-weight:900;">{{$paymentDetails->txn_status}}</th>  
        @else
        <th style="color:red;font-weight:900;">{{$paymentDetails->txn_status}}</th>  
        @endif
      </tr>
     </thead>
     @if($paymentDetails->payment_mode=='Online')
    <tbody>
    <tr>
      <th scope="row">Order ID</th>
      <td>{{$paymentDetails->order_id}}</td>
    </tr> 
    <tr>
      <th scope="row">Transaction Amount</th>
      <td>{{$paymentDetails->paid_amount}}</td>
    </tr> 
    <tr>
      <th scope="row">Transaction ID</th>
      <td>{{$paymentDetails->transaction_id}}</td>
    </tr> 
    <tr>
      <th scope="row">Transaction Date</th>
      <td>{{date('d-M-Y h:i a',strtotime($paymentDetails->txn_date))}}</td>
    </tr>
    <tr>
      <th scope="row">Payment Mode</th>
      <td>{{$paymentDetails->payment_mode}}</td>
    </tr>
  </tbody>
  @elseif($paymentDetails->payment_mode=='Offline')
  <tbody>
    <tr>
      <th scope="row">Transaction Amount</th>
      <td>{{$examFees->fee_amount}}</td>
    </tr> 
    <tr>
      <th scope="row">Bank Name</th>
      <td>{{$paymentDetails->bank_name}}</td>
    </tr> 
    <tr>
      <th scope="row">Bank IFSC Code</th>
      <td>{{$paymentDetails->bank_ifsc_code}}</td>
    </tr> 
    <tr>
      <th scope="row">Challan/RTGS/NEFT Number</th>
      <td>{{$paymentDetails->challan}}</td>
    </tr> 
    <tr>
      <th scope="row">Challan/Reciept date</th>
      <td>{{date('d-m-Y',strtotime($examFees->receipt_date))}}</td>
    </tr> 
    <tr>
      <th scope="row">Payment Mode</th>
      <td>{{$paymentDetails->payment_mode}}</td>
    </tr> 
    <tr>
      <th scope="row">Challan/RTGS/NEFT Document</th>
      <td>
        @if($examFees->challan)
        <img src="{{$examFees->challan}}" style="max-height:300px;">
        @endif
      </td>
    </tr> 
  </tbody>
  @endif
</table>
@endforeach
@endif



@if($download==null)
<button class="btn btn-primary remove_on_print" onclick="window.close();">Close</button>
@endif
</div>
</div>
</center>
</body>
</html>