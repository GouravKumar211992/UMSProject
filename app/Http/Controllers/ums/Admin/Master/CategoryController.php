<?php

namespace App\Http\Controllers\ums\Admin\Master;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\ums\AdminController;

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
        return view('ums.master.category_list.category_list', [
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
        return redirect()->route('category_list')->with('success','Added Successfully.');
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
        return redirect()->route('category_list')->with('success','Update Successfully.');
        
    }


    public function editcategories(Request $request, $slug)
    {
        $selectedCategory = Category::Where('id', $slug)->first();

        return view('ums.master.category_list.category_list_edit', [
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
        return redirect()->route('category_list')->with('success','Deleted Successfully.');
        
    }
    public function categoryExport(Request $request)
    {
        return Excel::download(new CategoryExport($request), 'Category.xlsx');
    } 
}