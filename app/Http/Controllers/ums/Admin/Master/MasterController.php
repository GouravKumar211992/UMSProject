<?php

namespace App\Http\Controllers\Admin\Master;

use App\Models\ProductCategory;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\AdminController;

use App\Models\Category;
use App\Models\Service;
use App\Models\Brand;

class MasterController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {

        $category_count = Category::whereNull('deleted_at')->get()->count();
        $service_count = Service::whereNull('deleted_at')->get()->count();
        $brand_count = Brand::whereNull('deleted_at')->get()->count();

        return view('admin.master.index', [
            'page_title' => "Masters",
            'sub_title' => date('M d, Y'),
            'category_count' => $category_count,
            'service_count' => $service_count,
            'brand_count' => $brand_count
        ]);
    }
}