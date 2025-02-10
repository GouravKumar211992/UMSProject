<style>
	@media print {
		.educationtable th,
		.educationtable td{
			font-size: 10px;
		}
		.educationtable a{
			display: none;
		}
	}
</style>
<table class="table table-bordered table-striped educationtable">
	<thead>
		<tr>
		<td colspan="9"><b>Educational Qualification from 10th Std. Onwards</b></td>
		</tr>
			<tr>
				<th>Name of Exam*</th>
				<th>Degree Name*</th>
				<th>Board / university*</th>
				<th>Status<span class="text-danger">*</span></th>
				<th>Passing Year*</th>
				<th>Mark Type<span class="text-danger">*</span></th>
				<th>Total Marks / CGPA*</th>
				<th >Marks/CGPA Obtained*</th>
				<th >Equivalent Percentage*</th>
				<th>Subjects</tthd>
				<th>Certificate Number</th>
				<th>Attach Document*<br />
					<span class="text-danger f-12 normal-line-height">(Marks sheets of all relevant exams passed against the admission sought.)</span>
				</th>
				<?php $segment = Request::segment(1); ?> 
				@if($segment == 'additional-education-qualification')
				<th>Action</th>
				@endif
			</tr>
	</thead>
	<tbody>
	@foreach($applicationEducation as $education)
		<tr>
			<td>{{$education->name_of_exam}}</td>
			<td>{{$education->degree_name}}</td>
			<td>{{$education->board}}</td>
			<td>{{($education->passing_status==1)?'Passed':'Appeared'}}</td>
			<td>{{$education->passing_year}}</td>
			@if($education->passing_status==1)
			<td>{{($education->cgpa_or_marks==1)?'Marks':'CGPA'}}</td>
			@else
			<td></td>
			@endif
			<td>{{$education->total_marks_cgpa}}</td>
			<td>{{$education->cgpa_optain_marks}}</td>
			<td>{{round($education->equivalent_percentage, 2)}}</td>
			<td>{{$education->subject}}</td>
			<td>{{$education->certificate_number}}</td>
			<td>
				@if($education->doc_url)
				<a href="{{$education->doc_url}}" target="_blank">View Marks Sheets Doc</a>
				@endif
				@if($education->cgpa_document)
				<br/>
				<a href="{{$education->cgpa_document}}" target="_blank">View CGPA Doc</a>
				@endif
			</td>

			@if($segment == 'additional-education-qualification')
			<td><a class="btn btn-danger" onclick="return confirm('Are you sure you want to delete?');" href="{{url('delete-educational-doc/'.$education->id)}}">Delete</a></td>
			@endif
			
		</tr>@endforeach
		
	</tbody>

</table>


