<?php


namespace App\Http\Controllers\ums\Admin\Master;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\ums\AdminController;

use App\Models\Campuse;
use App\Exports\CampusExport;
use Maatwebsite\Excel\Facades\Excel;
use Auth;

class CampusController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {   
        $campuses = Campuse::orderBy('id', 'DESC');

        if($request->search) {
            $keyword = $request->search;
            $campuses->where(function($q) use ($keyword){

                $q->where('name', 'LIKE', '%'.$keyword.'%');
            });
        }
        if(!empty($request->name)){
            
            $campuses->where('name','LIKE', '%'.$request->name.'%');
        }
        if ($request->type!=null) {
            $campuses->where('is_affiliated',$request->type);
        }
        $campuses = $campuses->paginate(10);
       
        return view('ums.master.campus_list.campus_list', [
            'page_title' => "Campuse",
            'sub_title' => "records",
            'campuses' => $campuses,
        ]);
    }

    public function add(Request $request)
    {
        return view('admin.master.campus.addcampus', [
            'page_title' => "Add New",
            'sub_title' => "Campus",
        ]);
    }

    public function addCampus(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'type' => 'required',
            'campus_code' => 'required',
        ]);

        $data = $request->all();
        
        if($request->type=='AFFILIATED COLLEGE')
        {
            $data['is_affiliated']=1;
        }
        else{
            $data['is_affiliated']=0;
        }
       // dd($data);
        $campuses = new Campuse();
        $campuses->fill($data);
        $campuses->save();
        return redirect()->route('campus_list')->with('success','Added Successfully');
    }

    public function editCampus(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'is_affiliated' => 'required',
            'campus_code' => 'required',
        ]);
        $update_category = Campuse::where('id', $request->campus_id)->update(['campus_code'=>$request->campus_code,'name' => $request->name,'is_affiliated' => $request->is_affiliated, 'updated_by' => 1,'short_name' => $request->short_name, 'email' => $request->email,'website' => $request->website, 'contact' => $request->contact, 'address' => $request->address]);
        //dd($update_category);
        return redirect()->route('campus_list')->with('success','Updated Successfully');
    }


    public function editcampuses(Request $request, $slug)
    {
        $selectedCampuse = Campuse::where('id', $slug)->first();
        return view('ums.master.campus_list.campus_list_edit', [
            'page_title' => $selectedCampuse->name,
            'sub_title' => "Edit Information",
            'selected_campuse' => $selectedCampuse,
        ]);
    }

    public function softDelete(Request $request,$slug) {
        
        Campuse::where('id', $slug)->delete();
        return redirect()->route('campus_list')->with('success','Deleted Successfully');
        
    }
    public function campusExport(Request $request)
    {
        return Excel::download(new CampusExport($request), 'Campuses.xlsx');
    } 

    
}