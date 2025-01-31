<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\AdminController;
use App\Models\Result;

use App\Imports\TrBulkUploadImport;
use Maatwebsite\Excel\Facades\Excel;

class TrBulkUploadController extends AdminController
{

    public function bulkTrUploading(Request $request){
    	$request->validate([
            'upload_file' => 'required',
        ]);
        if($request->hasFile('upload_file')){
			Excel::import(new TrBulkUploadImport, $request->file('upload_file'));
		}
        return back()->with('success','Records Saved!');
    }


}