@extends('ums.admin.admin-meta')
@section('content')


<section class="content mb-3">
	<form id="cat_form" method="POST" action="{{route('add_question_bank')}}" onsubmit='disableButton()' enctype="multipart/form-data">
		@csrf
		<div class="container-fluid">

			<div class="row">
				<div class="col-lg-12">
					<div class="tabData">

						<h6 class="mt-2">Add Question Bank</h6>
						<div class="mt-2 mb-2">
							<div class="dashbed-border-bottom"></div>
						</div>

						{{-- @include('partials.notifications') --}}

						<div class="pb-4">

							<div class="row">

								<div class="col-md-4">
									<div class="form-group">
										<label for="name">Campus Name</label>
										<select class="form-control selectpicker-back campus_id" id="campus_id" name="campus_id" required>
		                                    <option value="">--Select Campus--</option>
		                                    @foreach($campuses as $campuse)
		                                    <option value="{{$campuse->id}}" {{(old('campus_id')=="$campuse->id")? 'selected': ''}} >{{$campuse->name}}</option>
		                                    @endforeach
		                                </select>
										@if($errors->has('campus_id'))
											<span class="text-danger">{{ $errors->first('campus_id') }}</span>
										@endif
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="name">Program</label>
										<select class="form-control selectpicker" id="program_id" name="program_id" required>
		                                    <option value="">Select Program</option>
		                                    @foreach($programs as $program)
		                                     <option value="{{$program->id}}" {{(old('program')=="$program->id")? 'selected': ''}}>{{$program->name}}</option>
		                                    @endforeach
		                                </select>
										@if($errors->has('program_id'))
											<span class="text-danger">{{ $errors->first('program_id') }}</span>
										@endif
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label for="name">Course</label>
										<select class="form-control selectpicker-back" id="course_id" name="course_id" required>
		                                    <option value="">Select Course</option>
		                                </select>
										@if($errors->has('course_id'))
											<span class="text-danger">{{ $errors->first('course_id') }}</span>
										@endif
									</div>
								</div>

								<div class="col-md-4" id="phd_title_show">
									<div class="form-group">
										<label for="name">Title / Topic</label>
										<input type="text" name="phd_title" class="form-control">
										@if($errors->has('phd_title'))
											<span class="text-danger">{{ $errors->first('phd_title') }}</span>
										@endif
									</div>
								</div>
								<div class="col-md-4" id="synopsis_show">
									<div class="form-group">
										<label for="name">Synopsis</label>
										<input type="file" class="form-control selectpicker-back" name="synopsis_file" accept="application/pdf">
										@if($errors->has('synopsis_file'))
											<span class="text-danger">{{ $errors->first('synopsis_file') }}</span>
										@endif
									</div>
								</div>
								<div class="col-md-4" id="thysis_show">
									<div class="form-group">
										<label for="name">Thysis</label>
										<input type="file" class="form-control selectpicker-back" name="thysis_file" accept="application/pdf">
										@if($errors->has('thysis_file'))
											<span class="text-danger">{{ $errors->first('thysis_file') }}</span>
										@endif
									</div>
								</div>
								<div class="col-md-4" id="journal_paper">
									<div class="form-group">
										<label for="name">Journal Paper</label>
										<input type="file" class="form-control selectpicker-back" name="journal_paper_file" accept="application/pdf">
										@if($errors->has('journal_paper_file'))
											<span class="text-danger">{{ $errors->first('journal_paper_file') }}</span>
										@endif
									</div>
								</div>
								<div class="col-md-4" id="seminar_show">
									<div class="form-group">
										<label for="name">Seminar</label>
										<input type="file" class="form-control selectpicker-back" name="seminar_file" accept="application/pdf">
										@if($errors->has('seminar_file'))
											<span class="text-danger">{{ $errors->first('seminar_file') }}</span>
										@endif
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="name">Branch</label>
										<select class="form-control selectpicker-back" id="branch_id" name="branch_id" required>
		                                    <option value="">Select Branch</option>
		                                </select>
										@if($errors->has('branch_id'))
											<span class="text-danger">{{ $errors->first('branch_id') }}</span>
										@endif
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label for="name">Semester</label>
										<select class="form-control selectpicker-back" id="semester_id" name="semester_id" required>
		                                    <option value="">Select Semester</option>
		                                </select>
										@if($errors->has('semester_id'))
											<span class="text-danger">{{ $errors->first('semester_id') }}</span>
										@endif
									</div>
								</div>
								 
								<div class="col-md-4">
									<div class="form-group">
										<label for="name">Subject Code</label>
										<select class="form-control selectpicker-back" id="sub_code" name="sub_code" required>
		                                    <option value="">Select Subject Code</option>
		                                </select>
										@if($errors->has('sub_code'))
											<span class="text-danger">{{ $errors->first('sub_code') }}</span>
										@endif
									</div>
								</div>

								
								<div class="col-md-4">
									<div class="form-group">
										<label for="name">Session</label>
										<select class="form-control selectpicker-back" id="session" name="session" required>
		                                    <option value="">Select Session</option>
		                                    @foreach($sessions as $session)
		                                    
		                                    <option value="{{$session->academic_session}}" {{(old('session')=="$session->id")? 'selected': ''}} >{{$session->academic_session}}</option>
		                                    @endforeach
		                                </select>
										@if($errors->has('session'))
											<span class="text-danger">{{ $errors->first('session') }}</span>
										@endif
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="name">File</label>
										<input type="file" class="form-control selectpicker-back" name="question_bank_file" accept="application/pdf" required>
										@if($errors->has('session'))
											<span class="text-danger">{{ $errors->first('session') }}</span>
										@endif
									</div>
								</div>


								<div class="col-md-12 col-lg-12 mt-4">
									<input type="button"  onclick="this.form.reset();" class="btn btn-secondary fa fa-undo" value="Reset"/>
									<button id="btn" class="btn btn-primary"><i class="fa fa-send"></i> Submit</button>
								</div>

							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
</section>
@endsection

