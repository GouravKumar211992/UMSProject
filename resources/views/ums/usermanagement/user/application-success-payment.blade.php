<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Success Payment View Page</title>
  <!-- Bootstrap CSS CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap JS Bundle (includes Popper) CDN -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

	<style>
		@media print {
			.remove_on_print {
				display: none;
			}
		}
		.container-fluid {
			max-width: 900px;
			margin: 0 auto;
			padding-top: 50px;
		}
		.table th, .table td {
			vertical-align: middle;
		}
	</style>
</head>
<body>

	<div class="container-fluid">
		<!-- Print Button -->
		<div class="text-right remove_on_print mb-3">
			<input type="button" value="Print" class="btn btn-primary" onClick="window.print()" />
		</div>

		<div class="row">
			<div class="col-md-12 text-center mb-4">
				<img src="{{asset('assets/admin/img/logo.png')}}" alt="Logo" style="max-width: 150px;">
				<h4 class="mt-2">Application Payment Slip</h4>
			</div>
		</div>

		<!-- Payment Details Table -->
		<div class="row">
			<div class="col-md-12">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th colspan="2" class="text-center bg-primary text-white">Transfer To DSMNRU</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<th>Student Name</th>
							<td>{{$paymentDetails->application->full_name}}</td>
						</tr>
						<tr>
							<th>Application No.</th>
							<td>{{$paymentDetails->application->application_no}}</td>
						</tr>
						<tr>
							<th>Payment Status</th>
							<td>
								@if($paymentDetails->txn_status=='SUCCESS')
									<b class="text-success">{{$paymentDetails->txn_status}}</b>
								@else
									<b class="text-danger">{{$paymentDetails->txn_status}}</b>
								@endif
							</td>
						</tr>
						<tr>
							<th>Courses</th>
							<td>
								@if(Request()->courses)
									{{base64_decode(Request()->courses)}}
								@else
									{{$application->course->name}}
								@endif
							</td>
						</tr>
						<tr>
							<th>Transaction ID</th>
							<td>{{$paymentDetails->transaction_id}}</td>
						</tr>
						<tr>
							<th>Transaction Amount</th>
							<td>Rs.{{$paymentDetails->paid_amount}}</td>
						</tr>
						<tr>
							<th>Transaction Date</th>
							<td>{{date('d-m-Y h:i a', strtotime($paymentDetails->created_at))}}</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>

		<!-- Go to Dashboard Button -->
		<div class="row">
			<div class="col-md-12 text-center">
				<a href="{{url('user-dashboard')}}" class="btn btn-success remove_on_print">Go to Dashboard</a>
			</div>
		</div>
	</div>

</body>
</html>
