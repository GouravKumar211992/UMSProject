<?php
namespace App\Http\Controllers\ums\Admin;

use App\Http\Controllers\Controller;
use App\Models\ums\Course;
use App\Models\ums\Result;
use App\Models\ums\Campuse;
use App\Models\ums\ExamFee;
use App\Models\ums\Semester;
use App\Models\ums\BackPaperBulk;
use App\Models\ums\AcademicSession;
use Illuminate\Http\Request;
use Auth;
use DB;
use App\Imports\BackPaperImport;
use Maatwebsite\Excel\Facades\Excel;

class BackPaperController extends Controller {

    public function bulkBackPaper(Request $request){
        $applications = BackPaperBulk::get();
        return view('ums.exam.bulk_back_paper',compact('applications'));
    }

    public function bulkBackPaperSave(Request $request){
    	$request->validate([
            'back_paper_file' => 'required',
        ]);
        if($request->hasFile('back_paper_file')){
			Excel::import(new BackPaperImport, $request->file('back_paper_file'));
		}
        return back()->with('success','Records Saved!');
    }

}

