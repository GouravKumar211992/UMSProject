<?php

namespace App\Http\Controllers\ums\Admin;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\ums\AdminController;

use App\Admin;
use App\Imports\ICardsImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\ums\Student;
use App\Models\ums\Application;
use App\Models\ums\ExamFee;
use App\Models\ums\Course;
use App\Models\ums\Category;
use App\Models\ums\PermanentAddress;
use App\Models\ums\UploadDocuments;
use App\Models\ums\Icard;
use App\Models\ums\Result;
use Illuminate\Support\Facades\Storage;

class IcardsController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function bulkIcards(Request $request){
        return view('admin.cards.bulk');
    }

    public function icardList(Request $request){
        $students = Icard::latest()->paginate(10);
        return view('ums.icards.card_list', [
            'students' => $students
        ]);
    }

    public function singleIcard(Request $request){
		//return redirect('admin/bulk-icard-print?id='.$request->id);
        $data['icard'] = Icard::find($request->id);
       
        return view('ums.icards.single_icard',$data);
    }

    public function singleIcardDelete(Request $request){
        Icard::whereId($request->id)->delete();
        return back()->with('success','Deleted Successfully');
    }

    public function bulkIcardPrint(Request $request) {
        $campus_id = 1;
    
        
        $roll_nos = Result::where('semester_final', 1)
            ->join('courses', 'courses.id', '=', 'results.course_id')
            ->where('campus_id', $campus_id)
            ->distinct()
            ->pluck('roll_no')
            ->toArray();
    
        
        $query = Icard::select('icards.*')
            ->join('exam_fees', 'exam_fees.roll_no', '=', 'icards.roll_no')
            ->join('results', 'results.roll_no', '=', 'icards.roll_no')
            ->join('courses', 'courses.id', '=', 'results.course_id');
    
        
        if ($request->id) {
            $query->where('icards.id', $request->id);
        }
    
       
        $query = $query->where('courses.campus_id', $campus_id)
            ->whereNotIn('results.roll_no', $roll_nos)
            ->distinct('icards.roll_no')
            ->limit(10) 
            ->get(); 
    
        $data['icards'] = $query;
        return view('ums.icards.bulk_icard_print', $data);
    }

    public function bulkIcardsSave(Request $request)
    {  // dd($request->all());
	$request->validate([
					'icard_file' => 'required',
           		]);

        if($request->hasFile('icard_file')){

			Excel::import(new ICardsImport, $request->file('icard_file'));

		}

        return back()->with('success','Records Saved!');
    }



    public function bulkIcardsFiles(Request $request, $type)
    {

        return view('ums.icards.bulk_icard_print',[
			'type' => $type
		]);
    }

    public function bulkIcardsFilesSave(Request $request, $type){
			
			$request->validate([
					'icard_file' => 'required',
           		]);

		if($request->hasFile('icard_file'))
		{
			foreach ($request->file('icard_file') as $key => $file) {
				$fileName = $file->getClientOriginalName();
				$roll_no = pathinfo($fileName, PATHINFO_FILENAME); 

				$idCard = Icard::where('roll_no', $roll_no)->first();

				if(!$idCard) continue;

				if($type == 'photos') {
					$idCard->addMediaFromRequest("icard_file[$key]")->toMediaCollection('profile_photo');
				}

				if($type == 'signatures') {
					$idCard->addMediaFromRequest("icard_file[$key]")->toMediaCollection('signature');
				}

				if($type == 'fee-recipts') {
					$idCard->addMediaFromRequest("icard_file[$key]")->toMediaCollection('fee_recipt');
				}
			}
		}

        return back()->with('success','Files has Saved!');
    }



}