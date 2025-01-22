<?php

namespace App\Http\Controllers\Ledger;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\Organization;
use Auth;
use Illuminate\Validation\Rule;


class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $parentGroup = Group::whereNull("parent_group_id")->where(function($q){
            $q->whereNull("organization_id")->orWhere('organization_id', Helper::getAuthenticatedUser()->organization_id);
        })->with([
            'parent' => function ($q) {
                $q->select('id', 'name');
            }
        ])->get();

        $data = Group::where(function($q){
            $q->whereNull("organization_id")->orWhere('organization_id', Helper::getAuthenticatedUser()->organization_id);
        })->with([
            'parent' => function ($q) {
                $q->select('id', 'name');
            }
        ])->orderBy('id', 'desc')->get();

        return view('ledgers.groups.view_groups', compact('data', 'parentGroup'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $parents = Group::select('id','name')->where(function($q){
            $q->whereNull('organization_id')->orWhere('organization_id', Helper::getAuthenticatedUser()->organization_id);
        })->get();
        return view('ledgers.groups.group-create', compact('parents'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255|unique:erp_groups,name',
        ]);

        // Find the organization based on the user's organization_id
        $organization = Organization::where('id', Helper::getAuthenticatedUser()->organization_id)->first();

        // Create a new group record with organization details
        Group::create(array_merge($request->all(), [
            'organization_id' => $organization->id,
            'group_id' => $organization->group_id,
            'company_id' => $organization->company_id,
        ]));

        // Redirect with a success message
        return redirect()->route('ledger-groups.index')->with('success', 'Group created successfully');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = Group::find($id);
        $parents = Group::select('id','name')->where(function($q){
            $q->whereNull('organization_id')->orWhere('organization_id', Helper::getAuthenticatedUser()->organization_id);
        })->get();
        return view('ledgers.groups.edit_group', compact('data', 'parents'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('erp_groups')->ignore($id)],
        ]);

        $update = Group::find($id);
        $update->name = $request->name;
        $update->parent_group_id = $request->parent_group_id;
        $update->status = $request->status;
        $update->save();

        return redirect()->route('ledger-groups.index')->with('success', 'Group updated successfully.');
    }

    public function getLedgerGroup(Request $request)
    {
        $searchTerm = $request->input('q', '');
        
        $query = Group::where('status', 1);
        
        if (!empty($searchTerm)) {
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%$searchTerm%");
            });
        }
        $results = $query->limit(10)->get(['id', 'name']);
        
        return response()->json($results);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function delete_group($id)
    {
        $record = Group::findOrFail($id);
        $record->delete();
        return redirect()->route('groups.view_groups')->with('success', 'Group deleted successfully');
    }

}
