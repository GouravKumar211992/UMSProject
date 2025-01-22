<?php

namespace App\Http\Controllers;

use Yajra\DataTables\Facades\DataTables;
use App\Models\StockAccount;
use App\Models\Organization;
use App\Models\OrganizationCompany;
use App\Models\Category;
use App\Models\Group;
use App\Models\Ledger;
use App\Models\Item;
use App\Models\Book;
use App\Models\UserOrganizationMapping;
use App\Models\EmployeeOrganizationEmployee;
use App\Http\Requests\StockAccountRequest;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use App\Helpers\AccountHelper;
use App\Models\Trait\DefaultGroupCompanyOrg;
use Auth;

class StockAccountController extends Controller
{
    public function index(Request $request, $id = null)
    {
        $user = Helper::getAuthenticatedUser();
        $userType = Helper::userCheck()['type'];
        $orgIds = [];
        if ($userType === 'employee') {
            $orgIds = EmployeeOrganizationEmployee::where('employee_id', $user->id)
                ->pluck('organization_id')
                ->toArray();
        } elseif ($userType === 'user') {
            $orgIds = UserOrganizationMapping::where('user_id', $user->id)
                ->pluck('organization_id')
                ->toArray();
        }
        $groupIds = Organization::whereIn('id', $orgIds)
            ->pluck('group_id')
            ->toArray();
        $companies = OrganizationCompany::whereIn('group_id', $groupIds)->get();
        $categories = Category::all();
        $subCategories = Category::all(); 
        $ledgerGroups = Group::all();
        $ledgers = Ledger::all();
        $items = Item::all();
        $stockAccount = StockAccount::all();
        $erpBooks = Book::all(); 
        
        if ($request->ajax()) {
            $stockAccounts = StockAccount::with([
                'organization', 'group', 'company', 'ledgerGroup',
                'ledger', 'category', 'subCategory', 'item'
            ])
            ->orderBy('group_id')
            ->orderBy('company_id') 
            ->orderBy('organization_id')
            ->get();

            return DataTables::of($stockAccounts)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    return '<span class="badge rounded-pill ' . 
                        ($row->status == 'active' ? 'badge-light-success' : 'badge-light-danger') . 
                        ' badgeborder-radius">' . ucfirst($row->status) . '</span>';
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('stock-accounts.edit', $row->id);
                    $deleteUrl = route('stock-accounts.destroy', $row->id);
                    return '<div class="dropdown">
                                <button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown">
                                    <i data-feather="more-vertical"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="' . $editUrl . '">
                                       <i data-feather="edit-3" class="me-50"></i>
                                        <span>Edit</span>
                                    </a>
                                    <form action="' . $deleteUrl . '" method="POST" class="dropdown-item">
                                        ' . csrf_field() . method_field('DELETE') . '
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i data-feather="trash" class="me-50"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </div>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('procurement.stock-account.index', compact(
            'companies', 'categories', 'subCategories', 'ledgerGroups', 'ledgers', 'items', 'stockAccount','erpBooks'
        ));
    }

    public function store(StockAccountRequest $request)
    {
        $validated = $request->validated();
        $user = Helper::getAuthenticatedUser();
        $organization = $user->organization;
        $groupId = $organization->group_id;
     
        if (!isset($validated['stock_accounts']) || empty($validated['stock_accounts'])) {
            $existingAccounts = StockAccount::where('group_id', $groupId)->get();
            foreach ($existingAccounts as $existingAccount) {
                $existingAccount->delete();
            }

            return response()->json([
                'status' => true,
                'message' => 'All Record have been deleted.',
                'deleted' => count($existingAccounts),
            ], 200);
        }
        $existingAccounts = StockAccount::where('group_id', $groupId)->get();
        $insertData = [];
        $updateResults = [];
        $deleteResults = [];
        $incomingIds = collect($validated['stock_accounts'])->pluck('id')->toArray();
        foreach ($validated['stock_accounts'] as $stockAccountData) {
            if (isset($stockAccountData['id']) && $stockAccountData['id']) {
                $existingAccount = StockAccount::find($stockAccountData['id']);
                if ($existingAccount) {
                    $existingAccount->update([
                        'group_id' => $groupId,
                        'company_id' => $validated['company_id'],
                        'organization_id' => $validated['organization_id'],
                        'ledger_group_id' => $stockAccountData['ledger_group_id'] ?? null,
                        'ledger_id' => $stockAccountData['ledger_id'] ?? null,
                        'category_id' => $stockAccountData['category_id'] ?? null,
                        'sub_category_id' => $stockAccountData['sub_category_id'] ?? null,
                        'item_id' => $stockAccountData['item_id'] ?? null,
                        'book_id' => $stockAccountData['book_id'] ?? null,
                    ]);
                    $updateResults[] = $existingAccount;
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => "Stock account with ID {$stockAccountData['id']} not found.",
                    ], 404);
                }
            } else {
                $newStockAccount = StockAccount::create([
                    'group_id' =>$groupId,
                    'company_id' => $stockAccountData['company_id'],
                    'organization_id' => $stockAccountData['organization_id'],
                    'ledger_group_id' => $stockAccountData['ledger_group_id'] ?? null,
                    'ledger_id' => $stockAccountData['ledger_id'] ?? null,
                    'category_id' => $stockAccountData['category_id'] ?? null,
                    'sub_category_id' => $stockAccountData['sub_category_id'] ?? null,
                    'item_id' => $stockAccountData['item_id'] ?? null,
                    'book_id' => $stockAccountData['book_id'] ?? null,
                ]);
                $insertData[] = $newStockAccount;
            }
        }

        foreach ($existingAccounts as $existingAccount) {
            if (!in_array($existingAccount->id, $incomingIds)) {
                $existingAccount->delete();
                $deleteResults[] = $existingAccount;
            }
        }
        return response()->json([
            'status' => true,
            'message' => 'Record processed successfully.',
            'inserted' => count($insertData),
            'updated' => count($updateResults),
            'deleted' => count($deleteResults),
        ], 200);
    }

    public function testLedgerGroupAndLedgerId(Request $request)
    {
        $organizationId = $request->query('organization_id', 5);
        $itemId = $request->query('item_id','29');
        $bookId = $request->query('book_id','1');  
        if ($itemId && is_string($itemId)) {
            $itemId = explode(',', $itemId);
        }
        $ledgerData = AccountHelper::getStockLedgerGroupAndLedgerId( $organizationId, $itemId, $bookId);
        if ($ledgerData) {
            return response()->json($ledgerData);
        }
        return response()->json(['message' => 'No data found for the given parameters'], 404);
    }

    public function getOrganizationsByCompany($companyId)
    {
        $organizations = Organization::where('company_id', $companyId)
            ->where('status', 'active')
            ->get();

        return response()->json(['organizations' => $organizations]);
    }

    public function getDataByOrganization($organizationId)
    {
        $categories = Category::withDefaultGroupCompanyOrg() 
            ->where('organization_id', $organizationId)
            ->whereNull('parent_id')
            ->where('status', 'active')
            ->get();
    
        $ledgers = Ledger::withDefaultGroupCompanyOrg()  
            ->where('organization_id', $organizationId)
            ->get();
    
        $erpBooks = Book::withDefaultGroupCompanyOrg()  
            ->where('organization_id', $organizationId)
            ->where('status', 'active')
            ->get();
    
        $items = Item::withDefaultGroupCompanyOrg() 
            ->where('organization_id', $organizationId)
            ->where('status', 'active')
            ->get();
    
        return response()->json([
            'categories' => $categories,
            'ledgers' => $ledgers,
            'erpBooks' => $erpBooks,
            'items' => $items
        ]);
    }
    
    public function getCategoriesByOrganization(Request $request, $organizationId)
    {
        $searchTerm = $request->input('search', ''); 
        $query = Category::withDefaultGroupCompanyOrg() 
            ->where('organization_id', $organizationId)
            ->whereNull('parent_id')
            ->where('type', 'product')
            ->where('status', 'active');
            
        if ($searchTerm) {
            $query->where('name', 'LIKE', "%$searchTerm%");  
        }
        $categories = $query->take($searchTerm ? 1000 : 10)  
                            ->get(['id', 'name']); 
        if ($categories->isEmpty()) {
            return response()->json([
                'message' => 'No categories found for the provided organization.'
            ], 404);
        }
        return response()->json([
            'categories' => $categories
        ]);
    }
    
    public function getSubcategoriesByCategory(Request $request, $categoryId)
    {
        $category = Category::withDefaultGroupCompanyOrg()->find($categoryId);
        if (!$category) {
            return response()->json([
                'message' => 'Category not found.'
            ], 404);
        }
        $query = $category->subCategories()->where('status', 'active'); 
        $searchTerm = $request->input('search', '');
        if ($searchTerm) {
            $query->where('name', 'LIKE', "%$searchTerm%");
        }
        $subCategories = $query->take($searchTerm ? 1000 : 10)->get(['id', 'name']);
        if ($subCategories->isEmpty()) {
            return response()->json([
                'subCategories' => [], 
            ]);
        }
        return response()->json([
            'subCategories' => $subCategories
        ]);
    }
    
    public function getItemsAndSubCategoriesByCategory(Request $request)
    {
        $categoryId = $request->category_id;
        $searchTerm = $request->input('search', '');
        $subCategoryQuery = Category::withDefaultGroupCompanyOrg()
            ->where('parent_id', $categoryId)
            ->where('status', 'active');

        if ($searchTerm) {
            $subCategoryQuery->where('name', 'LIKE', "%$searchTerm%");
        }

        $subCategories = $subCategoryQuery->take($searchTerm ? 1000 : 10)
                                        ->get(['id', 'name']);
        $items = Item::withDefaultGroupCompanyOrg()
            ->where('category_id', $categoryId)
            ->where('status', 'active')
            ->get();

        return response()->json([
            'subCategories' => $subCategories,
            'items' => $items
        ]);
    }

    public function getItemsBySubCategory(Request $request)
    {
        $subCategoryId = $request->sub_category_id;
        $searchTerm = $request->input('search', '');
        $query = Item::withDefaultGroupCompanyOrg()
            ->where('subcategory_id', $subCategoryId)
            ->where('status', 'active');
    
        if ($searchTerm) {
            $query->where(function ($query) use ($searchTerm) {
                $query->where('item_name', 'LIKE', "%$searchTerm%")
                    ->orWhere('item_code', 'LIKE', "%$searchTerm%");
            });
        }
        $items = $query->take($searchTerm ? 1000 : 10)
                       ->get(['id','item_name', 'item_code']); 
    
        return response()->json([
            'items' => $items
        ]);
    }
    
    public function getLedgersByOrganization(Request $request, $organizationId)
    {
        $searchTerm = $request->input('search', '');
        $query = Ledger::withDefaultGroupCompanyOrg()
                       ->where('organization_id', $organizationId)
                       ->where('status', '1');

        if ($searchTerm) {
            $query->where('name', 'LIKE', "%$searchTerm%");
        }
        $ledgers = $query->take($searchTerm ? 1000 : 10)
                         ->get(['id', 'name']);
    
        if ($ledgers->isEmpty()) {
            return response()->json([
                'ledgers' => [],
            ]);
        }
        return response()->json([
            'ledgers' => $ledgers
        ]);
    }
    
    public function getLedgerGroupByLedger(Request $request)
    {
        $ledgerId = $request->input('ledger_id');
        $searchTerm = $request->input('search_term', '');
    
        if (empty($ledgerId)) {
            return response()->json(['message' => 'No ledger id provided.'], 400);
        }
        $ledger = Ledger::withDefaultGroupCompanyOrg()->find($ledgerId);
    
        if (!$ledger) {
            return response()->json(['message' => 'Ledger not found for the provided id.'], 404);
        }
    
        $ledgerGroupId = $ledger->ledger_group_id;
        $groupQuery = Group::query();

        if (is_array($ledgerGroupId) || json_decode($ledgerGroupId, true)) {
            $groupIds = is_array($ledgerGroupId) ? $ledgerGroupId : json_decode($ledgerGroupId, true);
            $groupQuery->whereIn('id', $groupIds);
        } else {
            $groupQuery->where('id', $ledgerGroupId);
        }
    
        if ($searchTerm) {
            $groupQuery->where('name', 'LIKE', "%$searchTerm%");
        }
    
        $ledgerGroups = $groupQuery->get(); 

        $ledgerGroupData = $ledgerGroups->map(function($group) {
            return [
                'id' => $group->id,
                'name' => $group->name
            ];
        });

        return response()->json([
            'ledgerGroupData' => $ledgerGroupData,
            'message' => $ledgerGroups->isEmpty() ? 'No groups found for the given ledger.' : 'Groups found.'
        ]);
    }
    

    
    public function destroy($id)
    {
        try {
            $stockAccount = StockAccount::findOrFail($id); 
            $result = $stockAccount->deleteWithReferences();
            if (!$result['status']) {
                return response()->json([
                    'status' => false,
                    'message' => $result['message'],
                    'referenced_tables' => $result['referenced_tables'] ?? [],
                ], 400);
            }

            return response()->json([
                'status' => true,
                'message' => 'Record deleted successfully!',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while deleting the stock account: ' . $e->getMessage(),
            ], 500);
        }
    }
}
