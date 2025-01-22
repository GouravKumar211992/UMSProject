<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\Book;
use App\Models\Land;
use App\Models\User;

use App\Models\Email;

use App\Helpers\ConstantHelper;
use App\Models\Lease;
use App\Models\Legal;
use App\Models\Vendor;
use App\Helpers\Helper;
use App\Models\BookType;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\HomeLoan;
use App\Mail\CustomEmail;
use App\Models\IssueType;
use Illuminate\Support\Facades\Log;
use App\Models\AssignTeam;
use App\Models\Attachment;
use App\Models\SendMessage;
use Illuminate\Http\Request;
use App\Models\NumberPattern;
use App\Models\EmailRecipient;
use App\Models\ApprovalWorkflow;
use Illuminate\Support\Facades\DB;
use App\Helpers\NotificationHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use App\Notifications\GeneralNotification;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use App\Http\Controllers\LegalNotificationSender;

class LegalController extends Controller
{
    public function index()
    {

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

        $Unassigned_leases = 0;
        $Assigned_leases = 0;
        $Closed_leases = 0;


        if (!empty($type) && $type == 1) {
            $data = Legal::where('organization_id', $organization_id)
                // Legals created by the user
                ->where('user_id', $user_id)
                ->where('type', $type)
                // Join with approvelworkflow table and match book_id with series, user_id, and type
                ->orwhereHas('approvelworkflow', function ($query) use ($user_id, $utype) {
                    $query->where('user_id', $user_id)  // Match user_id in approvelworkflow with authenticated user
                        ->where('user_type', $utype);       // Match type in approvelworkflow
                })

                // Order by creation date
                ->orderBy('created_at', 'desc')

                // Get the results
                ->get();

            $data1 = Legal::where('organization_id', $organization_id)
                // Legals created by the user
                ->where('user_id', $user_id)
                ->where('type', $type)
                // Join with approvelworkflow table and match book_id with series, user_id, and type
                ->orWhereHas('approvelworkflow', function ($query) use ($user_id, $utype) {
                    $query->where('user_id', $user_id)  // Match user_id in approvelworkflow with authenticated user
                        ->where('user_type', $utype);       // Match type in approvelworkflow
                })
                // Order by creation date
                ->orderBy('created_at', 'desc')
                // Get only the ids
                ->pluck('id');

            $requests = Legal::whereIN('id', $data1)->distinct()->pluck('requestno');
            $raisedByOptions = Legal::whereIN('id', $data1)->distinct()->pluck('name');

            $Closed_leases = Legal::whereIN('id', $data1)->where('status', 'Close')->count();
            $Assigned_leases = Legal::whereIn('id', $data1) // Use 'id' to match legal IDs
                ->whereIn('id', function ($query) {
                    $query->select('legalid')
                        ->from('erp_assign_teams'); // Ensure you are checking against the right table
                })
                ->where('status', '!=', 'Close') // Replace 'your_status_condition' with the actual status you want to check
                ->distinct('id') // Ensure we count unique legal IDs
                ->count('id'); // Count the unique legal IDs
            // Assuming $data1 contains the legal IDs you are checking against
            $Unassigned_leases = Legal::whereIn('id', $data1) // Use 'id' to match legal IDs
                ->whereNotIn('id', function ($query) {
                    $query->select('legalid')
                        ->from('erp_assign_teams'); // Ensure you are checking against the right table
                })
                ->where('status', '!=', 'Close') // Replace 'your_status_condition' with the actual status you want to check
                ->distinct('id') // Ensure we count unique legal IDs
                ->count('id'); // Count the unique legal IDs

        } elseif (!empty($type) && $type == 2) {
            $data = Legal::where('organization_id', $organization_id)
                // Legals created by the user
                ->where('user_id', $user_id)
                ->where('type', $type)

                // Legals assigned to the user's team
                ->orWhereHas('teams', function ($query) use ($user_id) {
                    $query->where('team', $user_id);  // Filter by the assigned team ID
                })

                // Join with approvelworkflow table and match book_id with series, user_id, and type
                ->orwhereHas('approvelworkflow', function ($query) use ($user_id, $utype) {
                    $query->where('user_id', $user_id)  // Match user_id in approvelworkflow with authenticated user
                        ->where('user_type', $utype);       // Match type in approvelworkflow
                })

                // Order by creation date
                ->orderBy('created_at', 'desc')

                // Get the results
                ->get();

            $data1 = Legal::where('organization_id', $organization_id)
                // Legals created by the user
                ->where('user_id', $user_id)
                ->where('type', $type)

                // Legals assigned to the user's team
                ->orWhereHas('teams', function ($query) use ($user_id) {
                    $query->where('team', $user_id);  // Filter by the assigned team ID
                })

                // Join with approvelworkflow table and match book_id with series, user_id, and type
                ->orwhereHas('approvelworkflow', function ($query) use ($user_id, $utype) {
                    $query->where('user_id', $user_id)  // Match user_id in approvelworkflow with authenticated user
                        ->where('user_type', $utype);       // Match type in approvelworkflow
                })

                // Order by creation date
                ->orderBy('created_at', 'desc')

                // Get the results
                ->pluck('id');

            $requests = Legal::whereIN('id', $data1)->distinct()->pluck('requestno');
            $raisedByOptions = Legal::whereIN('id', $data1)->distinct()->pluck('name');

            $Closed_leases = Legal::whereIN('id', $data1)->where('status', 'Close')->count();
            // Assuming $data1 contains the legal IDs you are checking against
            $Assigned_leases = Legal::whereIn('id', $data1) // Use 'id' to match legal IDs
                ->whereIn('id', function ($query) {
                    $query->select('legalid')
                        ->from('erp_assign_teams'); // Ensure you are checking against the right table
                })
                ->where('status', '!=', 'Close') // Replace 'your_status_condition' with the actual status you want to check
                ->distinct('id') // Ensure we count unique legal IDs
                ->count('id'); // Count the unique legal IDs
            // Assuming $data1 contains the legal IDs you are checking against
            $Unassigned_leases = Legal::whereIn('id', $data1) // Use 'id' to match legal IDs
                ->whereNotIn('id', function ($query) {
                    $query->select('legalid')
                        ->from('erp_assign_teams'); // Ensure you are checking against the right table
                })
                ->where('status', '!=', 'Close') // Replace 'your_status_condition' with the actual status you want to check
                ->distinct('id') // Ensure we count unique legal IDs
                ->count('id'); // Count the unique legal IDs
        } else {
            $data = Legal::where('organization_id', $organization_id)->orderby('created_at', 'desc')->get();

            $requests = Legal::where('organization_id', $organization_id)->distinct()->pluck('requestno');
            $raisedByOptions = Legal::where('organization_id', $organization_id)->distinct()->pluck('name');
        }


        $users = Employee::where('organization_id', $organization_id)->get();

        $selectedDateRange = '';
        $selectedRequestNo = '';
        $selectedRaisedBy = '';
        $selectedStatus = '';



        return view('legal.index', compact('data', 'requests', 'raisedByOptions', 'selectedDateRange', 'selectedRequestNo', 'selectedRaisedBy', 'selectedStatus', 'users', 'Unassigned_leases', 'Assigned_leases', 'Closed_leases', 'type', 'user_id'));
        // return view('legal.index');
    }

    public function legal_create()
    {
        if (!empty(Auth::guard('web')->user())) {
            $organization_id = Auth::guard('web')->user()->organization_id;
            $user_id = Auth::guard('web')->user()->id;
            $type = 1;
        } elseif (!empty(Auth::guard('web2')->user())) {
            $organization_id = Auth::guard('web2')->user()->organization_id;
            $user_id = Auth::guard('web2')->user()->id;
            $type = 2;
        } else {
            $organization_id = 1;
            $user_id = 1;
            $type = 2;
        }

        $parentURL = request() -> segments()[0];
        

        $servicesBooks = Helper::getAccessibleServicesFromMenuAlias($parentURL);
        if (count($servicesBooks['services']) == 0) {
           return redirect() -> route('/');
       }
       $firstService = $servicesBooks['services'][0];
       $series = Helper::getBookSeriesNew($firstService -> alias, $parentURL)->get();
       
        $issues = IssueType::where('organization_id', $organization_id)->get();

        $user = Helper::getAuthenticatedUser();
        $organizationId = $user->organization_id;
        $customers = Customer::with(['erpOrganizationType', 'category', 'subcategory'])
            ->where('organization_id', $organizationId)
            ->get();
        $vendors = Vendor::with(['erpOrganizationType', 'category', 'subcategory'])
            ->where('organization_type_id', $organizationId)
            ->get();
        $loans = HomeLoan::where('status', '!=', 3)->where('organization_id', $organization_id)->orderBy('id', 'desc')->get();
        $leases = Lease::where('organization_id', $organization_id)->with('land')->orderby('id', 'desc')->get();

        return view('legal.legal-add', compact('series', 'issues', 'customers', 'vendors', 'loans', 'leases'));
    }

    public function getSeries($issue_id)
    {
        if (!empty(Auth::guard('web')->user())) {
            $organization_id = Auth::guard('web')->user()->organization_id;
        } elseif (!empty(Auth::guard('web2')->user())) {
            $organization_id = Auth::guard('web2')->user()->organization_id;
        } else {
            $organization_id = 1;
        }

        $parentURL = request() -> segments()[0];
        

        $servicesBooks = Helper::getAccessibleServicesFromMenuAlias($parentURL);
        if (count($servicesBooks['services']) == 0) {
           return redirect() -> route('/');
       }
       $firstService = $servicesBooks['services'][0];
       $series = Helper::getBookSeriesNew($firstService -> alias, $parentURL)->get();
       
        return response()->json($series);
    }

    public function getRequests($book_id)
    {
        if (!empty(Auth::guard('web')->user())) {
            $organization_id = Auth::guard('web')->user()->organization_id;
        } elseif (!empty(Auth::guard('web2')->user())) {
            $organization_id = Auth::guard('web2')->user()->organization_id;
        } else {
            $organization_id = 1;
        }

        $request = NumberPattern::where('book_id', $book_id)->where('organization_id', $organization_id)->select('prefix', 'suffix', 'starting_no', 'current_no')->first();

        $requestno = $request->prefix . $request->current_no . $request->suffix;

        return response()->json(['requestno' => $requestno]);
    }

    public function legal_store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'issues' => 'required|string|max:255',
            'series' => 'required|string|max:255',
            'requestno' => 'required|string|max:255',
            'party_type' => 'required|string|max:255',
            'party_name' => 'required|max:255',
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:255', // Make email nullable
            'subject' => 'required|string|max:255',
            'filenumber' => 'nullable|string|max:255', // New column for file number
            'address' => 'nullable|string|max:255', // New column for address
            'files.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048', // Validate each file in the array
            'status' => 'required|string|in:draft,submitted', // Validation for status
        ], [
            'issues.required' => 'The issues field is required.',
            'series.required' => 'The series field is required.',
            'requestno.required' => 'The request number is required.',
            'party_type.required' => 'The party type is required.',
            'party_name.required' => 'The party name is required.',
            'name.required' => 'The name field is required.',
            'phone.max' => 'The phone number may not be greater than 15 characters.',
            'email.email' => 'Please provide a valid email address.',
            'subject.required' => 'The subject field is required.',
            'filenumber.max' => 'The file number may not be greater than 255 characters.',
            'address.max' => 'The address may not be greater than 255 characters.',
            'files.*.file' => 'Each file must be a valid file format.',
            'files.*.mimes' => 'Each file must be in PDF, DOC, DOCX, JPG, JPEG, or PNG format.',
            'files.*.max' => 'Each file may not be larger than 2MB.',
            'status.required' => 'The status field is required.',
            'status.in' => 'The status must be either draft or submitted.'
        ]);


        // Check if validation fails
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }


        // Handle file uploads
        $filePaths = [];
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                // Generate a unique file name
                $fileName = time() . '_' . $file->getClientOriginalName();

                // Define the directory path where files will be stored
                $destinationPath = public_path('uploads/legal');

                // Move the file to the public directory
                $file->move($destinationPath, $fileName);

                // Store the relative file path
                $filePaths[] = $fileName;
            }
        }

        if (!empty(Auth::guard('web')->user())) {
            $organization_id = Auth::guard('web')->user()->organization_id;
            $user_id = Auth::guard('web')->user()->id;
            $type = 1;
        } elseif (!empty(Auth::guard('web2')->user())) {
            $organization_id = Auth::guard('web2')->user()->organization_id;
            $user_id = Auth::guard('web2')->user()->id;
            $type = 2;
        } else {
            $organization_id = 1;
            $user_id = 1;
            $type = 2;
        }
        $user = Helper::getAuthenticatedUser();
        $organization = $user->organization;
        $organization_id = $organization->id;
        $group_id = $organization->group_id;
        $company_id =  $organization->company_id;

        $status = $request->status;

        // Save the data to the database
        $insert = new Legal();
        $insert->organization_id = $organization_id;
        $insert->book_id = $request->series ?? null;
        $insert->document_date = Carbon::now()->format('Y-m-d');
        $insert->doc_number_type = $request->doc_number_type;
        $insert->doc_reset_pattern = $request->doc_reset_pattern;
        $insert->doc_prefix =  $request->doc_prefix;
        $insert->doc_suffix = $request->doc_suffix;
        $insert->doc_no =  $request->doc_no;
        $insert->group_id = $group_id;
        $insert->company_id = $company_id;
        $insert->user_id = $user_id;
        $insert->type = $type;
        $insert->issues = $request->issues;
        $insert->series = $request->series;
        $insert->requestno = $request->requestno;
        $insert->party_type = $request->party_type;
        $insert->party_name = $request->party_name;
        $insert->name = $request->name;
        $insert->phone = $request->phone;
        $insert->email = $request->email;
        $insert->filenumber = $request->filenumber;
        $insert->address = $request->address;
        $insert->subject = $request->subject;
        $insert->file_path = implode(',', $filePaths); // Store file paths as JSON
        $insert->remark = $request->remark;
        $insert->status = $status;  // Store the status (draft or submitted)
        $insert->save();

        $update = Legal::find($insert->id);

        $document_status = $request->status_val;
        if ($status == ConstantHelper::SUBMITTED) {
            $bookId = $update->book_id;
            $docId = $update->id;
            $remarks = $update->remark;
            $attachments = $request->file('attachments');
            $currentLevel = $update->approvalLevel;
            $revisionNumber = $update->revision_number ?? 0;
            $actionType = 'submit'; // Approve // reject // submit
            $approveDocument = Helper::approveDocument($bookId, $docId, $revisionNumber, $remarks, $attachments, $currentLevel, $actionType);
            $document_status = Helper::checkApprovalRequired($request->series);
            $update->status = $document_status;
            $update->save();
            if ($document_status == ConstantHelper::SUBMITTED) {

                if ($update->approvelworkflow->count() > 0) { // Check if the relationship has records
                    foreach ($update->approvelworkflow as $approver) {
                        if ($approver->user) { // Check if the related user exists
                            $approver_user = $approver->user;
                            LegalNotificationSender::sendLegalSubmission($approver_user, $update);
                        }
                    }
                }
            }
        } else {
            $bookId = $update->book_id;
            $docId = $update->id;
            $remarks = $update->remark;
            $attachments = $request->file('attachments');
            $currentLevel = $update->approvalLevel;
            $revisionNumber = $update->revision_number ?? 0;
            $actionType = 'draft'; // Approve // reject // submit
            $approveDocument = Helper::approveDocument($bookId, $docId, $revisionNumber, $remarks, $attachments, $currentLevel, $actionType);
        }
        // Update the current number in NumberPattern
        $numberPattern = NumberPattern::where('book_id', $request->series)->first();

        if (!empty($numberPattern)) {

            $number = $numberPattern->current_no + 1;
            $numberPattern->current_no = $number;
            $numberPattern->save();
        }

        // Redirect to /land with a success message
        return redirect()->route("legal")->with('success', 'Legal created successfully.');
    }



    public function edit($id)
    {

        if (!empty(Auth::guard('web')->user())) {
            $organization_id = Auth::guard('web')->user()->organization_id;
        } elseif (!empty(Auth::guard('web2')->user())) {
            $organization_id = Auth::guard('web2')->user()->organization_id;
        } else {
            $organization_id = 1;
        }



        $legal = Legal::findOrFail($id);
        $books = BookType::where('status', 'Active')->whereHas('service', function ($query) {
            $query->where('alias', 'legal');
        })
            ->where('organization_id', $organization_id)
            ->pluck('id');

        $issues = IssueType::where('organization_id', $organization_id)->get();

        $user = Helper::getAuthenticatedUser();
        $organizationId = $user->organization_id;
        $customers = Customer::with(['erpOrganizationType', 'category', 'subcategory'])
            ->where('organization_id', $organizationId)
            ->get();

        $vendors = Vendor::with(['erpOrganizationType', 'category', 'subcategory'])
            ->where('organization_type_id', $organizationId)
            ->get();

        $loans = HomeLoan::where('status', '!=', 3)->where('organization_id', $organization_id)->orderBy('id', 'desc')->get();

        $leases = Lease::where('organization_id', $organization_id)->with('land')->orderby('id', 'desc')->get();
        $type = Employee::find($legal->user_id)->type;
        $buttons = Helper::actionButtonDisplayForLegal($legal->series, $legal->status, $legal->id, 0, $legal->approvalLevel, $legal->user_id, $type);
        $parentURL = request() -> segments()[0];
        

        $servicesBooks = Helper::getAccessibleServicesFromMenuAlias($parentURL);
        if (count($servicesBooks['services']) == 0) {
           return redirect() -> route('/');
       }
       $firstService = $servicesBooks['services'][0];
       $series = Helper::getBookSeriesNew($firstService -> alias, $parentURL)->get();
        return view('legal.legal-edit', compact('buttons', 'legal', 'issues', 'series', 'customers', 'vendors', 'loans', 'leases'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'issues' => 'required|string|max:255',
            'party_type' => 'required|string|max:255',
            'party_name' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:255', // Make email nullable
            'subject' => 'required|string|max:255',
            'filenumber' => 'nullable|string|max:255', // New column for file number
            'address' => 'nullable|string|max:255', // New column for address
            'files.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048', // Validate each file in the array
            'status' => 'required|string|in:draft,submitted', // Validation for status
        ], [
            'issues.required' => 'The issues field is required.',
            'issues.string' => 'The issues must be a string.',
            'issues.max' => 'The issues may not exceed 255 characters.',

            'party_type.required' => 'The party type field is required.',
            'party_type.string' => 'The party type must be a string.',
            'party_type.max' => 'The party type may not exceed 255 characters.',

            'party_name.required' => 'The party name field is required.',
            'party_name.string' => 'The party name must be a string.',
            'party_name.max' => 'The party name may not exceed 255 characters.',

            'name.required' => 'The name field is required.',
            'name.string' => 'The name must be a string.',
            'name.max' => 'The name may not exceed 255 characters.',

            'phone.string' => 'The phone number must be a string.',
            'phone.max' => 'The phone number may not exceed 15 characters.',

            'email.email' => 'Please provide a valid email address.',
            'email.max' => 'The email address may not exceed 255 characters.',

            'subject.required' => 'The subject field is required.',
            'subject.string' => 'The subject must be a string.',
            'subject.max' => 'The subject may not exceed 255 characters.',

            'filenumber.string' => 'The file number must be a string.',
            'filenumber.max' => 'The file number may not exceed 255 characters.',

            'address.string' => 'The address must be a string.',
            'address.max' => 'The address may not exceed 255 characters.',

            'files.*.file' => 'Each uploaded file must be a valid file.',
            'files.*.mimes' => 'Each file must be in PDF, DOC, DOCX, JPG, JPEG, or PNG format.',
            'files.*.max' => 'Each file may not be larger than 2MB.',

            'status.required' => 'The status field is required.',
            'status.string' => 'The status must be a string.',
            'status.in' => 'The status must be either draft or submitted.'
        ]);


        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        if (!empty(Auth::guard('web')->user())) {
            $organization_id = Auth::guard('web')->user()->organization_id;
        } elseif (!empty(Auth::guard('web2')->user())) {
            $organization_id = Auth::guard('web2')->user()->organization_id;
        } else {
            $organization_id = 1;
        }

        try {
            // Retrieve the legal record
            $legal = Legal::findOrFail($id);

            $NumberPattern = NumberPattern::where('book_id', $request->series)->where('organization_id', $organization_id)->first();

            if (!empty($NumberPattern) && (!empty($request->requestno)) && $request->requestno != $legal->requestno) {
                $number = (explode('_', $request->requestno));


                $number = $NumberPattern->current_no + 1;
                $NumberPattern->current_no = $number;
                $NumberPattern->save();
            }

            // Handle file removals
            if ($request->has('remove_files')) {
                $filesToRemove = explode(',', $request->remove_files);

                // Ensure file_path is not empty
                $existingFiles = !empty($legal->file_path) ? explode(',', $legal->file_path) : [];
                $remainingFiles = array_diff($existingFiles, $filesToRemove);

                foreach ($filesToRemove as $fileName) {
                    if (!empty($fileName)) {
                        $filePath = public_path('uploads/legal/' . $fileName);
                        if (file_exists($filePath)) {
                            unlink($filePath); // Delete the file
                        }
                    }
                }

                // Update the file_path field with the remaining files
                $legal->file_path = implode(',', $remainingFiles);
            }

            // Handle new file uploads
            if ($request->hasFile('files')) {
                $existingFiles = !empty($legal->file_path) ? explode(',', $legal->file_path) : [];

                foreach ($request->file('files') as $file) {
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $file->move(public_path('uploads/legal'), $fileName); // Move file to public/uploads/legal
                    $existingFiles[] = $fileName; // Add the new file path to the array
                }

                // Update the file_path field with all files (new and existing)
                $legal->file_path = implode(',', $existingFiles);
            }

            $status = $request->status == ConstantHelper::SUBMITTED ? Helper::checkApprovalRequired($request->series) : $request->status;

            // Update other data in the database
            $legal->issues = $request->issues;
            $legal->party_type = $request->party_type;
            $legal->party_name = $request->party_name;
            $legal->name = $request->name;
            $legal->phone = $request->phone;
            $legal->email = $request->email;
            $legal->filenumber = $request->filenumber;
            $legal->address = $request->address;
            $legal->subject = $request->subject;
            $legal->remark = $request->remark;
            $legal->status = $status;  // Store the status (draft or submitted)
            $legal->save();



            $update = $legal;

            $document_status = $request->status_val;
            if ($status == ConstantHelper::SUBMITTED) {
                $bookId = $update->book_id;
                $docId = $update->id;
                $remarks = $update->remark;
                $attachments = $request->file('attachments');
                $currentLevel = $update->approvalLevel;
                $revisionNumber = $update->revision_number ?? 0;
                $actionType = 'submit'; // Approve // reject // submit
                $approveDocument = Helper::approveDocument($bookId, $docId, $revisionNumber, $remarks, $attachments, $currentLevel, $actionType);
                $document_status = Helper::checkApprovalRequired($request->book_id);
                $update->status = $document_status;
                $update->save();
            } else {
                $bookId = $update->book_id;
                $docId = $update->id;
                $remarks = $update->remark;
                $attachments = $request->file('attachments');
                $currentLevel = $update->approvalLevel;
                $revisionNumber = $update->revision_number ?? 0;
                $actionType = 'draft'; // Approve // reject // submit
                $approveDocument = Helper::approveDocument($bookId, $docId, $revisionNumber, $remarks, $attachments, $currentLevel, $actionType);
            }



            return redirect()->route('legal')->with('success', 'Legal record updated successfully.');
        } catch (\Exception $e) {
            // Log the exception for debugging purposes
            Log::error('An error occurred while saving legal data: ' . $e->getMessage());

            // Redirect back with input data and an error message if something goes wrong
            return redirect()->back()->withInput()->withErrors(['error' => 'An error occurred while saving the data.']);
        }
    }






    public function legal_view(Request $request)
    {

        $user = Helper::getAuthenticatedUser();
        $user_id = $user->id;
        $type = Helper::userCheck()['type'];


        $data = Legal::with('teams', 'teams.user')->find($request->id);
        $message = Email::with('recipients', 'attachments', 'replies', 'user', 'employee')
            ->whereNull('parent_id')
            ->where('legal_id', $request->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $users = Employee::where('organization_id', $user->organization_id)->get();
        $buttons = Helper::actionButtonDisplayForLegal($data->series, $data->status, $data->id, 0, $data->approvalLevel, $data->user_id, $type, 0);
        return view('legal.view', compact('buttons', 'message', 'data', 'users', 'type', 'user_id'));
    }

    public function legal_assignsubmit(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'team' => 'required|array|min:1', // Ensure at least one team member is selected
            'team.*' => 'exists:employees,id', // Ensure selected team members exist
            'remarks' => 'nullable|string',
            'assignid' => 'required|integer|exists:erp_legals,id', // Validate assignid
        ]);


        foreach ($request->team as $team) {
            // Create a new AssignTeam record
            $insert = new AssignTeam();
            $insert->team = $team; // Convert array to comma-separated string
            $insert->remarks = $request->remarks;
            $insert->legalid = $request->assignid;
            $insert->save();

            $legal = Legal::find($request->assignid);
            $legal->status = ConstantHelper::ASSIGNED;
            $legal->save();

            $employee = Employee::find($team);

            LegalNotificationSender::sendRequestAssignmentNotification($employee, $legal);



            // Send notification to the updated user
            $additionalData = [
                'user_id' => $employee->id,
                'user_type' => 'employee',
                'username' => $employee->name,
                'request_id' => $request->assignid,
                'request_table' => 'erp_legals',
                'title' => 'Assignment Notification',
                'message' => "Dear {$employee->name}, a new Request (Request ID: {$request->assignid}) has been assigned to you. Please review the details and take the necessary action. Thank you for your prompt attention.",
            ];


            // $employee->notify(new GeneralNotification($additionalData));
            // $notification = $employee->notifications()->latest()->first();
            // //Update the notification with extra data
            // //dd($notification);
            // $notification->update([
            //     'organization_id' => Auth::check() ? Auth::user()->organization_id : null,
            //     'auth_type' => Auth::check() ? get_class(Auth::user()) : null,
            //     'auth_id' => Auth::check() ? Auth::user()->id : null,
            //     'title' => $additionalData['title'],
            //     'description' => $additionalData['message'],
            // ]);
        }

        // Redirect with success message
        return redirect()->route('legal')->with('success', 'LegalAssign successfully.');
    }


    public function legal_sendmessage(Request $request)
    {
        $data1 = [];

        // Process each uploaded file
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $key => $file) {
                $data = [];
                // Store the file and get the path
                $path = $file->store('uploads', 'public');
                $data['filePaths'] = $path;
                $data['fileNames'] = $file->getClientOriginalName();
                $data['filenameshow'] = $request->names[$key];

                $data1[] = $data;
            }
        }

        // Prepare data for JSON
        $insert = new SendMessage();
        $insert->team = implode(',', $request->team);
        $insert->message = $request->message;
        $insert->documents = implode(',', $request->names);
        $insert->filejson = json_encode($data);
        $insert->legalid = $request->legalid;
        $insert->save();

        return redirect()->route("legal")->with('success', 'Send Message successfully.');
    }



    public function sendEmail(Request $request)
    {
        if (empty($request->parent_id)) {
            $validatedData = $request->validate([
                'body' => 'required|string',
                'subject' => 'required|string',
                'to' => 'required|array',
                'to.*' => 'required|integer|exists:employees,id',
                'cc' => 'nullable|array',
                'cc.*' => 'nullable|email',
                'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,docx',
                'legal_id' => 'required|integer|exists:erp_legals,id',
                'parent_id' => 'nullable|integer|exists:erp_emails,id'
            ]);
        } else {

            $validatedData = $request->validate([
                'body' => 'required|string',
                'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,docx',
                'legal_id' => 'required|integer|exists:erp_legals,id',
                'parent_id' => 'nullable|integer|exists:erp_emails,id'
            ]);
        }


        try {

            if (!empty(Auth::guard('web')->user())) {
                $organization_id = Auth::guard('web')->user()->organization_id;
                $user_id = Auth::guard('web')->user()->id;
                $type = 1;
                $username = Auth::guard('web')->user()->name;
            } elseif (!empty(Auth::guard('web2')->user())) {
                $organization_id = Auth::guard('web2')->user()->organization_id;
                $user_id = Auth::guard('web2')->user()->id;
                $type = 2;
                $username = Auth::guard('web2')->user()->name;
            } else {
                $organization_id = 1;
                $user_id = 1;
                $type = 2;
                $username = 'guest';
            }
            // Create the email record
            $email = new Email();
            $email->user_id = $user_id;
            $email->user_type = $type;
            $email->subject = $validatedData['subject'] ?? null;
            $email->body = $validatedData['body'];
            $email->legal_id = $validatedData['legal_id'];
            $email->parent_id = $validatedData['parent_id'] ?? null;
            $email->save();

            // Fetch recipients (To)
            if (empty($request->parent_id)) {
                $recipients = Employee::whereIn('id', $validatedData['to'])->pluck('email')->toArray();
            } else {
                $recipients = [];
                $emailtoget = Email::where('parent_id', $request->parent_id)->first();
                if ($emailtoget->user_type == 2) {
                    $recipients = Employee::where('id', $emailtoget->user_id)->pluck('email')->toArray();
                } else {
                    $recipients = User::where('id', $emailtoget->user_id)->pluck('email')->toArray();
                }
            }
            // Fetch CC recipients if provided
            // $ccRecipients = $validatedData['cc'] ?? [];

            // Save attachments
            $attachs = [];
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $key => $file) {
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $file->move(public_path('attachments'), $fileName);

                    $attachment = new Attachment();
                    $attachment->email_id = $email->id;
                    $attachment->file_path = $fileName;
                    $attachment->file_name = $request->names[$key];
                    $attachment->save();

                    $attachs[] = $file->getClientOriginalName();
                }
            }

            if (empty($request->parent_id)) {
                // Save recipients (To)
                foreach ($validatedData['to'] as $recipientId) {
                    $recipient = new EmailRecipient();
                    $recipient->email_id = $email->id;
                    $recipient->user_id = $recipientId;
                    $recipient->type = 'to';
                    $recipient->save();
                    $employee = Employee::find($recipientId);
                    $request_id = Legal::find($request->legal_id);

                    if (count($attachs) > 0) {
                        $date = Carbon::now()->format('Y-m-d');

                    
                        // $additionalData = [
                        //     'user_id' => $employee->id,
                        //     'username' => $employee->name,
                        //     'user_type' => 'employee',
                        //     'request_id' => $request_id,
                        //     'request_table' => 'erp_legals',
                        //     'title' => 'Document Upload Notification',
                        //     'message' => "Dear {$employee->name}, a new document has been uploaded for your legal ticket (Ticket ID: {$request_id}) on {$date}. Please log in to your account to view the document. Thank you for your prompt attention.",

                        // ];

                        try {
                            LegalNotificationSender::sendDocumentUploadNotification($employee, $request_id);

                            // $employee->notify(new GeneralNotification($additionalData));
                            // $notification = $employee->notifications()->latest()->first();
                            // // Update the notification with extra data
                            // $notification->update([
                            //     'organization_id' => Auth::check() ? Auth::user()->organization_id : null,
                            //     'auth_type' => Auth::check() ? get_class(Auth::user()) : null,
                            //     'auth_id' => Auth::check() ? Auth::user()->id : null,
                            //     'title' => $additionalData['title'],
                            //     'description' => $additionalData['message'],
                            // ]);
                        } catch (Exception $e) {
                            //throw new Exception('Error sending notification: ' . $e->getMessage());
                            return response()->json(['error' => 'Error sending notification: ' . $e->getMessage()]);
                        }
                    }
                    // $authUserName = Auth::user()->name;

                    // $additionalData = [
                    //     'user_id' => $employee->id,
                    //     'username' => $employee->name,
                    //     'user_type' => 'employee',
                    //     'request_id' => $request_id,
                    //     'request_table' => 'erp_legals',
                    //     'title' => 'New Comment Added Notification',
                    //     'message' => "Dear {$employee->name}, a new comment has been added to your legal ticket (Ticket ID: {$request_id}) by {$authUserName} on {$date}. Please log in to your account to review the comment. Thank you.",

                    // ];

                    try {
                       
                        LegalNotificationSender::sendNewCommentAddedNotification($employee, $request_id);

                        // $employee->notify(new GeneralNotification($additionalData));
                        // $notification = $employee->notifications()->latest()->first();
                        // // Update the notification with extra data
                        // $notification->update([
                        //     'organization_id' => Auth::check() ? Auth::user()->organization_id : null,
                        //     'auth_type' => Auth::check() ? get_class(Auth::user()) : null,
                        //     'auth_id' => Auth::check() ? Auth::user()->id : null,
                        //     'title' => $additionalData['title'],
                        //     'description' => $additionalData['message'],
                        // ]);
                    } catch (Exception $e) {
                        //throw new Exception('Error sending notification: ' . $e->getMessage());
                        return response()->json(['error' => 'Error sending notification: ' . $e->getMessage()]);
                    }
                }
            } else {

                $recipient = new EmailRecipient();
                $recipient->email_id = $email->id;
                $recipient->user_id = $emailtoget->user_id;
                $recipient->type = 'to';
                $recipient->save();
            }

            // Save CC recipients
            // if (!empty($ccRecipients)) {
            //     foreach ($ccRecipients as $ccEmail) {
            //         $ccUser = Employee::where('email', $ccEmail)->first();
            //         if ($ccUser) {
            //             $cc = new EmailRecipient();
            //             $cc->email_id = $email->id;
            //             $cc->user_id = $ccUser->id;
            //             $cc->type = 'cc';
            //             $cc->save();
            //         }
            //     }
            // }

            // Send email using Mailable class
            Mail::to($recipients)
                ->queue(new CustomEmail($validatedData['body'], $attachs));

            $email = Email::with('replies', 'attachments', 'recipients')->find($email->id);



            return response()->json([
                'message' => 'Message sent successfully!',
                'parent_id' => $validatedData['parent_id'] ?? null,
                'email' => $email,
                'created_at' => $email->created_at->format('Y-m-d H:i:s'),
                'user_name' => $username,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred: ' . $e], 500);
        }
    }



    public function searchMessages(Request $request)
    {
        $query = $request->input('query');

        // Assuming `emails` table has a foreign key to `employees` table
        $messages = Email::with('recipients', 'attachments', 'replies', 'user', 'employee')
            ->join('employees', 'erp_emails.user_id', '=', 'employees.id') // Join with employees table
            ->where(function ($queryBuilder) use ($query) {
                $queryBuilder->where('body', 'like', "%{$query}%")
                    ->orWhere('employees.name', 'like', "%{$query}%");
            })
            ->whereNull('parent_id')
            ->where('legal_id', $request->id)
            ->orderBy('erp_emails.created_at', 'desc')
            ->select('erp_emails.*') // Select only fields from the emails table
            ->get();

        return response()->json($messages);
    }


    public function filter(Request $request)
    {
        if (!empty(Auth::guard('web')->user())) {
            $organization_id = Auth::guard('web')->user()->organization_id;
            $user_id = Auth::guard('web')->user()->id;
            $type = 1;
        } elseif (!empty(Auth::guard('web2')->user())) {
            $organization_id = Auth::guard('web2')->user()->organization_id;
            $user_id = Auth::guard('web2')->user()->id;
            $type = 2;
        } else {
            $organization_id = 1;
            $user_id = 1;
            $type = 1;
        }

        $requests = Legal::where('organization_id', $organization_id)->distinct()->pluck('requestno');
        $raisedByOptions = Legal::where('organization_id', $organization_id)->distinct()->pluck('name');
        $query = Legal::with('emails', 'teams')->where('organization_id', $organization_id)->where('user_id', $user_id)->where('type', $type);

        // Apply filters based on the request
        if ($request->filled('date_range')) {
            $dates = explode(' to ', $request->input('date_range'));
            if (!empty($dates[1])) {
                $query->whereBetween('created_at', [$dates[0] . ' 00:00:00', $dates[1] . ' 23:59:59']);
            } else {
                $query->whereDate('created_at', $dates[0]);
            }
        }

        if ($request->filled('request_no')) {
            $query->where('requestno', $request->input('request_no'));
        }

        if ($request->filled('raised_by')) {
            $query->where('name', $request->input('raised_by'));
        }

        if ($request->filled('status')) {
            $status = $request->input('status');

            if ($status === 'Close') {
                // Filter records with related teams and no related emails
                $query->where('status', 'Close');
            } else if ($status === 'Assigned') {
                // Filter records with related teams and no related emails
                $query->whereHas('teams', function ($q) {
                    $q->whereNotNull('id'); // Ensure that related teams exist
                })
                    ->whereDoesntHave('emails'); // Ensure no related emails
            } else if ($status === 'Waiting') {
                // Filter records with related emails and no related teams
                $query->whereHas('emails', function ($q) {
                    $q->whereNotNull('id'); // Ensure that related emails exist
                });
            } else if ($status === 'Pending') {
                // Filter records with no related teams or emails
                $query->whereDoesntHave('teams')
                    ->whereDoesntHave('emails');
            }
        }

        $data = $query->get();

        $selectedDateRange = $request->input('date_range');
        $selectedRequestNo = $request->input('request_no');
        $selectedRaisedBy = $request->input('raised_by');
        $selectedStatus = $request->input('status');
        $users = Employee::all();
        $Unassigned_leases = 0;
        $Assigned_leases = 0;
        $Closed_leases = 0;

        $idArray = $data->pluck('id')->toArray(); // This will create a simple array of IDs

        $Closed_leases = Legal::whereIN('id', $idArray)->where('status', 'Close')->count();
        $Assigned_leases = Legal::whereIn('id', $idArray) // Use 'id' to match legal IDs
            ->whereIn('id', function ($query) {
                $query->select('legalid')
                    ->from('erp_assign_teams'); // Ensure you are checking against the right table
            })
            ->where('status', '!=', 'Close') // Replace 'your_status_condition' with the actual status you want to check
            ->distinct('id') // Ensure we count unique legal IDs
            ->count('id'); // Count the unique legal IDs
        // Assuming $data1 contains the legal IDs you are checking against
        $Unassigned_leases = Legal::whereIn('id', $idArray) // Use 'id' to match legal IDs
            ->whereNotIn('id', function ($query) {
                $query->select('legalid')
                    ->from('erp_assign_teams'); // Ensure you are checking against the right table
            })
            ->where('status', '!=', 'Close') // Replace 'your_status_condition' with the actual status you want to check
            ->distinct('id') // Ensure we count unique legal IDs
            ->count('id'); // Count the unique legal IDs



        return view('legal.index', compact('data', 'requests', 'raisedByOptions', 'selectedDateRange', 'selectedRequestNo', 'selectedRaisedBy', 'selectedStatus', 'users', 'Unassigned_leases', 'Assigned_leases', 'Closed_leases', 'type', 'user_id'));
    }

    public function searchDocs(Request $request)
    {
        $query = $request->get('query');

        // Initialize collections to store results
        $files = [];
        $matchingAttachments = [];

        if ($query) {
            // Search directly in file_path
            $files = Legal::where('id', $request->id)
                ->where('file_path', 'LIKE', "%{$query}%")
                ->first(); // Assuming this returns a single record

            // Search through related emails and attachments
            $legalsWithMatchingAttachments = Legal::where('id', $request->id)
                ->whereHas('emails.attachments', function ($q) use ($query) {
                    $q->where('file_name', 'LIKE', "%{$query}%");
                })
                ->get();

            // Loop through the records and extract only matching attachments
            $matchingAttachments = [];
            foreach ($legalsWithMatchingAttachments as $legal) {
                foreach ($legal->emails as $email) {
                    foreach ($email->attachments as $attachment) {
                        if (stripos($attachment->file_name, $query) !== false) {
                            $matchingAttachments[] = $attachment;
                        }
                    }
                }
            }
        } else {
            // Query is null, return all data without filtering
            $files = Legal::where('id', $request->id)->first();

            // Get all emails and attachments without filtering by file_name
            $legalsWithMatchingAttachments = Legal::where('id', $request->id)
                ->with('emails.attachments')
                ->get();

            // Collect all attachments
            $matchingAttachments = [];
            foreach ($legalsWithMatchingAttachments as $legal) {
                foreach ($legal->emails as $email) {
                    foreach ($email->attachments as $attachment) {
                        $matchingAttachments[] = $attachment;
                    }
                }
            }
        }


        // Return the view with the results
        return view('legal.doc-results', compact('files', 'matchingAttachments', 'query'))->render();
    }


    public function mailer(Request $request)
    {
        return view('legal.mailer');
    }

    public function close(Request $request)
    {

        $legal = Legal::with('teams')->findOrFail($request->id);
        Legal::where('id', $request->id)->update([
            'status' => ConstantHelper::CLOSE,
            'close_remark' => $request->remark,
        ]);

        foreach ($legal->teams as $team) {
            $user = $team->user;
            $date = Carbon::now()->format('Y-m-d H:i:s');
            // Send notification to the updated user
            $additionalData = [
                'user_id' => $user->id,
                'username' => $user->name,
                'user_type' => 'employee',
                'request_id' => $request->id,
                'request_table' => 'erp_legals',
                'title' => 'Resolved Notification',
                'message' => "Dear {$user->name}, your legal ticket (Ticket ID: {$request->id}) has been closed on {$date}. If you have any further questions or require additional assistance, please contact our support team. Thank you for using our ticketing system.",
            ];

            try {
                LegalNotificationSender::sendRequestResolvedNotification($user, $legal);

                // $user->notify(new GeneralNotification($additionalData));
                // $notification = $user->notifications()->latest()->first();
                // // Update the notification with extra data
                // $notification->update([
                //     'organization_id' => Auth::check() ? Auth::user()->organization_id : null,
                //     'auth_type' => Auth::check() ? get_class(Auth::user()) : null,
                //     'auth_id' => Auth::check() ? Auth::user()->id : null,
                //     'title' => $additionalData['title'],
                //     'description' => $additionalData['message'],
                // ]);
            } catch (Exception $e) {
                return redirect()->back()->withInput()->withErrors(['error' => 'Error sending notification: ' . $e->getMessage()]);
            }
        }

        return redirect()->route("legal")->with('success', "Legal Request closed");
    }

    public function ApprReject(Request $request)
    {
        $attachments = null;
        if ($request->has('appr_rej_doc')) {
            $path = $request->file('appr_rej_doc')->store('land_parcel_documents', 'public');
            $attachments = $path;
        } elseif ($request->has('stored_appr_rej_doc')) {
            $attachments = $request->stored_appr_rej_doc;
        } else {
            $attachments = null;
        }

        $update = Legal::find($request->appr_rej_land_id);
        $approveDocument = Helper::approveDocument($update->book_id, $update->id, $update->revision_number??0, $request->appr_rej_remarks, $attachments, $update->approvalLevel, $request->appr_rej_status);
        $update->approvalLevel = $approveDocument['nextLevel'];
        $update->status = $approveDocument['approvalStatus'];
        $update->appr_rej_recom_remark = $request->appr_rej_remarks ?? null;
        $update->appr_rej_doc = $attachments;
        $update->appr_rej_behalf_of = $request->appr_rej_behalf_of ? json_encode($request->appr_rej_behalf_of) : null;

        $update->save();

        $creator_type = $update->type;
        $created_by = $update->user_id;
        $creator = null;

        if ($creator_type != null) {
            switch ($creator_type) {
                case 'employee':
                    $creator = Employee::find($created_by);
                    break;

                case 'user':
                    $creator = User::find($created_by);
                    break;
                case '2':
                    $creator = Employee::find($created_by);
                    break;

                case '1':
                    $creator = User::find($created_by);
                    break;

                default:
                    $creator = $creator_type::find($created_by);
                    break;
            }
        }

        $user = Helper::getAuthenticatedUser()->id;
        $approver = get_class(Helper::getAuthenticatedUser());
        $approver = $approver::find($user);

        if ($request->appr_rej_status == 'approve') {
            LegalNotificationSender::notifyLegalApproved($creator, $update, $approver);
            return redirect()->route("legal")->with('success', 'Approved Successfully!');
        } else {
            LegalNotificationSender::notifyLegalReject($creator, $update, $approver);
            return redirect()->route("legal")->with('success', 'Rejected Successfully!');
        }
    }



    public function onLeaseAddFilter(Request $request)
    {
        $land_no = $request->query('landNo');
        $customer_name = $request->query('customerName');
        $plot_no = $request->query('plotNo');
        $khasara_no = $request->query('khasaraNo');

        // Process the request and add new lease data
        if (!empty(Auth::guard('web')->user())) {
            $organization_id = Auth::guard('web')->user()->organization_id;
            $user_id = Auth::guard('web')->user()->id;
            $type = 1;
        } elseif (!empty(Auth::guard('web2')->user())) {
            $organization_id = Auth::guard('web2')->user()->organization_id;
            $user_id = Auth::guard('web2')->user()->id;
            $type = 2;
        } else {
            $organization_id = 1;
            $user_id = 1;
            $type = 2;
        }

        $query = Lease::query()
            ->where('organization_id', $organization_id)
            // Legals created by the user
            ->where('user_id', $user_id)
            ->where('type', $type)
            ->with('land', 'cust');

        if ($land_no) {
            $query->where('id', $land_no);
        }
        if ($customer_name) {
            $query->where('id', $customer_name);
        }
        if ($plot_no) {
            $query->where('id', $plot_no);
        }
        if ($khasara_no) {
            $query->where('id', $khasara_no);
        }
        $land_filter_list = $query->get();

        return Response::json(compact('land_filter_list'));
    }
    public function notifyNewCommentAdded(Request $request)
    {

        //     dd(Employee::find($request->user)->notifications());
        try {
            $user = Employee::find($request->user);

            // Check if the user exists
            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }

            // Find the legal ticket by ticket_id
            $ticketId = $request->legal;
            $ticket = Legal::find($ticketId);

            // Check if the ticket exists
            if (!$ticket) {
                return response()->json(['message' => 'Legal ticket not found'], 404);
            }

            // Call the sendNewCommentAddedNotification method
            LegalNotificationSender::sendDocumentUploadNotification($user, $ticket);

            return response()->json(['message' => 'Notification sent successfully']);
        } catch (Exception $e) {
            // Catch any exceptions and return a response with the error message
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }
}
