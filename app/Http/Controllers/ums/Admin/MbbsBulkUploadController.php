<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\AdminController;
use App\Models\StudentAllFromOldAgency;

use App\Imports\MBBSBulkUploadImport;
use Maatwebsite\Excel\Facades\Excel;

class MbbsBulkUploadController extends AdminController
{
    public function mbbsBulkUpload(Request $request)
    {
    	$request->validate([
            'mbbs_permission_file' => 'required',
        ]);
        if($request->hasFile('mbbs_permission_file')){
			Excel::import(new MBBSBulkUploadImport, $request->file('mbbs_permission_file'));
		}
        return back()->with('success','Records Saved!');
    }

    public function MBBSbulkPermissionCancel(Request $request){
        $updateArray = [
            'regular_permission' => 'Not Allowed',
            'supplementary_permission' => 'Not Allowed',
            'challenge_permission' => 'Not Allowed',
            'scrutiny_permission' => 'Not Allowed',
            'status_description' => 'You Are Not Allowed'
        ];
        StudentAllFromOldAgency::where('course_id',$request->course_id)->update($updateArray);
        return back()->with('success','All Permissions are removed');
    }

   
}