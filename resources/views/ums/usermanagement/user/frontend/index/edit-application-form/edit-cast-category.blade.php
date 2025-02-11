@if(Request()->edit=='true' && admission_open_couse_wise($application->course_id,2,$application->academic_session))
<!-- Modal -->
<div class="modal fade" id="editCastCategory" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
<form id="castCategoryEditForm">
@csrf
<input type="hidden" name="table" value="applications">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Cast Category</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        
<div class="row">

<div class="col-md-12 mb-2">
	<div class="form-group">
		<label>Category<span class="text-danger">*</span></label>
	</div>
</div>
<div class="col-md-12 mb-2">
	<select class="form-control" id="category" name="category" onchange="check_caste_certificate_number($(this).val())" required>
		<option value="">--Select Category--</option>
		@foreach($castCategorys as $castCategory)
		<option value="{{$castCategory->name}}">{{$castCategory->name}}</option>
		@endforeach
	</select>
</div>
<div class="col-md-12 mb-2 certificate_no_text" id="certificate_no1" style="display: none;">
	<div class="form-group">
		<label>Certificate Number<span class="text-danger">*</span></label>
	</div>
</div>
<div class="col-md-12 mb-2 certificate_no_text" id="certificate_no" style="display: none;">
	<div class="form-group">
		<input type="text" class="form-control" name="certificate_number" autocomplete="__away" maxlength="15" style="border: 1px solid #c0c0c0;font-size: 20px;">
	</div>
</div>
<div class="col-md-12 mb-2 certificate_no_text" id="certificate_no3" style="display: none;">
	<div class="form-group">
		<label>Upload Caste Certificate<span class="text-danger">*</span></label>
	</div>
</div>
<div class="col-md-12 mb-2 certificate_no_text" id="certificate_no3" style="display: none;">
	<div class="form-group">
		<input type="file" class="form-control" name="upload_caste_certificate" accept="application/pdf" autocomplete="__away" maxlength="15">
	</div>
</div>


</div>



      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary pull-left" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary pull-right">Save changes</button>
      </div>
    </div>
  </div>
  </form>
</div>


<script src="{{asset('assets/admin/js/jquery.min.js')}}"></script>
<script>
function check_caste_certificate_number(category) {
	if (category == '' || category == 'General') {
		$('.certificate_no_text').hide();
	} else {
		$('.certificate_no_text').show();
		
	}
}
$('#castCategoryEditForm').submit(function(e) {
		e.preventDefault();
		var formData = new FormData(this);
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$.ajax({
			type: 'POST',
			url: "{{ route('edit-application-form',[Request()->application_id]) }}",
			data: formData,
			cache: false,
			contentType: false,
			processData: false,
			success: function(data) {
				console.log(data);
				if(data.status==true){
					Swal.fire({
						title: 'SUCCESS! ',
						text: data.message,
						icon: 'success',
						confirmButtonColor: '#449d44',
						cancelButtonColor: '#d33',
						showCancelButton: false,
						confirmButtonText: 'OK'
					}).then((result) => {
						console.log(result);
						if (result.value) {
							location.reload();
						}
					});
				}else{
					Swal.fire(
					'Error!',
					data.message,
					'error'
					)
				}
			},
			error: function(request, status, error) {
				Swal.fire(
					'Error!',
					error,
					'error'
					)
			}
		});
	});
</script>

@endif