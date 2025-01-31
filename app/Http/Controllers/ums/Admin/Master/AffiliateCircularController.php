<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AffiliateCircular;
use App\Models\UploadDocument;
use App\Exports\AffiliateInformationExport;
use App\Exports\AffiliateCircularExport;
use Maatwebsite\Excel\Facades\Excel;
use Validator;
class AffiliateCircularController extends Controller
{
   public function index()
   {    
       $data=AffiliateCircular::paginate(10);
     return view('admin.master.affiliate-circular.show',['items'=>$data]);
   }

   public function addView()
   {    
      
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
