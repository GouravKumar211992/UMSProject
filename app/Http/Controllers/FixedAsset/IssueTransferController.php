<?php

namespace App\Http\Controllers\FixedAsset;

use App\Http\Controllers\Controller;
use App\Models\FixedAssetRegistration;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use App\Models\Employee;
use App\Models\FixedAssetIssueTransfer;
use Carbon\Carbon;

class IssueTransferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query=FixedAssetIssueTransfer::withDefaultGroupCompanyOrg()->orderBy('id','desc');
        $assets = FixedAssetRegistration::withDefaultGroupCompanyOrg()
        ->whereNotNull('asset_code')
        ->whereNotNull('asset_name')
        ->get();

    // Apply filters based on the request
    if ($request->has('status') && $request->status!==null) {
        $query->where('status', $request->status);
    }

    if ($request->has('asset') && $request->asset!==null) {
        $query->where('asset_id', $request->asset);
    }
    // Apply date range filter if provided
    if ($request->filled('date_range') && $request->date_range !==null) {
        $dates = explode(' to ', $request->date_range);
        if (count($dates) == 2) {
            $start_date = Carbon::createFromFormat('Y-m-d', $dates[0])->startOfDay();
            $end_date = Carbon::createFromFormat('Y-m-d', $dates[1])->endOfDay();
            $query->whereBetween('created_at', [$start_date, $end_date]);
        }
    }

    // Get the filtered data
    $data = $query->get();

        return view('fixed-asset.issue-transfer.index',compact('data','assets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $organization_id=Helper::getAuthenticatedUser()->organization->id;
        $employees = Employee::where(function ($query) use ($organization_id) {
            $query->whereHas('access_rights_org', function ($subQuery) use ($organization_id) {
                $subQuery->where('organization_id', $organization_id);
            })->orWhere('organization_id', $organization_id);
        })->get();

        $assets = FixedAssetRegistration::withDefaultGroupCompanyOrg()
        ->doesntHave('issue_transfer')
        ->whereNotNull('asset_code')
        ->whereNotNull('asset_name')
        ->get();

        return view('fixed-asset.issue-transfer.create',compact('assets','employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user= Helper::getAuthenticatedUser();
        $additionalData = [
            'created_by' => $user->id, // Assuming logged-in user
            'type' => get_class($user),
            'organization_id' => $user->organization->id,
            'group_id' => $user->organization->group_id,
            'company_id' => $user->organization->company_id,
        ];
        $data = array_merge($request->all(), $additionalData);


        // Store the asset
        try {
            $asset = FixedAssetIssueTransfer::create($data);
            return redirect()->route("finance.fixed-asset.issue-transfer.index")->with('success', 'Issue/Transfer created successfully!');
        } catch (\Exception $e) {
            // Set error message
            return redirect()->route("finance.fixed-asset.issue-transfer.create")->with('error',$e->getMessage());
        }}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = FixedAssetIssueTransfer::findorFail($id);
        $organization_id=Helper::getAuthenticatedUser()->organization->id;
        $employees = Employee::where(function ($query) use ($organization_id) {
            $query->whereHas('access_rights_org', function ($subQuery) use ($organization_id) {
                $subQuery->where('organization_id', $organization_id);
            })->orWhere('organization_id', $organization_id);
        })->get();
        $assets = FixedAssetRegistration::withDefaultGroupCompanyOrg()
        ->whereNotNull('asset_code')
        ->whereNotNull('asset_name')
        ->get();

        return view('fixed-asset.issue-transfer.show',compact('assets','employees','data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = FixedAssetIssueTransfer::findorFail($id);
        $organization_id=Helper::getAuthenticatedUser()->organization->id;
        $employees = Employee::where(function ($query) use ($organization_id) {
            $query->whereHas('access_rights_org', function ($subQuery) use ($organization_id) {
                $subQuery->where('organization_id', $organization_id);
            })->orWhere('organization_id', $organization_id);
        })->get();
        $assets = FixedAssetRegistration::withDefaultGroupCompanyOrg()
        ->whereNotNull('asset_code')
        ->whereNotNull('asset_name')
        ->get();

        return view('fixed-asset.issue-transfer.edit',compact('assets','employees','data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $asset = FixedAssetIssueTransfer::find($id);

    if (!$asset) {
        return redirect()
            ->route('finance.fixed-asset.issue-transfer.index')
            ->with('error', 'Issue/Transfer not found.');
    }

    $data = $request->all();

    // Update the asset
    try {
        $asset->update($data);
        return redirect()->route("finance.fixed-asset.issue-transfer.index")->with('success', 'Issue/Transfer updated successfully!');
    } catch (\Exception $e) {
        // Handle any exceptions
        return redirect()->route("finance.fixed-asset.issue-transfer.edit", $id)->with('error', $e->getMessage());
    }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
