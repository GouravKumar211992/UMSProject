<?php
namespace App\Http\Controllers\ums\Admin\Master;

use App\Http\Controllers\Controller;
use App\Models\ums\Period;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\ums\AdminController;

use App\Exports\PeriodExport;
use Maatwebsite\Excel\Facades\Excel;
use Auth;
class PeriodController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {   
        $periods = Period::orderBy('id', 'DESC');

        if($request->search) {
            $keyword = $request->search;
            $periods->where(function($q) use ($keyword){

                $q->where('name', 'LIKE', '%'.$keyword.'%');
            });
        }
        if(!empty($request->name)){
            
            $periods->where('name','LIKE', '%'.$request->name.'%');
        }
         $periods = $periods->paginate(10);
        return view('ums.master.period_list.period_list', [
            'page_title' => "Periods",
            'sub_title' => "records",
            'all_periods' => $periods
        ]);
    }

    public function add(Request $request)
    {
        
        return view('ums.master.period_list.period_list', [
            'page_title' => "Add New",
            'sub_title' => "Period"
        ]);
    }

    public function addPeriod(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);
        //$data = $request->all();
        
     //   $data['time_rang'] = $request->start_time.'-'.$request->end_time;
       // dd($data);
        $period = new Period;
        $period->name = $request->name;
        $period->status = $request->period_status == 'active'?1:0;
        $period->time_rang = $request->start_time.'-'.$request->end_time;
        $period->save();
       // $period = $this->create($data);
        return redirect()->route('get-periods')->with('success','Added Successfully.');
    }

    public function create(array $data)
    {
        // dd($data);
      return Period::create([
        'name' => $data['name'],
        'created_by' => 1,
        //'created_by' => Auth::guard('admin')->user()->id,
        'status' => $data['period_status'] == 'active'?1:0,
      ]);
    }

    public function editPeriod(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'start_time' => 'required',
             'end_time' => 'required',
             'period_status' => 'required'
        ]);
        $status = $request['period_status'] == 'active'?1:0;
        $update_edit = Period::where('id', $request->period_id)->update(['name' => $request->name, 'status' => $status, 'time_rang'=>$request->start_time.'-'.$request->end_time]);
        return redirect()->route('get-periods')->with('success','Update Successfully.');
        
    }


    public function editperiods(Request $request, $slug)
    {
        $selectedperiod = Period::where('id', $slug)->first();
    
        if (!$selectedperiod) {
            return redirect()->route('get-periods')->with('error', 'Period not found.');
        }
    
        return view('ums.master.period_list.period_list_edit', [
            'page_title' => $selectedperiod->name,
            'sub_title' => "Edit Information",
            'selected_period' => $selectedperiod
        ]);
    }
    


    public function show()
    {
        return view('admin.ProductPeriod.view');
    }

    public function edit($id)
    {
        $productPeriod = ProductPeriod::find($id);
        $parents = ProductPeriod::whereNull('parent_id')->get();


        return view(
            'admin.master.period.edit',
            array(
                'parents' => $parents,
                'productPeriod' => $productPeriod
            )
        );
    }

    public function softDelete(Request $request,$slug) {
        
        Period::where('id', $slug)->delete();
        return redirect()->route('get-periods')->with('success','Deleted Successfully.');
        
    }
    public function periodExport(Request $request)
    {
        return Excel::download(new PeriodExport($request), 'Period.xlsx');
    } 
}