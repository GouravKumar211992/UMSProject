<?php

namespace App\Http\Controllers\Faculty;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\AdminController;


use App\Faculty;
use App\User;
use App\Exports\UserExport;
use App\Exports\AdminExport;
use Maatwebsite\Excel\Facades\Excel;

use Auth;

class UserController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {   
        $users = User::orderBy('id', 'DESC');
        if($request->search) {
            $keyword = $request->search;
            $users->where(function($q) use ($keyword){

                $q->where('last_name', 'LIKE', '%'.$keyword.'%')
                    ->orWhere('first_Name', 'LIKE', '%'.$keyword.'%')
                    ->orWhere('email', 'LIKE', '%'.$keyword.'%')
                    ->orWhere('mobile', 'LIKE', '%'.$keyword.'%');
            });
        }
        if(!empty($request->first_Name)){
            
            $users->where('first_Name','LIKE', '%'.$request->first_Name.'%');
        }
        if (!empty($request->email)) {
            $users->where('email','LIKE', '%'.$request->email.'%');
        }
        if (!empty($request->mobile)) {
            
            $users->where('mobile','LIKE', '%'.$request->mobile.'%');
        }
        $users = $users->paginate(10);
        return view('admin.user.index', [
            'page_title' => "User",
            'sub_title' => "records",
            'all_users' => $users
        ]);
    }

    public function admins(Request $request)
    {   
        $users = Admin::orderBy('id', 'DESC');
        if($request->search) {
            $keyword = $request->search;
            $users->where(function($q) use ($keyword){

                $q->where('name', 'LIKE', '%'.$keyword.'%')
                    ->orWhere('email', 'LIKE', '%'.$keyword.'%')
                    ->orWhere('mobile', 'LIKE', '%'.$keyword.'%');
            });
        }
        if(!empty($request->name)){
            
            $users->where('name','LIKE', '%'.$request->name.'%');
        }
        if (!empty($request->email)) {
            $users->where('email','LIKE', '%'.$request->email.'%');
        }
        if (!empty($request->mobile)) {
            
            $users->where('mobile','LIKE', '%'.$request->mobile.'%');
        }
        $users = $users->paginate(10);
        return view('admin.user.admins', [
            'page_title' => "User",
            'sub_title' => "records",
            'all_users' => $users
        ]);
    }

    public function add(Request $request)
    {
        
        return view('admin.user.adduser', [
            'page_title' => "Add New",
            'sub_title' => "User"
        ]);
    }

    public function addUser(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:admins',
            'password' => 'required',
            'mobile' => 'required|numeric',
        ]);
        $data = $request->all();
        
        $user = $this->create($data);
        return redirect()->route('get-user');
    }

    public function create(array $data)
    {
        
      return Admin::create([
        'name'              => $data['name'],
        'user_name'         => $data['email'],
        'email'             => $data['email'],
        'password'          => $data['password'],
        'mobile'            => $data['mobile'],
        'gender'            => $data['gender1'],
        'marital_status'    => $data['marital'],
        'date_of_birth'     => $data['user_dob'],
        'created_by'        => 1,
        'status'            => $data['user_status']
      ]);
    }

    public function editUser(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'mobile' => 'required|numeric',
        ]);
        $update_category = Admin::where('id', $request->user_id)->update(['name' => $request->name,'mobile' => $request->mobile,'gender' => $request->gender1,'marital_status' => $request->marital,'date_of_birth' => $request->user_dob, 'status' => $request->user_status, 'updated_by' => 1]);
        return redirect()->route('get-user');
        
    }


    public function editusers(Request $request, $slug)
    {

        $selectedUser = Admin::Where('id', $slug)->first();

        return view('admin.user.edituser', [
            'page_title' => $selectedUser->name,
            'sub_title' => "Edit Information",
            'selected_user' => $selectedUser
        ]);
    }


    public function show()
    {
        return view('admin.ProductCategory.view');
    }

    public function edit($id)
    {
        $productCategory = ProductCategory::find($id);
        $parents = ProductCategory::whereNull('parent_id')->get();


        return view(
            'admin.master.category.edit',
            array(
                'parents' => $parents,
                'productCategory' => $productCategory
            )
        );
    }

    public function softDelete(Request $request,$slug) {
        
        Admin::where('id', $slug)->delete();
        return redirect()->route('get-user');
        
    }
    public function userExport(Request $request)
    {
        return Excel::download(new UserExport($request), 'User.xlsx');
    }
    public function adminExport(Request $request)
    {
        return Excel::download(new AdminExport($request), 'Admin.xlsx');
    }
}