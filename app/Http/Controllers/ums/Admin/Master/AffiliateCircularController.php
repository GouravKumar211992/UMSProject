<?php

<<<<<<< HEAD
namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AffiliateCircular;
use App\Models\UploadDocument;
=======
namespace App\Http\Controllers\ums\Admin\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ums\AffiliateCircular;
use App\Models\ums\UploadDocument;
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
use App\Exports\AffiliateInformationExport;
use App\Exports\AffiliateCircularExport;
use Maatwebsite\Excel\Facades\Excel;
use Validator;
class AffiliateCircularController extends Controller
{
   public function index()
   {    
       $data=AffiliateCircular::paginate(10);
<<<<<<< HEAD
     return view('admin.master.affiliate-circular.show',['items'=>$data]);
=======
     return view('ums.master.affiliate.affiliate_circular',['items'=>$data]);
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
   }

   public function addView()
   {    
      
<<<<<<< HEAD
     return view('admin.master.affiliate-circular.add');
   }
    public function add(Request $request)
   { 
     $validator = Validator::make($request->all(),[
        'affiliate_circular_description' => 'required',
    'circular_date' => 'required',
    'circular_file'   => 'required',
   
        ]);

     if ($validator->fails()) {    
      return back()->withErrors($validator)->withInput($request->all());
    }
     $data= new AffiliateCircular;
     $data->circular_description=$request->affiliate_circular_description;
     $data->circular_date=$request->circular_date;
     if($request->circular_file)
            {
              $data->addMediaFromRequest('circular_file')->toMediaCollection('circular_file');
            }
     $data->save();
     return redirect()->route('affiliate-circular')->with('success',' Added Successfully.');
   }
    public function edit($id)
   {
      $data=AffiliateCircular::find($id);
     return view('admin.master.affiliate-circular.edit',['info'=>$data]);
   }
    public function delete($id)
   {
        $data=AffiliateCircular::find($id);
        $data->delete();
       
     return redirect()->route('affiliate-circular')->with('success','  Deleted Successfully.');
    
   }
     public function update(Request $request,$id)
   {

      $update=AffiliateCircular::find($id);

       $update->circular_description=$request->get('affiliate_circular_description');
       $update->circular_date=$request->get('circular_date');
      if($request->circular_file)       
            {
              $update->addMediaFromRequest('circular_file')->toMediaCollection('circular_file');
            }
       $update->save();

        return redirect()->route('affiliate-circular')->with('success',' Updated Successfully.');
     
   }
=======
     return view('ums.master.affiliate.affiliate_circular_add');
   }
   public function add(Request $request)
{
    $validator = Validator::make($request->all(), [
        'circular_description' => 'required',
        'circular_date' => 'required',
        'circular_file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
    ]);

    if ($validator->fails()) {
        return back()->withErrors($validator)->withInput($request->all());
    }

    $data = new AffiliateCircular();
    $data->circular_description = $request->circular_description;
    $data->circular_date = $request->circular_date;

    
    if ($request->hasFile('circular_file')) {
        $filePath = $request->file('circular_file')->store('uploads/circulars', 'public');
        $data->circular_file = $filePath; 
    }

    $data->save();

    return back()->with('success', 'Added Successfully.');
}

    public function edit($id)
   {
      $data=AffiliateCircular::find($id);
     return view('ums.master.affiliate.affiliate_circular_edit',['info'=>$data]);
   }

   public function update(Request $request, $id)
   {
       $validator = Validator::make($request->all(), [
           'circular_description' => 'required',
           'circular_date' => 'required',
           'circular_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
       ]);
   
       if ($validator->fails()) {
           return back()->withErrors($validator)->withInput($request->all());
       }
   
       $update = AffiliateCircular::find($id);
   
       $update->circular_description = $request->circular_description;
       $update->circular_date = $request->circular_date;
   
       
       if ($request->hasFile('circular_file')) {
           
           if ($update->circular_file && \Storage::exists('public/' . $update->circular_file)) {
               \Storage::delete('public/' . $update->circular_file);
           }
   
           
           $filePath = $request->file('circular_file')->store('uploads/circulars', 'public');
           $update->circular_file = $filePath; 
       }
   
       $update->save();
   
       return back()->with('success', 'Updated Successfully.');
   }

   public function delete($id)
{
    $data = AffiliateCircular::find($id);

    if ($data->circular_file && \Storage::exists('public/' . $data->circular_file)) {
        \Storage::delete('public/' . $data->circular_file);
    }

    $data->delete();

    return back()->with('success', 'Deleted Successfully.');
}

>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
     public function affiliateCircularExport(Request $request)
    {
        return Excel::download(new AffiliateCircularExport($request), 'AffiliateCircular.xlsx');
    } 

    public function affiliateInformationView(Request $request)

    {  
        $affiliateInformation = UploadDocument::orderBy('id', 'DESC');
        if($request->search) {
            $keyword = $request->search;
            $affiliateInformation->where(function($q) use ($keyword){

                $q->where('name', 'LIKE', '%'.$keyword.'%');
            });
        }
        if(!empty($request->name)){
            
            $affiliateInformation->where('name','LIKE', '%'.$request->name.'%');
        }
        $affiliateInformation = $affiliateInformation->paginate(10);
           
        return view('admin.affiliate-information.view',['affiliateInformation'=>$affiliateInformation]);
    }
    public function affiliateInformationExport(Request $request)
    {
      return Excel::download(new AffiliateInformationExport($request),'AffiliateInformation.xlsx');   

    }

}
