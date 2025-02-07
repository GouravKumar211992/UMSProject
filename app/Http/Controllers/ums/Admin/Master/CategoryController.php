<?php

<<<<<<< HEAD
namespace App\Http\Controllers\Admin\Master;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\AdminController;
=======
namespace App\Http\Controllers\ums\Admin\Master;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\ums\AdminController;
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7

use App\Models\Category;
use App\Exports\CategoryExport;
use Maatwebsite\Excel\Facades\Excel;
use Auth;

class CategoryController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {   
        $categories = Category::orderBy('id', 'DESC');

        if($request->search) {
            $keyword = $request->search;
            $categories->where(function($q) use ($keyword){

                $q->where('name', 'LIKE', '%'.$keyword.'%');
            });
        }
        if(!empty($request->name)){
            
            $categories->where('name','LIKE', '%'.$request->name.'%');
        }
         $categories = $categories->paginate(10);
<<<<<<< HEAD
        return view('admin.master.category.index', [
=======
        return view('ums.master.category_list.category_list', [
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
            'page_title' => "Categories",
            'sub_title' => "records",
            'all_categories' => $categories
        ]);
    }

    public function add(Request $request)
    {
        
        return view('admin.master.category.addcategory', [
            'page_title' => "Add New",
            'sub_title' => "Category"
        ]);
    }

    public function addCategory(Request $request)
    {
        $request->validate([
            'category_name' => 'required'
        ]);
        $data = $request->all();
        $category = $this->create($data);
<<<<<<< HEAD
        return redirect()->route('get-categories')->with('success','Added Successfully.');
=======
        return redirect()->route('category_list')->with('success','Added Successfully.');
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
    }

    public function create(array $data)
    {
      return Category::create([
        'name' => $data['category_name'],
        'created_by' => 1,
        //'created_by' => Auth::guard('admin')->user()->id,
        'status' => $data['category_status'] == 'active'?1:0
      ]);
    }

    public function editCategory(Request $request)
    {
        $request->validate([
            'category_name' => 'required'
        ]);
        $status = $request['category_status'] == 'active'?1:0;
        $update_category = Category::where('id', $request->category_id)->update(['name' => $request->category_name, 'status' => $status, 'updated_by' => 1]);
<<<<<<< HEAD
        return redirect()->route('get-categories')->with('success','Update Successfully.');
=======
        return redirect()->route('category_list')->with('success','Update Successfully.');
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
        
    }


    public function editcategories(Request $request, $slug)
    {
        $selectedCategory = Category::Where('id', $slug)->first();

<<<<<<< HEAD
        return view('admin.master.category.editcategory', [
=======
        return view('ums.master.category_list.category_list_edit', [
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
            'page_title' => $selectedCategory->name,
            'sub_title' => "Edit Information",
            'selected_category' => $selectedCategory
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
        
        Category::where('id', $slug)->delete();
<<<<<<< HEAD
        return redirect()->route('get-categories')->with('success','Deleted Successfully.');
=======
        return redirect()->route('category_list')->with('success','Deleted Successfully.');
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
        
    }
    public function categoryExport(Request $request)
    {
        return Excel::download(new CategoryExport($request), 'Category.xlsx');
    } 
}