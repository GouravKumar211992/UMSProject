<?php
namespace App\Imports;

use App\Models\Semester;
use App\Models\ExamSchedule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;


class ExamScheduleImport implements ToModel, WithValidation, WithHeadingRow, WithCalculatedFormulas
{
    public function rules(): array
    {
        return [
            'campus' => ['required'],
            'courses_id' => ['required'],
            'courses_name' => ['required'],
            'semester_id' => ['required'],
            'semester_name' => ['required'],
            'date' => ['required'],
            'shift' => ['required'],
            'paper_code' => ['required'],
            'paper_name' => ['required'],
            'year' => ['required'],
            'schedule_count' => ['required'],
        ];
    }

    public function model(array $row)
    {
        $row = (object)$row;
        $semester = Semester::withTrashed()
        ->select('semesters.*')
        ->join('courses','courses.id','semesters.course_id')
        ->where('campus_id',$row->campus)
        ->where('semesters.course_id',$row->courses_id)
        ->where('semester_number',$row->semester_id)
        ->first();
        if(!$semester){
            return false;
        }
        $semester_id = $semester->id;
        $exam = ExamSchedule::where('courses_id',$row->courses_id)
        ->where('semester_id',$semester_id)
        ->where('paper_code',$row->paper_code)
        ->where('year',$row->year)
        ->where('schedule_count',$row->schedule_count)
        ->first();
        if(!$exam){
            $examArray = [
                'campus' => $row->campus,
                'courses_id' => $row->courses_id,
                'courses_name' => $row->courses_name,
                'semester_id' => $row->semester_id,
                'semester_name' => $row->semester_name,
                'date' => date('Y-m-d',strtotime($row->date)),
                'shift' => $row->shift,
                'paper_code' => $row->paper_code,
                'paper_name' => $row->paper_name,
                'year' => $row->year,
                'schedule_count' => $row->schedule_count,
                // 'created_at' => date('Y-m-d'),
                // 'updated_at' => date('Y-m-d'),
            ];
            ExamSchedule::insert($examArray);
        }
    }
}
