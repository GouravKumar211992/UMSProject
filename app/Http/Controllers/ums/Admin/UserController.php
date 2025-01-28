<?php

namespace App\Http\Controllers\ums\Admin;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\ums\AdminController;



use App\Models\ums\EmailTemplate;
use App\Exports\UserExport;
use App\Exports\AdminExport;
use App\Models\ums\Admin;

use App\Models\Application;
use App\models\ums\EmailTemplate as UmsEmailTemplate;
use App\Models\ums\User;
use Illuminate\Support\Facades\Log;
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
        return view('ums.usermanagement.users.index', [
            'page_title' => "User",
            'sub_title' => "records",
            'all_users' => $users
        ]);
    }

    public function userPasswordChange(Request $request){
        $user = User::find($request->id);
        if($user){
            $user->password = 'dsmnru@123';
            $user->save();
        }
        return back()->with('success','Password is reset. The new password is dsmnru@123');
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
		if (!empty($request->gender)) {
            //
            $users->where('gender',$request->gender);
        }if (!empty($request->dob)) {
            //dd($request->dob);
            $users->where('date_of_birth',$request->dob);
        }
		if (!empty($request->marital)) {
            //dd($request->marital);
            $users->where('marital_status',$request->marital);
        }
        $users = $users->paginate(10);
        
        return view('ums.usermanagement.admins.admin_list', [
            'page_title' => "User",
            'sub_title' => "records",
            'all_users' => $users
        ]);
    }

    public function getTemplate(Request $request)
    {   
        $users = EmailTemplate::orderBy('id', 'DESC');
        
       
        $users = $users->paginate(10);
        return view('ums.usermanagement.email_list.email_template', [
            'page_title' => "User",
            'sub_title' => "records",
            'all_users' => $users
        ]);
    }

    public function add(Request $request)
    {
        
        return view('ums.admins.admin_list_add', [
            'page_title' => "Add New",
            'sub_title' => "User"
        ]);
    }

    public function addEmailTemplatePage(Request $request)
    {
        
        return view('admin.email.add_email_template', [
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
            'role' => 'required|numeric',
            'user_dob' => 'required',
            'gender' => 'required',
            'marital' => 'required',
        ]);
        $data = $request->all();
        
        
        $data['password'] = bcrypt($data['password']);
        
        // ye part maine add kiya hai 
      
            $user=$this->create($data);
            // dd($data);
            return redirect('admins');
        
     
    }
  




    public function addEmailTemplate(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'alias' => 'required',
            'subject' => 'required',
           
           
            
        ]);
        $data = $request->all();
        
         EmailTemplate::create($data);
        return redirect()->route('/email-template');
    }
   

    // ye maine banaye hai 
public function EditEmailTemplate(Request $request)
{
    $id=$request->user_id;
    $request->validate([
        'alias' => 'required',
        'subject' => 'required',
    ]);

   
    $emailTemplate = EmailTemplate::findOrFail($id);

   
    $emailTemplate->update([
        'alias' => $request->alias,
        'subject' => $request->subject,
        'status'=>$request->user_status,
        'message' => $request->message,
       
    ]);

    
    return redirect('email-template')->with('success', 'Email template updated successfully!');
}

public function editEmailTemplates(Request $request, $slug)
{
    // echo"helo";

    $selectedUser =  EmailTemplate::Where('id', $slug)->first();

    return view('ums.usermanagement.email_list.email_template_edit', [
        'selected_user' => $selectedUser
    ]);
}
// ----------------end-----------









    public function create(array $data)
    {
        
      return Admin::create([
        'name'              => $data['name'],
        'user_name'         => $data['email'],
        'email'             => $data['email'],
        'password'          => $data['password'],
        'mobile'            => $data['mobile'],
        'gender'            => $data['gender'],
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
        // dd($request->all());
        $update_category = Admin::where('id', $request->user_id)->update(['name' => $request->name,'mobile' => $request->mobile, 'role' => $request->role,'gender' => $request->gender,'marital_status' => $request->marital,'date_of_birth' => $request->user_dob, 'status' => $request->user_status, 'updated_by' => 1]);
        return redirect()->route('get-admin');
        
    }


    public function editusers(Request $request, $slug)
    {

        $selectedUser = Admin::Where('id', $slug)->first();

        return view('ums.usermanagement.admins.admin_list_edit', [
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
        return redirect('admins');
        
    }
    public function EmailsoftDelete(Request $request,$slug) {
        
        EmailTemplate::where('id', $slug)->delete();
        return redirect('email-template');
        
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