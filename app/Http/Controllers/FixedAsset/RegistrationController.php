<?php

namespace App\Http\Controllers\FixedAsset;

use App\Helpers\ConstantHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use App\Models\Category;
use App\Models\Currency;
use App\Models\Ledger;
use App\Models\MrnDetail;
use App\Models\MrnHeader;
use App\Models\Vendor;
use App\Http\Requests\FixedAssetRegistrationRequest;
use App\Models\FixedAssetRegistration;

class RegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $parentURL = request() -> segments()[0];
        
         $servicesBooks = Helper::getAccessibleServicesFromMenuAlias($parentURL);
         if (count($servicesBooks['services']) == 0) {
            return redirect() -> route('/');
        }
       $data=FixedAssetRegistration::withDefaultGroupCompanyOrg()->orderBy('id','desc')->get();
        return view('fixed-asset.registration.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $parentURL = request() -> segments()[0];
        
         $servicesBooks = Helper::getAccessibleServicesFromMenuAlias($parentURL);
         if (count($servicesBooks['services']) == 0) {
            return redirect() -> route('/');
        }
        $firstService = $servicesBooks['services'][0];
        $series = Helper::getBookSeriesNew($firstService -> alias, $parentURL)->get();
        $ledgers = Ledger::where('status', 1)->where('organization_id', Helper::getAuthenticatedUser()->organization_id)->select('id', 'name')->get();
        $categories = Category::where('status', 1)->where('organization_id', Helper::getAuthenticatedUser()->organization_id)->select('id', 'name')->get();
        $grns = MrnHeader::where('organization_id', Helper::getAuthenticatedUser()->organization_id)->whereHas('vendor')->get();
        $grn_details = MrnDetail::with('header')->whereHas('header', function ($query) {$query->where('organization_id', Helper::getAuthenticatedUser()->organization_id);})->get();
        $vendors = Vendor::where('organization_id', Helper::getAuthenticatedUser()->organization_id)->select('id', 'display_name as name')->get();
        $currencies = Currency::where('organization_id', Helper::getAuthenticatedUser()->organization_id)->select('id', 'short_name as name')->get();
        return view('fixed-asset.registration.create',compact('series','ledgers','categories','grns','vendors','currencies','grn_details'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FixedAssetRegistrationRequest $request)
    {
        // Validation is automatically handled by the FormRequest

        $validator= $request->validated();

        if (!$validator) {
            return redirect()
                ->route('finance.fixed-asset.registration.create')
                ->withInput()
                ->withErrors($request->errors());
        }

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
            $asset = FixedAssetRegistration::create($data);
            return redirect()->route("finance.fixed-asset.registration.index")->with('success', 'Asset created successfully!');
        } catch (\Exception $e) {
            // Set error message
            return redirect()->route("finance.fixed-asset.registration.create")->with('error',$e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $parentURL = request() -> segments()[0];
        
         $servicesBooks = Helper::getAccessibleServicesFromMenuAlias($parentURL);
         if (count($servicesBooks['services']) == 0) {
            return redirect() -> route('/');
        }
        $data= FixedAssetRegistration::withDefaultGroupCompanyOrg()->findorFail($id);
        $firstService = $servicesBooks['services'][0];
        $series = Helper::getBookSeriesNew($firstService -> alias, $parentURL)->get();
        

        $ledgers = Ledger::where('status', 1)->where('organization_id', Helper::getAuthenticatedUser()->organization_id)->select('id', 'name')->get();
        $categories = Category::where('status', 1)->where('organization_id', Helper::getAuthenticatedUser()->organization_id)->select('id', 'name')->get();
        $grns = MrnHeader::where('organization_id', Helper::getAuthenticatedUser()->organization_id)->whereHas('vendor')->get();
        $grn_details = MrnDetail::with('header')->whereHas('header', function ($query) {$query->where('organization_id', Helper::getAuthenticatedUser()->organization_id);})->get();
        $vendors = Vendor::where('organization_id', Helper::getAuthenticatedUser()->organization_id)->select('id', 'display_name as name')->get();
        $currencies = Currency::where('organization_id', Helper::getAuthenticatedUser()->organization_id)->select('id', 'short_name as name')->get();
        return view('fixed-asset.registration.show',compact('series','data','ledgers','categories','grns','vendors','currencies','grn_details'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $parentURL = request() -> segments()[0];
        
        
         $servicesBooks = Helper::getAccessibleServicesFromMenuAlias($parentURL);
         if (count($servicesBooks['services']) == 0) {
            return redirect() -> route('/');
        }
        $data= FixedAssetRegistration::withDefaultGroupCompanyOrg()->findorFail($id);
        $firstService = $servicesBooks['services'][0];
        $series = Helper::getBookSeriesNew($firstService -> alias, $parentURL)->get();
        

        $ledgers = Ledger::where('status', 1)->where('organization_id', Helper::getAuthenticatedUser()->organization_id)->select('id', 'name')->get();
        $categories = Category::where('status', 1)->where('organization_id', Helper::getAuthenticatedUser()->organization_id)->select('id', 'name')->get();
        $grns = MrnHeader::where('organization_id', Helper::getAuthenticatedUser()->organization_id)->whereHas('vendor')->get();
        $grn_details = MrnDetail::with('header')->whereHas('header', function ($query) {$query->where('organization_id', Helper::getAuthenticatedUser()->organization_id);})->get();
        $vendors = Vendor::where('organization_id', Helper::getAuthenticatedUser()->organization_id)->select('id', 'display_name as name')->get();
        $currencies = Currency::where('organization_id', Helper::getAuthenticatedUser()->organization_id)->select('id', 'short_name as name')->get();
        return view('fixed-asset.registration.edit',compact('series','data','ledgers','categories','grns','vendors','currencies','grn_details'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FixedAssetRegistrationRequest $request, $id)
{
    $asset = FixedAssetRegistration::find($id);

    if (!$asset) {
        return redirect()
            ->route('finance.fixed-asset.registration.index')
            ->with('error', 'Asset not found.');
    }

    $validator = $request->validated();

    if (!$validator) {
        return redirect()
            ->route('finance.fixed-asset.registration.edit', $id)
            ->withInput()
            ->withErrors($request->errors());
    }

    // Merge request data with additional data
    $data = $request->all();

    // Update the asset
    try {
        $asset->update($data);
        return redirect()->route("finance.fixed-asset.registration.index")->with('success', 'Asset updated successfully!');
    } catch (\Exception $e) {
        // Handle any exceptions
        return redirect()->route("finance.fixed-asset.registration.edit", $id)->with('error', $e->getMessage());
    }
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function getLedgerGroups(Request $request)
    {
        $ledgerId = $request->input('ledger_id');
        $ledger = Ledger::find($ledgerId);

        if ($ledger) {
            $groups = $ledger->group();

            if ($groups && $groups instanceof \Illuminate\Database\Eloquent\Collection) {
                $groupItems = $groups->map(function ($group) {
                    return ['id' => $group->id, 'name' => $group->name];
                });
            } else if ($groups) {
                $groupItems = [
                    ['id' => $groups->id, 'name' => $groups->name],
                ];
            } else {
                $groupItems = [];
            }

            return response()->json($groupItems);
        }

        return response()->json([], 404);
    }
}
