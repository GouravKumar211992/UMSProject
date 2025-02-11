<?php
namespace App\Imports;

use App\Models\CourseSwitching;
use App\Models\Enrollment;
use App\Models\Semester;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Illuminate\Http\Request;
use DB;


class CourseSwitchingImport implements ToModel, WithValidation, WithHeadingRow, WithCalculatedFormulas
{
    public function rules(): array
    {
        return [
            'roll_no' => ['required'],
            'name' => ['required'],
            'old_course_id' => ['required'],
            'new_course_id' => ['required'],
        ];
    }

    public function model(array $row)
    {
        $row = array_filter($row);
        $row['ip_address'] = \Request::ip();
        $roll_no = $row['roll_no'];
        $old_course_id = $row['old_course_id'];
        $new_course_id = $row['new_course_id'];

        // Course Transfer is allowed for only BVA(37)
        if($old_course_id == 69 || $old_course_id == 70){
            $courseSwitching = CourseSwitching::where('roll_no', $roll_no)
            ->where('old_course_id', $old_course_id)
            ->where('new_course_id', $new_course_id)
            ->first();
            if(!$courseSwitching) {
                $courseSwitching = new CourseSwitching;
                $courseSwitching->fill($row); 
                $courseSwitching->save();
                $student = Enrollment::where('roll_number', $roll_no)
                ->where('course_id', $old_course_id)
                ->first();
                if($student){
                    $courseSwitching->status = 1;
                    $courseSwitching->save();
                    $old_semesters = Semester::where('course_id',$old_course_id)->get();
                    foreach($old_semesters as $old_semester){
                        $new_semester = Semester::where('course_id',$new_course_id)
                        ->where('semester_number',$old_semester->semester_number)
                        ->first();

                        // upate all tables which are having semester wise data
                        if($new_semester){
                            DB::table('exam_fees')
                            ->where('roll_no',$roll_no)
                            ->where('course_id',$old_course_id)
                            ->where('semester',$old_semester->id)
                            ->update([
                                'course_id'=>$new_course_id,
                                'semester'=>$new_semester->id,
                            ]);
                            DB::table('student_subjects')
                            ->where('roll_number',$roll_no)
                            ->where('course_id',$old_course_id)
                            ->where('semester_id',$old_semester->id)
                            ->update([
                                'course_id'=>$new_course_id,
                                'semester_id'=>$new_semester->id,
                            ]);
                            DB::table('special_back_table_details')
                            ->where('roll_number',$roll_no)
                            ->where('course_id',$old_course_id)
                            ->where('semester_id',$old_semester->id)
                            ->update([
                                'course_id'=>$new_course_id,
                                'semester_id'=>$new_semester->id,
                            ]);
                            DB::table('internal_marks')
                            ->where('roll_number',$roll_no)
                            ->where('course_id',$old_course_id)
                            ->where('semester_id',$old_semester->id)
                            ->update([
                                'course_id'=>$new_course_id,
                                'semester_id'=>$new_semester->id,
                            ]);
                            DB::table('external_marks')
                            ->where('roll_number',$roll_no)
                            ->where('course_id',$old_course_id)
                            ->where('semester_id',$old_semester->id)
                            ->update([
                                'course_id'=>$new_course_id,
                                'semester_id'=>$new_semester->id,
                            ]);
                            DB::table('practical_mraks')
                            ->where('roll_number',$roll_no)
                            ->where('course_id',$old_course_id)
                            ->where('semester_id',$old_semester->id)
                            ->update([
                                'course_id'=>$new_course_id,
                                'semester_id'=>$new_semester->id,
                            ]);
                            DB::table('results')
                            ->where('roll_no',$roll_no)
                            ->where('course_id',$old_course_id)
                            ->where('semester',$old_semester->id)
                            ->update([
                                'course_id'=>$new_course_id,
                                'semester'=>$new_semester->id,
                            ]);
                        }

                        // upate all tables which are not having semester wise data
                        DB::table('enrollments')
                        ->where('roll_number',$roll_no)
                        ->where('course_id',$old_course_id)
                        ->update(['course_id'=>$new_course_id]);
                    }
                }
            }
        }
        return $courseSwitching;
    }
}
