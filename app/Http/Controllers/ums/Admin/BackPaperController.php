<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Result;
use App\Models\Campuse;
use App\Models\ExamFee;
use App\Models\Semester;
use App\Models\BackPaperBulk;
use App\Models\AcademicSession;
use Illuminate\Http\Request;
use Auth;
use DB;
use App\Imports\BackPaperImport;
use Maatwebsite\Excel\Facades\Excel;

class BackPaperController extends Controller {

    public function bulkBackPaper(Request $request){
        $applications = BackPaperBulk::get();
        return view('admin.exam-paper-approval.bulk-back-paper',compact('applications'));
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

