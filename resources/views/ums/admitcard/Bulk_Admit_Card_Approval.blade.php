@extends("ums.admin.admin-meta")
@section("content")


  
<!-- BEGIN: Content-->
<div class="app-content content">
  @include('ums.admin.notifications')
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-5 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Bulk Admit Card Approval</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.html">Home</a></li>  
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-sm-end col-md-7 mb-50 mb-sm-0">
                <div class="form-group breadcrumb-right"> 
                  <button type="submit" form="form_data" class="btn btn-primary btn-sm" href="#">
                    <i data-feather="check-circle" ></i>
                    Generate Admit Card
                </button>
                <button class="btn btn-outline-secondary btn-sm" id="exportButton">
                  <i data-feather="share" class="font-small-4 me-50"></i>Export
              </button>
              <button class="btn btn-warning box-shadow-2 btn-sm mb-sm-0 mb-50" onclick="window.location.reload();">
                  <i data-feather="refresh-cw"></i>Reset
              </button>
                </div>
            </div>
        </div>
        <form method="POST" id="form_data" action="{{ route('Bulk_Admit_Card_Approval') }}">
          @csrf
          <div class="customernewsection-form poreportlistview p-1">
              <div class="row">
                  <!-- Paper Type -->
                  <div class="col-md-3">
                      <div class="mb-1">
                          <label class="form-label" for="paperType">Paper Type:</label>
                          <select name="paper_type" id="paper_type" class="form-select" onChange="location.href='{{ url()->current() }}?paper_type=' + this.value;">
                              <option value="">Choose Paper Type</option>
                              <option value="regular" @if(Request()->paper_type == 'regular') selected @endif>REGULAR</option>
                              <option value="back_paper" @if(Request()->paper_type == 'back_paper') selected @endif>BACK</option>
                              <option value="special_back" @if(Request()->paper_type == 'special_back') selected @endif>SPLBACK</option>
                              <option value="final_back_paper" @if(Request()->paper_type == 'final_back_paper') selected @endif>FINAL BACK</option>
                              <option value="compartment" @if(Request()->paper_type == 'compartment') selected @endif>Supplementary</option>
                          </select>
                          <span class="text-danger">{{ $errors->first('paper_type') }}</span>
                      </div>
                  </div>
      
                  <!-- Campus Select -->
                  <div class="col-md-3">
                      <div class="mb-1">
                          <label class="form-label" for="campus">Campus:</label>
                          <select name="campus_id" id="campus_id" class="form-select" onChange="location.href='{{ url()->current() }}?campus_id=' + this.value;">
                              <option value="">--Choose Campus--</option>
                              @foreach($campuses as $campus)
                                  <option value="{{ $campus->id }}" @if(Request()->campus_id == $campus->id) selected @endif>
                                      {{ $campus->name }}
                                  </option>
                              @endforeach
                          </select>
                          <span class="text-danger">{{ $errors->first('campus_id') }}</span>
                      </div>
                  </div>
      
                  <!-- Course Select (Dynamically Populated) -->
                  <div class="col-md-3">
                      <div class="mb-1">
                          <label class="form-label" for="course">Course:</label>
                          <select name="course" id="course" class="form-select select2">
                              <option value="">--Choose Course--</option>
                              @foreach($courses as $course)
                                  <option value="{{ $course->id }}" @if(Request()->course == $course->id) selected @endif>
                                      {{ $course->name }}
                                  </option>
                              @endforeach
                          </select>
                          <span class="text-danger">{{ $errors->first('course') }}</span>
                      </div>
                  </div>
      
                  <!-- Semester -->
                  <div class="col-md-3">
                      <div class="mb-1">
                          <label class="form-label" for="semester">Semester:</label>
                          <select name="semester" id="semester" class="form-select select2">
                              <option value="ALL">All Semester</option>
                              @foreach($semesters as $semester)
                                  <option value="{{ $semester->id }}" @if(Request()->semester == $semester->id) selected @endif>
                                      {{ $semester->name }}
                                  </option>
                              @endforeach
                          </select>
                          <span class="text-danger">{{ $errors->first('semester') }}</span>
                      </div>
                  </div>
      
                  <!-- Academic Session -->
                  <div class="col-md-3">
                      <div class="mb-1">
                          <label class="form-label" for="academicSession">Academic Session:</label>
                          <select name="session" id="session" class="form-select">
                              <option value="">--Select Session--</option>
                              @foreach($sessions as $session)
                                  <option value="{{ $session->academic_session }}" @if(Request()->session == $session->academic_session) selected @endif>
                                      {{ $session->academic_session }}
                                  </option>
                              @endforeach
                          </select>
                          <span class="text-danger">{{ $errors->first('session') }}</span>
                      </div>
                  </div>
      
                  <!-- Month -->
                  <div class="col-md-3">
                      <div class="mb-1">
                          <label class="form-label" for="month">Month:</label>
                          <select name="month" id="month" class="form-select">
                              <option value="">--Select Month--</option>
                              @for($month = 1; $month <= 12; $month++)
                                  @php $month_text = date('F', strtotime('2023-' . $month . '-01')); @endphp
                                  <option value="{{ $month_text }}" @if(Request()->month == $month_text) selected @endif>
                                      {{ $month_text }}
                                  </option>
                              @endfor
                          </select>
                          <span class="text-danger">{{ $errors->first('month') }}</span>
                      </div>
                  </div>
      
                  <!-- Year -->
                  <div class="col-md-3">
                      <div class="mb-1">
                          <label class="form-label" for="year">Year:</label>
                          <select name="year" id="year" class="form-select select2">
                              <option value="">--Select Year--</option>
                              @for($year = (date('Y') - 1); $year <= date('Y'); $year++)
                                  <option value="{{ $year }}" @if(Request()->year == $year) selected @endif>
                                      {{ $year }}
                                  </option>
                              @endfor
                          </select>
                          <span class="text-danger">{{ $errors->first('year') }}</span>
                      </div>
                  </div>
      
                  <!-- Center -->
                  <div class="col-md-3">
                      <div class="mb-1">
                          <label class="form-label" for="center">Center:</label>
                          <select name="center" id="center" class="form-select select2">
                              <option value="">--Select Center--</option>
                              @foreach($centers as $center)
                                  <option value="{{ $center->center_code }}" @if(Request()->center == $center->center_code) selected @endif>
                                      {{ $center->center_code }} {{ $center->center_name }}
                                  </option>
                              @endforeach
                          </select>
                          <span class="text-danger">{{ $errors->first('center') }}</span>
                      </div>
                  </div>
      
                 
                
              </div>
          </div>
      </form>
      
    </div>
  </div>
  



<script>
  $(document).ready(function(){
  $('#course').change(function() {
var course=$('#course').val();
var formData = {course:course,"_token": "{{ csrf_token() }}"}; //Array 
$.ajax({
  url : "{{route('semester_list')}}",
  type: "POST",
  data : formData,
  success: function(data, textStatus, jqXHR){
    $('#semester').html(data);
  },
});
});
});
</script>
@endsection