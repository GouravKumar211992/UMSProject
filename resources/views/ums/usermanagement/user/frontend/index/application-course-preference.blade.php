@if($course_single)
<div class="clearfix"></div>
<div class="col-md-8 @if($course_single->course_group==null) hidden @endif " @if($course_single->course_group_data()->count() < 1) style="display: none;" @endif>
    <br/>
    <label class="forLateralCouseText1">Course Preference</label>
    <table id="tblLocations" class="table table-bordered admintable" cellpadding="0" cellspacing="0" border="1">
        <thead>
            <tr class="text-center info">
                <th>SN#</th>
                <th>Course</th>
            </tr>
        </thead>
        <tbody>
            @foreach($course_single->course_group_data() as $index_pref=>$course)
            <tr class="@if($index_pref>0) forLateralCourses @endif">
                <td class="forLateralCouseText2" style="display: none;">Department</td>
                <td class="forLateralCouseText3">Preference
                    <span class="position_text">{{$index_pref+1}}</span>
                </td>
                <td>
                    <select name="course_preferences[]" class="form-control">
                        @if($index_pref > 0)
                        <option value="">None</option>
                        @endif
                        @foreach($course_single->course_group_data() as $index_pref_loop=>$course)
                        <option value="{{$course->id}}">{{$course->course_description}}</option>
                        @endforeach
                    </select>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>


@endif
