@if($course_single)
<div class="clearfix"></div>
<div class="col-md-8 col-xs-12 @if($course_single->course_group==null) hidden @endif " @if($course_single->course_group_data()->count() < 1) style="display: none;" @endif>
    <br/>
    @if($application->course_id==126 && $application->lateral_entry=='yes')
    <label for="">Selected Department<span class="text-danger">*</span></label>
    @else
    <label for="">Course Preference<span class="text-danger">*</span></label>
    @endif
    <table id="tblLocations" class="table table-bordered admintable" cellpadding="0" cellspacing="0" border="1">
        <thead>
            <tr class="text-center info">
                <th>SN#</th>
                <th>Course</th>
            </tr>
        </thead>
        <tbody>
            @php $course_preferences = explode(',',$application->course_preferences); @endphp
            @foreach(array_filter($course_preferences) as $index_pref=>$course_preference_id)
            <tr>  
            @if($application->course_id==126 && $application->lateral_entry=='yes')
            <td>Department</td>
            @else
            <td>Preference
                    <span class="position_text">{{$index_pref+1}}</span>
                </td>
            @endif
                <td>{{$application->course_preference_array($course_preference_id)->course_description}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>


@endif
