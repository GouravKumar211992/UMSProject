<?php

namespace App\Http\Controllers\Faculty;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\AdminController;

use App\Faculty;
use App\Imports\ICardsImport;
use Maatwebsite\Excel\Facades\Excel;

use App\Models\Student;
use App\Models\Application;
use App\Models\Course;
use App\Models\Category;
use App\Models\PermanentAddress;
use App\Models\UploadDocuments;
use App\Models\Icard;
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
        return view('admin.cards.index', [
            'students' => $students
        ]);
    }

    public function singleIcard(Request $request){
		return redirect('admin/bulk-icard-print?id='.$request->id);
        $data['icard'] = Icard::find($request->id);
        return view('admin.cards.icard',$data);
    }

    public function singleIcardDelete(Request $request){
        Icard::whereId($request->id)->delete();
        return back()->with('success','Deleted Successfully');
    }

    public function bulkIcardPrint(Request $request){
		if($request->id){
			$data['icards'] = Icard::whereId($request->id)->paginate(100);
		}else{
			$data['icards'] = Icard::paginate(500);
		}
        return view('admin.cards.bulk-icard',$data);
    }

    public function bulkIcardsSave(Request $request)
    {   

        if($request->hasFile('icard_file')){

			Excel::import(new ICardsImport, $request->file('icard_file'));

		}

        return back()->with('success','Records Saved!');
    }



    public function bulkIcardsFiles(Request $request, $type)
    {

        return view('admin.cards.bulk-icards-files',[
			'type' => $type
		]);
    }

    public function bulkIcardsFilesSave(Request $request, $type){

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