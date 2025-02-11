@if(Request()->edit=='true' && admission_open_couse_wise($application->course_id,2,$application->academic_session))
<!-- Modal -->
<div class="modal fade" id="editDomicile" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
<form id="domicileEditForm">
@csrf
<input type="hidden" name="table" value="applications">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Domicile</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        
<div class="row">

<div class="col-md-4 mb-2">
    <div class="form-group">
        <label>Domicile<span class="text-danger">*</span></label>
        <div class="form-group" id="domicile_cirtificate1" style="margin-top: 20px; display: block;" hidden="">
            <label>Upload Your Domicile<span class="text-danger">*</span></label>
        </div>
        <div class="form-group" id="domicile1" style="margin-top: 20px; display: none;" hidden="">
            <label>Enter Your Domicile<span class="text-danger">*</span></label>
        </div>
    </div>
</div>
<div class="col-md-8 mb-2">
    <select class="form-control" id="domicile">

        <option value="">--Select Domicile--</option>
        <option value="Uttar Pradesh">Uttar Pradesh</option>
        <option value="Others">Others</option>
    </select>
    <input type="text" class="filled-in" name="domicile" id="domicile2" autocomplete="__away" hidden="" style="display: none;border: 1px solid #c0c0c0;font-size: 20px;">
    <input type="file" class="form-control" name="domicile_cirtificate" value="" id="domicile_cirtificate2" autocomplete="__away" hidden="" accept="application/pdf" style="display: block;">
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
$('#domicile').change(function() {

    var dom = $('#domicile').val();
    if(dom==''){
        return false;
    }
    if (dom == "Uttar Pradesh") {
        $("#domicile2").val(dom);
        $("#domicile1").hide();
        $("#domicile2").hide();
        $("#domicile_cirtificate2").show();
        $("#domicile_cirtificate1").show();

    } else {
        $("#domicile_cirtificate2").hide();
        $("#domicile_cirtificate1").hide();
        $("#domicile2").val("");
        $("#domicile1").show();
        $("#domicile2").show();
    }
});
$('#domicileEditForm').submit(function(e) {
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