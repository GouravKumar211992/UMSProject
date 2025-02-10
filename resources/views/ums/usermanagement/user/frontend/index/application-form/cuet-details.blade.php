@if($course_single && $course_single->cuet_status=='Yes')
@php $application_details = (@$application)?$application:null; @endphp
<br/>
<div class="table-responsive">	

<table class="table table-bordered table-striped educationtable" style="border-collapse: collapse !important;">
    @if(!$application_details)
    <thead>
		<tr>
    		<th colspan="5">
                <h5>
                    <strong>CUET Details <code>Note :- Fill the marks of all subjects in which you appeared in CUET(UG)</code></strong>
                    @if($course->id==5 || $course->id==4)
                    <input type="hidden" name="cuet_required" value="No">
                    <br>
                    <br>
                    <select name="cuet_details_type" id="cuet_details_type" class="form-control" onchange="cuet_details_type_visibility()" style="max-width: 200px;">
                        <option value="Yes">CUET Details Available</option>
                        <option value="No">CUET Details Not Available</option>
                    </select>
                    @else
                        <input type="hidden" name="cuet_required" value="Yes">
                    @endif
                </h5>
            </th>
		</tr>
		<tr class="cuet_details_type_visibility">
    		<th colspan="2" style="vertical-align: middle;">
                <strong>CUET Application Number : <span class="text-danger">*</span></strong>
            </th>
    		<th colspan="3">
                <input type="text" name="cuet_application_number" class="form-control uppercase" style="max-width: 300px;">
                <div class="invalid-feedback text-danger cuet_application_number_application"></div>
            </th>
		</tr>
		<tr class="cuet_details_type_visibility">
    		<th colspan="2" style="vertical-align: middle;">
                <strong>Upload CUET(UG) Score Card : <span class="text-danger">*</span></strong>
            </th>
    		<th colspan="3">
                <input type="file" name="cuet_score_card" class="form-control" accept="image/jpeg,image/jpg,application/pdf" style="max-width: 300px;">
                <div class="invalid-feedback text-danger cuet_score_card_application"></div>
                <span class="text-danger" style="padding-left: 15px;">Only JPG or PDF files from 200KB to 500KB</span>
            </th>
		</tr>
        <tr class="cuet_details_type_visibility">
            <th>SN#</th>
            <th>Subject Code</th>
            <th>Subject Name</th>
            <th class="text-center">Maximum marks</th>
            <th class="text-center">Obtained marks</th>
        </tr>
	</thead>
	<tbody class="cuet_details_type_visibility">
        @for($cuet_loop=1;$cuet_loop<=6;$cuet_loop++)
        <tr>
            <th>{{$cuet_loop}}</th>
            <th>
                <input name="cuet_subject_code{{$cuet_loop}}" class="form-control uppercase">
            </th>
            <th>
                <input name="cuet_subject_name{{$cuet_loop}}" class="form-control uppercase">
            </th>
            <th>
                <input type="number" name="cuet_maximum_marks{{$cuet_loop}}" class="form-control cuet_maximum_marks_calculate" onkeyup="cuet_maximum_marks_calculate()">
            </th>
            <th>
                <input type="number" name="cuet_obtained_marks{{$cuet_loop}}" class="form-control cuet_obtained_marks_calculate" onkeyup="cuet_obtained_marks_calculate()">
            </th>
        </tr>
        @endfor
        <tr>
            <th colspan="3">
                <input type="hidden" name="total_cuet_maximum_marks" id="total_cuet_maximum_marks">
                <input type="hidden" name="total_cuet_obtained_marks" id="total_cuet_obtained_marks">
            </th>
            <th><strong>Total Maximum Marks : </strong><span class="total_cuet_maximum_marks"></span></th>
            <th><strong>Total Obtained Marks : </strong><span class="total_cuet_obtained_marks"></span></th>
        </tr>
    </tbody>
    @endif

    @if($application_details && $application_details->cuet_details)
    @php $cuet_details = unserialize($application_details->cuet_details); @endphp
    @if(isset($cuet_details) && $cuet_details[0]['cuet_application_number']!=null)
    <thead>
		<tr>
    		<th colspan="5"><strong style="font-size: 15px;">CUET Details</strong></th>
		</tr>
		<tr>
    		<th colspan="2" style="vertical-align: middle;">
                <strong>CUET Application Number : <span class="text-danger">*</span></strong>
            </th>
    		<th colspan="3">
            {{strtoupper(@$cuet_details[0]['cuet_application_number'])}}
            </th>
		</tr>
		<tr>
    		<th colspan="2" style="vertical-align: middle;">
                <strong>Upload CUET(UG) Score Card : <span class="text-danger">*</span></strong>
            </th>
    		<th colspan="3">
                <a href="{{$application->cuet_score_card}}" class="btn btn-info" target="_blank">View CUET Score Card</a>
            </th>
		</tr>
        <tr>
            <th>SN#</th>
            <th>Subject Code</th>
            <th>Subject Name</th>
            <th class="text-center">Maximum marks</th>
            <th class="text-center">Obtained marks</th>
        </tr>
	</thead>
	<tbody>
        @foreach($cuet_details as $cuet_loop=>$cuet_details_sigle)
        <tr>
            <td>{{++$cuet_loop}}</td>
            <td>{{$cuet_details_sigle['cuet_subject_code']}}</td>
            <td>{{$cuet_details_sigle['cuet_subject_name']}}</td>
            <td class="text-center">{{$cuet_details_sigle['cuet_maximum_marks']}}</td>
            <td class="text-center">{{$cuet_details_sigle['cuet_obtained_marks']}}</td>
        </tr>
        @endforeach
        <tr>
            <th colspan="3"></th>
            <th><strong>Total Maximum Marks : </strong><span class="total_cuet_maximum_marks">{{$cuet_details_sigle['total_cuet_maximum_marks']}}</span></th>
            <th><strong>Total Obtained Marks : </strong><span class="total_cuet_obtained_marks">{{$cuet_details_sigle['total_cuet_obtained_marks']}}</span></th>
        </tr>
    </tbody>
    @endif
    @endif
</table>

<script>
function cuet_maximum_marks_calculate(){
    validateMarks();
    var total_cuet_maximum_marks = 0;
    $('.cuet_maximum_marks_calculate').each(function(){
        var cuet_maximum_marks_single = parseInt($(this).val()) || 0;
        total_cuet_maximum_marks = total_cuet_maximum_marks + cuet_maximum_marks_single;
    });
    $('#total_cuet_maximum_marks').val(total_cuet_maximum_marks);
    $('.total_cuet_maximum_marks').text(total_cuet_maximum_marks);
}
function cuet_obtained_marks_calculate(){
    validateMarks();
    var total_cuet_obtained_marks = 0;
    $('.cuet_obtained_marks_calculate').each(function(){
        var cuet_obtained_marks_single = parseInt($(this).val()) || 0;
        total_cuet_obtained_marks = total_cuet_obtained_marks + cuet_obtained_marks_single;
    });
    $('#total_cuet_obtained_marks').val(total_cuet_obtained_marks);
    $('.total_cuet_obtained_marks').text(total_cuet_obtained_marks);
}

function validateMarks() {
    var isValid = true;

    $('.cuet_maximum_marks_calculate').each(function(index){
        var maxMarks = parseInt($(this).val()) || 0;
        var obtainedMarks = parseInt($('.cuet_obtained_marks_calculate').eq(index).val()) || 0;

        if(maxMarks <= 0 || maxMarks < obtainedMarks) {
            isValid = false;
            $(this).css('border-color', 'red');
            $('.cuet_obtained_marks_calculate').eq(index).css('border-color', 'red');
        } else {
            $(this).css('border-color', '');
            $('.cuet_obtained_marks_calculate').eq(index).css('border-color', '');
        }
    });

    return isValid;
}
@if($course->id==5 || $course->id==4)
cuet_details_type_visibility();
function cuet_details_type_visibility(){
    var cuet_details_type = $('#cuet_details_type').val();
    var cuet_details_type_visibility = $('.cuet_details_type_visibility');
    if(cuet_details_type=='Yes'){
        cuet_details_type_visibility.show();
    }else{
        cuet_details_type_visibility.hide();
    }
}
@endif
</script>

</div>

@endif