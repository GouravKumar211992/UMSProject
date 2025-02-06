@extends('admin.layouts.app')
{{-- Web site Title --}} 
@section('title') Subject :: @parent @stop 
@section('content')

<section class="content mb-3">
	<form id="edit_subject_form" type="POST" action="{{route('edit-subject-form')}}">
	@csrf
		<div class="container-fluid">
					
					<div class="row mb-3 align-items-center">
						<div class="col-4">
							<a href="{{route('get-subjects')}}" class="btn btn-secondary btn-back">Go Back</a> 
						</div> 
						<div class="col-8 text-right">
							<a href="javascript:void(0);" onclick="submitCat();" class="btn btn-secondary btn-update">Update</a>
						</div>
						<div class="col-md-12">
							<div class="border-bottom mt-3 mb-2 border-innerdashed"> </div>
						</div>
					</div>

                   <div class="row">
				<section class="col-md-12 connectedSortable">
					<div class="row">
					<div class="col-md-4">
							<div class="form-group position-relative custom-form-group inner-formnew">
								<span class="form-label main-page">Student ID</span>
								<input id="student_id" name="student_id" type="text" value="{{$selected_fee->student_id}}" class="form-control" > 
								@if ($errors->has('student_id'))
                                <span class="text-danger">{{ $errors->first('student_id') }}</span>
                                @endif
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group position-relative custom-form-group inner-formnew">
								<span class="form-label main-page">Course Code</span>
								<input id="course_code" name="course_code" type="text" value="{{$selected_fee->course_code}}" class="form-control @error('course_code') is-invalid @enderror" placeholder="Enter Course Code here"> 
								@if ($errors->has('course_code'))
                                <span class="text-danger">{{ $errors->first('course_code') }}</span>
                                @endif
							</div>
						</div>
						
						<div class="col-md-4">
							<div class="form-group position-relative custom-form-group inner-formnew">
								<span class="form-label main-page">Semester</span>
								<input id="semester" name="semester" type="text" value="{{$selected_fee->semester}}" class="form-control @error('semester') is-invalid @enderror" placeholder="Enter Semester here"> 
								@if ($errors->has('semester'))
                                <span class="text-danger">{{ $errors->first('semester') }}</span>
                                @endif
							</div>
						</div>

					</div>
				</section>
				<section class="col-md-12 connectedSortable">
					<div class="row">
					<div class="col-md-4">
							<div class="form-group position-relative custom-form-group inner-formnew">
								<span class="form-label main-page">Registration Fee</span>
								<input id="registration_fee" name="registration_fee" type="text" value="{{$selected_fee->registration_fee}}" class="form-control @error('registration_fee') is-invalid @enderror" placeholder="Enter Registration Fee here"> 
								@if ($errors->has('registration_fee'))
                                <span class="text-danger">{{ $errors->first('registration_fee') }}</span>
                                @endif
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group position-relative custom-form-group inner-formnew">
								<span class="form-label main-page">Admission Fee</span>
								<input id="admission_fee" name="admission_fee" type="text" value="{{$selected_fee->admission_fee}}" class="form-control @error('admission_fee') is-invalid @enderror" placeholder="Enter Admission Fee here"> 
								@if ($errors->has('admission_fee'))
                                <span class="text-danger">{{ $errors->first('admission_fee') }}</span>
                                @endif
							</div>
						</div>
						
						<div class="col-md-4">
							<div class="form-group position-relative custom-form-group inner-formnew">
								<span class="form-label main-page">Exam Fee</span>
								<input id="exam_fee" name="exam_fee" type="text" value="{{$selected_fee->exam_fee}}" class="form-control @error('exam_fee') is-invalid @enderror" placeholder="Enter Exam Fee here"> 
								@if ($errors->has('exam_fee'))
                                <span class="text-danger">{{ $errors->first('exam_fee') }}</span>
                                @endif
							</div>
						</div>

					</div>
				</section>
				<section class="col-md-12 connectedSortable">
					<div class="row">
					<div class="col-md-4">
							<div class="form-group position-relative custom-form-group inner-formnew">
								<span class="form-label main-page">Tution Fee</span>
								<input id="tution_fee" name="tution_fee" type="text" value="{{$selected_fee->tution_fee}}" class="form-control @error('tution_fee') is-invalid @enderror" placeholder="Enter Tution Fee here"> 
								@if ($errors->has('tution_fee'))
                                <span class="text-danger">{{ $errors->first('tution_fee') }}</span>
                                @endif
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group position-relative custom-form-group inner-formnew">
								<span class="form-label main-page">Computer Fee</span>
								<input id="computer_fee" name="computer_fee" type="text" value="{{$selected_fee->computer_fee}}" class="form-control @error('computer_fee') is-invalid @enderror" placeholder="Enter Computer Fee here"> 
								@if ($errors->has('computer_fee'))
                                <span class="text-danger">{{ $errors->first('computer_fee') }}</span>
                                @endif
							</div>
						</div>
						
						<div class="col-md-4">
							<div class="form-group position-relative custom-form-group inner-formnew">
								<span class="form-label main-page">Library Fee</span>
								<input id="library_fee" name="library_fee" type="text" value="{{$selected_fee->library_fee}}" class="form-control @error('library_fee') is-invalid @enderror" placeholder="Enter Library Fee here"> 
								@if ($errors->has('library_fee'))
                                <span class="text-danger">{{ $errors->first('library_fee') }}</span>
                                @endif
							</div>
						</div>

					</div>
				</section>
				<section class="col-md-12 connectedSortable">
					<div class="row">
					<div class="col-md-4">
							<div class="form-group position-relative custom-form-group inner-formnew">
								<span class="form-label main-page">Caution Money</span>
								<input id="caution_money" name="caution_money" type="text" value="{{$selected_fee->caution_money}}" class="form-control @error('caution_money') is-invalid @enderror" placeholder="Enter Caution Money here"> 
								@if ($errors->has('caution_money'))
                                <span class="text-danger">{{ $errors->first('caution_money') }}</span>
                                @endif
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group position-relative custom-form-group inner-formnew">
								<span class="form-label main-page">Insurance Fee</span>
								<input id="insurance_fee" name="insurance_fee" type="text" value="{{$selected_fee->insurance_fee}}" class="form-control @error('insurance_fee') is-invalid @enderror" placeholder="Enter Insurance Fee here"> 
								@if ($errors->has('insurance_fee'))
                                <span class="text-danger">{{ $errors->first('insurance_fee') }}</span>
                                @endif
							</div>
						</div>
						
						<div class="col-md-4">
							<div class="form-group position-relative custom-form-group inner-formnew">
								<span class="form-label main-page">Student Welfare Fund</span>
								<input id="student_welfare_fund" name="student_welfare_fund" type="text" value="{{$selected_fee->student_welfare_fund}}" class="form-control @error('student_welfare_fund') is-invalid @enderror" placeholder="Enter Student Welfare Fund here"> 
								@if ($errors->has('student_welfare_fund'))
                                <span class="text-danger">{{ $errors->first('student_welfare_fund') }}</span>
                                @endif
							</div>
						</div>

					</div>
				</section>
				<section class="col-md-12 connectedSortable">
					<div class="row">
					<div class="col-md-4">
							<div class="form-group position-relative custom-form-group inner-formnew">
								<span class="form-label main-page">Game Fee</span>
								<input id="game_fee" name="game_fee" type="text" value="{{$selected_fee->game_fee}}" class="form-control @error('game_fee') is-invalid @enderror" placeholder="Enter Game Fee here"> 
								@if ($errors->has('game_fee'))
                                <span class="text-danger">{{ $errors->first('game_fee') }}</span>
                                @endif
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group position-relative custom-form-group inner-formnew">
								<span class="form-label main-page">Total Fee</span>
								<input id="total_fee" name="total_fee" type="text" value="{{Request::get('old_total_fee')}}" class="form-control" > 
								@if ($errors->has('total_fee'))
                                <span class="text-danger">{{ $errors->first('total_fee') }}</span>
                                @endif
							</div>
						</div>
						

					</div>
				</section>
			</div>
					
					

                </div>
</form>
            </section>

@stop

@section('styles')
    <style type="text/css"></style>
@stop 

@section('scripts')

@stop
