<?php

namespace App\Http\Controllers\Land;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Lease;
use App\Models\Employee;
use App\Exports\LandExport;
use Illuminate\Http\Request;
use App\Models\LandScheduler;
use App\Http\Controllers\Controller;
use App\Models\Recovery;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Response;

class LandReportController extends Controller
{
    public function index()
    {
        $leases = Lease::get();
        $employees = Employee::get();
        $users = User::get();

        return view('land.report', compact('leases', 'users', 'employees'));
    }

    public function getLandReport(Request $request)
    {
        try {
            $period = $request->query('period');
            $startDate = $request->query('startDate');
            $endDate = $request->query('endDate');
            $series = $request->query('series');
            $area = $request->query('area');
            $landCost = $request->query('landCost');
            $khasaraNumber = $request->query('khasaraNumber');
            $paymentLastReceived = $request->query('paymentLastReceived');
            $totalLeaseAmount = $request->query('totalLeaseAmount');
            $leaseDuration = $request->query('leaseDuration');
            $monthlyInstallment = $request->query('monthlyInstallment');

            if (!empty(Auth::guard('web')->user())) {
                $organization_id = Auth::guard('web')->user()->organization_id;
                $user_id = Auth::guard('web')->user()->id;
                $type = 1;
                $utype = 'user';
            } elseif (!empty(Auth::guard('web2')->user())) {
                $organization_id = Auth::guard('web2')->user()->organization_id;
                $user_id = Auth::guard('web2')->user()->id;
                $type = 2;
                $utype = 'employee';
            } else {
                $organization_id = 1;
                $user_id = 1;
                $type = 1;
                $utype = 'user';
            }

            $query = Lease::query()
                ->with([
                    'land',
                    'recovery' => function ($query) {
                        $query->latest();  // Only interested in the latest recovery
                    }
                ])->withSum('recovery', 'received_amount')
                ->where('organization_id', $organization_id)
                // Legals created by the user
                ->where('user_id', $user_id)
                ->where('type', $type);

            // Date Filtering
            if (($startDate && $endDate) || $period) {
                if (!$startDate || !$endDate) {
                    switch ($period) {
                        case 'this-month':
                            $startDate = Carbon::now()->startOfMonth();
                            $endDate = Carbon::now()->endOfMonth();
                            break;
                        case 'last-month':
                            $startDate = Carbon::now()->subMonth()->startOfMonth();
                            $endDate = Carbon::now()->subMonth()->endOfMonth();
                            break;
                        case 'this-year':
                            $startDate = Carbon::now()->startOfYear();
                            $endDate = Carbon::now()->endOfYear();
                            break;
                    }
                }
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }

            // Vendor Filter
            if ($series) {
                $query->where('series', $series);
            }

            // Status Filter
            if ($area) {
                $query->where('area_sqft', $area);
            }

            if ($landCost) {
                $query->whereHas('land', function ($query) use ($landCost) {
                    $query->where('cost', 'like', "%{$landCost}%");
                });
            }

            if ($khasaraNumber) {
                $query->where('khasara_no', $khasaraNumber);
            }

            if ($paymentLastReceived) {
                $query->whereHas('recovery', function ($query) use ($paymentLastReceived) {
                    $query->whereDate('paymentLastReceived', $paymentLastReceived)
                        ->orderBy('created_at', 'desc')
                        ->limit(1);
                });
            }


            if ($totalLeaseAmount) {
                $query->where('lease_cost', 'like', "%{$totalLeaseAmount}%");
            }

            // if ($leaseDuration) {
            //     $query->whereRaw("
            //     CASE
            //         WHEN period_type = 'Monthly' THEN lease_time
            //         WHEN period_type = 'Quarterly' THEN lease_time * 3
            //         WHEN period_type = 'Yearly' THEN lease_time * 12
            //     END = ?", [$leaseDuration]);
            // }

            if ($leaseDuration) {
                $query->where("lease_time", $leaseDuration);
            }

            if ($monthlyInstallment) {
                $query->where('installment_cost', 'like', "%{$monthlyInstallment}%");
            }

            // Fetch Results
            $lease_reports = $query->get();

            return response()->json($lease_reports);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function addScheduler(Request $request)
    {
        // Validate request data
        $validatedData = $request->validate([
            'to' => 'required|array',
            'type' => 'required|string',
            'date' => 'required|date',
            'remarks' => 'nullable|string',
        ]);
        $toIds = $validatedData['to'];

        foreach ($toIds as $toId) {
            LandScheduler::updateOrCreate(
                [
                    'toable_id' => $toId['id'],
                    'toable_type' => $toId['type']
                ],
                [
                    'type' => $validatedData['type'],
                    'date' => $validatedData['date'],
                    'remarks' => $validatedData['remarks']
                ]
            );
        }

        return Response::json(['success' => 'Scheduler Added Successfully!']);
    }

    public function sendReportMail()
    {
        $fileName = $this->getFileName();
        $user = Auth::user();

        $startDate = null;
        $endDate = null;
        // Create the export object with the date range
        $excelData = Excel::raw(new LandExport($startDate, $endDate), \Maatwebsite\Excel\Excel::XLSX);

        // Save the file locally for debugging
        $filePath = storage_path('app/public/land-report/' . $fileName);
        $directoryPath = storage_path('app/public/land-report');

        if (!file_exists($directoryPath)) {
            mkdir($directoryPath, 0755, true);
        }
        file_put_contents($filePath, $excelData);

        // Check if the file exists
        if (!file_exists($filePath)) {
            throw new \Exception('File does not exist at path: ' . $filePath);
        }

        Mail::send('emails.land_report', [], function ($message) use ($user, $filePath) {
            $message->to($user->email)
                ->subject('Land Report')
                ->attach($filePath);
        });

        return Response::json(['success' => 'Send Mail Successfully!']);
    }

    private function getFileName()
    {
        $now = carbon::now()->format('Y-m-d_H-i-s');
        return "purchase_order_report_{$now}.xlsx";
    }

    public function recoverySchedulerReport(Request $request)
    {
        $land_no = $request->query('landNo');

        if (!empty(Auth::guard('web')->user())) {
            $organization_id = Auth::guard('web')->user()->organization_id;
            $user_id = Auth::guard('web')->user()->id;
            $type = 1;
            $utype = 'user';
        } elseif (!empty(Auth::guard('web2')->user())) {
            $organization_id = Auth::guard('web2')->user()->organization_id;
            $user_id = Auth::guard('web2')->user()->id;
            $type = 2;
            $utype = 'employee';
        } else {
            $organization_id = 1;
            $user_id = 1;
            $type = 1;
            $utype = 'user';
        }

        $recovried = Recovery::where('organization_id', $organization_id)
            // Legals created by the user
            ->where('user_id', $user_id)
            ->where('type', $type)
            ->where('land_no', $land_no)->get();
        return Response::json(['recovried' => $recovried]);
    }
}


