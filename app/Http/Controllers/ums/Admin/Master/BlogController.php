<?php

namespace App\Http\Controllers\Admin\master;

use App\Models\Blog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use App\Http\Controllers\AdminController;

use App\Exports\PeriodExport;
use Maatwebsite\Excel\Facades\Excel;
use Auth;

class BlogController extends AdminController
{
        public function __construct()
        {
            parent::__construct();
        }
    
        public function index(Request $request)
        {   
            $blogs = Blog::orderBy('id', 'DESC');
    
            if($request->search) {
                $keyword = $request->search;
                $blogs->where(function($q) use ($keyword){
    
                    $q->where('name', 'LIKE', '%'.$keyword.'%');
                });
            }
            if(!empty($request->name)){
                
                $blogs->where('name','LIKE', '%'.$request->name.'%');
            }
             $blogs = $blogs->paginate(10);
            return view('admin.master.blog.index', [
                'page_title' => "Blog",
                'sub_title' => "records",
                'blogs' => $blogs
            ]);
        }
    
        public function create(Request $request)
        {
          //  dd('.');
            return view('admin.master.blog.create', [
                'page_title' => "Add New Blog",
                'sub_title' => "Blog"
            ]);
        }
    
        public function store(Request $request)
        {
            $request->validate([
                'name' => 'required'
            ]);
            $data = $request->all();
            
            $blog = new Blog;
            $blog->name = $request->name;
            $blog->status = $request->status == 'active'?1:0;
            $blog->save();
           // $blog = $this->create($data);
            return redirect()->route('get-blogs')->with('success','Added Successfully.');
        }
    
        public function edit(Request $request, $slug)
        {
            $blog = Blog::find($slug);
           // dd($blog);
            return view('admin.master.blog.edit', [
                'page_title' => 'Edit Blog',
                'sub_title' => "Edit Information",
                'blog' => $blog
            ]);
          //  return view('banners.edit')->withSetting($setting)->withBanner($banner);
        }
    
    
    
        public function update(Request $request)
        {
            $request->validate([
                'name' => 'required|unique:blogs,name,'.$request->id,
            ]);
            $data = $request->all();
            
            $blog = new Blog;
            dd($data);
            $blog->name = $request->name;
            $blog->status = $request->status == 'active'?1:0;
            $blog->save();
           // $blog = $this->create($data);
            return redirect()->route('get-blogs')->with('success','Added Successfully.');
            
        }
    
    
       
        public function show()
        {
            return view('admin.ProductPeriod.view');
        }
    
    
        public function softDelete(Request $request,$slug) {
            
            Blog::where('id', $slug)->delete();
            return redirect()->route('get-blogs')->with('success','Deleted Successfully.');
            
        }
        public function export(Request $request)
        {
            return Excel::download(new BlogExport($request), 'Blog.xlsx');
        } 
    }
