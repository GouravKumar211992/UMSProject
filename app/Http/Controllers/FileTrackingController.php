<?php

namespace App\Http\Controllers;

use App\Models\FileTracking;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use Carbon\Carbon;
use App\Helpers\ConstantHelper;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpWord\IOFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use setasign\Fpdi\Fpdi;
use App\Models\BookLevel;
use App\Models\AmendmentWorkflow;
use App\Models\OrganizationBookParameter;
use App\Models\Book;
use Exception;
use App\Helpers\ServiceParametersHelper;

class FileTrackingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Base query with filters
        $query = FileTracking::withDefaultGroupCompanyOrg()->orderBy('id', 'desc')->whereNotIn('document_status', [ConstantHelper::APPROVAL_NOT_REQUIRED, 'signed']);

        // Apply status filter if provided
        if ($request->has('status') && $request->status !== null) {
            $query->where('document_status', $request->status);
        }

        // Apply date range filter if provided
        if ($request->filled('date_range')) {
            // Ensure the date range is in the correct format
            $dates = explode(' to ', $request->date_range);

            if (count($dates) == 2) {
                $start_date = Carbon::createFromFormat('Y-m-d', $dates[0])->startOfDay();
                $end_date = Carbon::createFromFormat('Y-m-d', $dates[1])->endOfDay();
                $query->whereBetween('created_at', [$start_date, $end_date]);
            }
        }

        // Apply keyword filter if provided
        if ($request->has('keyword')) {
            $query->where('file_name', 'like', '%' . $request->keyword . '%');
        }

        $baseQuery = clone $query;

        // Count statuses with applied filters
        $signed_count = (clone $baseQuery)
            ->where('document_status', ConstantHelper::APPROVED)
            ->count();

        $unsigned_count = (clone $baseQuery)
            ->where('expected_completion_date', '>', Carbon::now()->toDateString())
            ->where('document_status', ConstantHelper::SUBMITTED)
            ->where('document_status', '!=', ConstantHelper::DRAFT)
            ->count();

        $pending_count = (clone $baseQuery)
            ->where('expected_completion_date', '>', Carbon::now()->toDateString())
            ->where('document_status', '!=', ConstantHelper::SUBMITTED)
            ->where('document_status', '!=', ConstantHelper::DRAFT)
            ->where('document_status', '!=', ConstantHelper::APPROVED)
            ->count();

        $overdue_count = (clone $baseQuery)
            ->where('expected_completion_date', '<', Carbon::now()->toDateString())
            ->where('document_status', '!=', ConstantHelper::DRAFT)
            ->where('document_status', '!=', ConstantHelper::APPROVED)
            ->count();


        if ($request->has('search') && $request->search === 'overdue') {
            $query->where('expected_completion_date', '<', Carbon::now()->toDateString())
                ->where('document_status', '!=', ConstantHelper::DRAFT)
                ->where('document_status', '!=', ConstantHelper::APPROVED);
        }
        //my expected_completion_date like this 2023-01-03
        if ($request->has('search') && $request->search === 'signed') {

            $query->where('document_status', ConstantHelper::APPROVED);
        }
        if ($request->has('search') && $request->search === 'unsigned') {
            $query->where('expected_completion_date', '>', Carbon::now()->toDateString())
                ->where('document_status', '!=', ConstantHelper::DRAFT)
                ->where('document_status', ConstantHelper::SUBMITTED);
        }
        if ($request->has('search') && $request->search === 'pending') {
            $query->where('expected_completion_date', '>', Carbon::now()->toDateString())
                ->where('document_status', '!=', ConstantHelper::DRAFT)
                ->where('document_status', '!=', ConstantHelper::SUBMITTED)
                ->where('document_status', '!=', ConstantHelper::APPROVED);
        }

        // Get the filtered data
        $data = $query->get();

        // Clone the base query to apply the same filters for counts

        // Return the view with data and counts
        return view('file-tracking.index', compact('data', 'signed_count', 'unsigned_count', 'pending_count', 'overdue_count'));
    }



    /**
     * Show the form for creating a new resource.
     *
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
        return view('file-tracking.create', compact('series'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $userData = Helper::getAuthenticatedUser();
        $organization_id = $userData->organization->id;
        $group_id = $userData->organization->group_id;
        $company_id = $userData->organization->company_id;
        $user_id = $userData->id;
        $type = get_class($userData);

        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'expected_completion_date' => 'required|date|after_or_equal:today',
                'comments' => 'nullable|string|max:500',
                'file' => 'required|file|mimes:pdf,docx|max:5120',
                'book_id' => 'required',
                'document_number' => 'required',
            ], [
                'expected_completion_date.required' => 'The expected completion date is required.',
                'expected_completion_date.date' => 'The expected completion date must be a valid date.',
                'expected_completion_date.after_or_equal' => 'The expected completion date must be today or later.',
                'comments.string' => 'The comments must be a valid string.',
                'comments.max' => 'The comments must not exceed 500 characters.',
                'file.file' => 'The uploaded file must be a valid file.',
                'file.mimes' => 'The uploaded file must be a PDF.',
                'file.max' => 'The uploaded file size must not exceed 5 MB.',
                'book_id.required' => 'The book series is required.',
                'document_number.required' => 'The document number is required.',
                'file.required' => 'The file is required.',
            ]);

            if ($validator->fails()) {
                return redirect()
                    ->route('file-tracking.create')
                    ->withInput()
                    ->withErrors($validator);
            }

            // Handle file upload if present
            $filePath = null;
            if ($request->hasFile('file')) {
                $filePath = $request->file('file')->store('file_tracking', 'public');
            }
            $signed_by = null;
            if ($request->input('signed_by') != null) {
                $signed_by = json_encode($request->input('signed_by'));
            }

            // Save data to the database
            if ($request->document_status == ConstantHelper::SUBMITTED) {
                $status = self::checkApprovalRequired($request->input('book_id'));
                if ($status == null) {
                    return redirect()
                        ->route('file-tracking.create')
                        ->withInput()
                        ->withErrors(['error' => 'This series does not have any approvals. Please ensure that at least some approval exists before proceeding.']);
                } else {
                    $filetrack = FileTracking::create([
                        'book_id' => $request->input('book_id'),
                        'group_id' => $group_id,
                        'company_id' => $company_id,
                        'organization_id' => $organization_id,
                        'document_date' => Carbon::now()->format('Y-m-d'),
                        'document_number' => $request->input('document_number'),
                        'doc_number_type' => $request->input('doc_number_type'),
                        'doc_reset_pattern' => $request->input('doc_reset_pattern'),
                        'doc_prefix' => $request->input('doc_prefix'),
                        'doc_suffix' => $request->input('doc_suffix'),
                        'doc_no' => $request->input('doc_no'),
                        'document_status' => $status,
                        'file_name' => $request->input('file_name'),
                        'created_by' => $user_id,
                        'type' => $type,
                        'expected_completion_date' => $request->input('expected_completion_date'),
                        'comment' => $request->input('comments'),
                        'signed_by' => $signed_by,
                        'approval_level' => 1,
                        'revision_number' => 0,
                        'file' => $filePath,
                    ]);

                    Helper::approveDocument($filetrack->book_id, $filetrack->id, 0, $filetrack->comment, $request->file('file'), $filetrack->approval_level, 'submit');

                    self::approval_list($filetrack->id);
                    return redirect()
                        ->route('file-tracking.index')
                        ->with('success', 'File tracking record created successfully.');
                }
            } else {
                $filetrack = FileTracking::create([
                    'book_id' => $request->input('book_id'),
                    'group_id' => $group_id,
                    'company_id' => $company_id,
                    'organization_id' => $organization_id,
                    'document_date' => Carbon::now()->format('Y-m-d'),
                    'document_number' => $request->input('document_number'),
                    'doc_number_type' => $request->input('doc_number_type'),
                    'doc_reset_pattern' => $request->input('doc_reset_pattern'),
                    'doc_prefix' => $request->input('doc_prefix'),
                    'doc_suffix' => $request->input('doc_suffix'),
                    'doc_no' => $request->input('doc_no'),
                    'document_status' => 'draft',
                    'file_name' => $request->input('file_name'),
                    'created_by' => $user_id,
                    'type' => $type,
                    'expected_completion_date' => $request->input('expected_completion_date'),
                    'comment' => $request->input('comments'),
                    'signed_by' => $signed_by,
                    'file' => $filePath,
                ]);
                Helper::approveDocument($filetrack->book_id, $filetrack->id, 0, $filetrack->comment, $request->file('file'), $filetrack->approval_level, $request->document_status);
                return redirect()
                    ->route('file-tracking.index')
                    ->with('success', 'File tracking record created successfully.');
            }
        } catch (\Exception $e) {
            Log::error('Error creating file tracking record: ' . $e->getMessage());
            return redirect()
                ->route('file-tracking.index')
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $userData = Helper::getAuthenticatedUser();
        $parentURL = request() -> segments()[0];
        

        $servicesBooks = Helper::getAccessibleServicesFromMenuAlias($parentURL);
        if (count($servicesBooks['services']) == 0) {
           return redirect() -> route('/');
       }
       $firstService = $servicesBooks['services'][0];
       $series = Helper::getBookSeriesNew($firstService -> alias, $parentURL)->get();
        $data = FileTracking::withDefaultGroupCompanyOrg()->findorFail($id);
        $history = Helper::getApprovalHistory($data->book_id, $id, 0);


        $userId = Helper::getAuthenticatedUser()->id;
        $type = get_class(Helper::getAuthenticatedUser()) == "App\Models\Employee" ? 'employee' : 'user';
        $creator_type = $data->type === "App\Models\Employee" ? 'employee' : 'user';

        $pending_signer = json_decode($data->pending_signer);
        $approval_teams = json_decode($data->approval_teams);
        $buttons = self::actionButtonDisplay($data->book_id, $data->document_status, $data->id, $data->approval_level, $data->created_by, $creator_type, 0);
        return view('file-tracking.show', compact('series', 'data', 'history', 'buttons'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $userData = Helper::getAuthenticatedUser();
        $parentURL = request() -> segments()[0];
        

        $servicesBooks = Helper::getAccessibleServicesFromMenuAlias($parentURL);
        if (count($servicesBooks['services']) == 0) {
           return redirect() -> route('/');
       }
       $firstService = $servicesBooks['services'][0];
       $series = Helper::getBookSeriesNew($firstService -> alias, $parentURL)->get();
        $data = FileTracking::withDefaultGroupCompanyOrg()->findorFail($id);
        return view('file-tracking.edit', compact('series', 'data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'expected_completion_date' => 'required|date|after_or_equal:today',
                'comments' => 'nullable|string|max:500',
                'file' => 'nullable|file|mimes:pdf|max:5120',
            ], [
                'expected_completion_date.required' => 'The expected completion date is required.',
                'expected_completion_date.date' => 'The expected completion date must be a valid date.',
                'expected_completion_date.after_or_equal' => 'The expected completion date must be today or later.',
                'comments.string' => 'The comments must be a valid string.',
                'comments.max' => 'The comments must not exceed 500 characters.',
                'file.file' => 'The uploaded file must be a valid file.',
                'file.mimes' => 'The uploaded file must be a PDF.',
                'file.max' => 'The uploaded file size must not exceed 5 MB.',
            ]);

            if ($validator->fails()) {
                return redirect()
                    ->route('file-tracking.edit', $id)
                    ->withInput()
                    ->withErrors($validator);
            }

            // Fetch the existing record
            $filetrack = FileTracking::withDefaultGroupCompanyOrg()->findOrFail($id);

            // Handle file upload
            $filePath = $filetrack->file;
            if ($request->hasFile('file')) {
                // Delete old file
                if ($filePath && Storage::disk('public')->exists($filePath)) {
                    Storage::disk('public')->delete($filePath);
                }
                // Store new file
                $filePath = $request->file('file')->store('file_tracking', 'public');
            }

            $signed_by = null;
            if ($request->input('signed_by') != null) {
                $signed_by = json_encode($request->input('signed_by'));
            }


            // Update the record
            $filetrack->update([
                'document_status' => 'draft',
                'expected_completion_date' => $request->input('expected_completion_date'),
                'comment' => $request->input('comments'),
                'file_name' => $request->input('file_name'),
                'signed_by' => $signed_by,
                'file' => $filePath,
            ]);

            if ($request->document_status == ConstantHelper::SUBMITTED) {
                $status = self::checkApprovalRequired($filetrack->book_id);
                if ($status != ConstantHelper::SUBMITTED) {
                    return redirect()
                        ->route('file-tracking.edit', $id)
                        ->withInput()
                        ->withErrors(['error' => 'This series does not have any approvals. Please ensure that at least some approval exists before proceeding.']);
                } else {
                    $fileTracking = FileTracking::findOrFail($id);
                    $fileTracking->document_status = $status;
                    $fileTracking->save();
                    Helper::approveDocument($filetrack->book_id, $filetrack->id, 0, $filetrack->comment, $request->file('file'), $filetrack->approval_level, 'submit');
                    self::approval_list($filetrack->id);
                    return redirect()
                        ->route('file-tracking.index')
                        ->with('success', 'File tracking record updated successfully.');
                }
            } else {
                Helper::approveDocument($filetrack->book_id, $filetrack->id, 0, $filetrack->comment, $request->file('file'), $filetrack->approval_level, $request->document_status);
                return redirect()
                    ->route('file-tracking.index')
                    ->with('success', 'File tracking record updated successfully.');
            }
        } catch (\Exception $e) {
            Log::error('Error updating file tracking record: ' . $e->getMessage());
            return redirect()
                ->route('file-tracking.edit', $id)
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function sign($id, Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'comments' => 'required|string|max:500',
            ], [
                'comments.string' => 'The comments must be a valid string.',
                'comments.max' => 'The comments must not exceed 500 characters.',
                'comments.required' => 'The comments is required.',
            ]);

            if ($validator->fails()) {
                return redirect()
                    ->route('file-tracking.show', $id)
                    ->withInput()
                    ->withErrors($validator);
            }

            // Find the file tracking record by ID
            $fileTracking = FileTracking::withDefaultGroupCompanyOrg()->findOrFail($id);

            // Get the authenticated user ID and name
            $user = Helper::getAuthenticatedUser();
            $userId = $user->id;

            $type = get_class(Helper::getAuthenticatedUser()) == "App\Models\Employee" ? 'employee' : 'user';
            $userName = $user->name; // Fetch the authenticated user's name
            $userType = get_class($user); // Default to 'user' if type is not provided
            $comment = $request->input('comments', ''); // Default to an empty string if no comment is provided
            if ($fileTracking->teams && $fileTracking->teams->count() > 0) {
                $team = $fileTracking->teams->where('user_id', $userId)->where('user_type', $type)->first();
                if ($team && $team->count() > 0) {
                    $sign = $team->user->sign;
                    if ($sign && $sign->count() > 0) {
                        $sign_id = $sign->id;
                        // Check if the document is already signed by the current user
                        $signedBy = json_decode($fileTracking->signed_by, true) ?? []; // Decode signed_by field

                        // Check if the user is already in the signed_by array
                        $isSignedByUser = false;
                        foreach ($signedBy as $signer) {
                            if ($signer['sign_id'] == $sign_id) {
                                $isSignedByUser = true;
                                break;
                            }
                        }

                        if ($isSignedByUser) {
                            return redirect()->route('file-tracking.show', $fileTracking->id)->withErrors('Your Sign Already Added!.');
                        } else {
                            $status = Helper::approveDocument($fileTracking->book_id, $fileTracking->id, 0, $request->comments, null, $fileTracking->approval_level, 'approve');

                            $signedBy[] = [
                                'sign_id' => $sign_id,
                                'user_id' => $userId,
                                'user_name' => $sign->name,  // Add user name for display
                                'user_type' => $userType,  // Store the user's type (e.g., "User" or "Admin")
                                'signature' => $sign->sign_upload_file,
                                'designation' => $sign->designation,
                                'remarks' => $comment  // Store the user's comment
                            ];

                            $pending = [];
                            $history = Helper::getApprovalHistory($fileTracking->book_id, $id, 0);
                            foreach ($history as $his) {
                                if ($his->approval_type == 'pending') {
                                    $pending[] = [
                                        'user_id' => $his->user_id,
                                        'user_type' => $his->user_type,
                                        'user_name' => $his->name,
                                    ];
                                }
                            }

                            // Output the result
                            $fileTracking->pending_signer = empty($pending) ? null : json_encode($pending);
                            $fileTracking->signed_by = json_encode($signedBy);
                            $fileTracking->document_status = $status['approvalStatus'];
                            $fileTracking->approval_level = $status['nextLevel'];
                            $fileTracking->save();

                            self::processFileAndAddSignatures($id);
                            return redirect()->route('file-tracking.index')->with('success', 'Document approve and signed successfully.');
                        }
                    }
                    return redirect()->route('file-tracking.show', $fileTracking->id)->withErrors('Please Add your sign first!.');
                } else {
                    return redirect()->route('file-tracking.show', $fileTracking->id)->withErrors('You are not authorized to sign this document.');
                }
            } else {
                return redirect()->route('file-tracking.show', $fileTracking->id)->withErrors('You are not authorized to sign this document.');
            }
        } catch (\Exception $e) {
            Log::error('Error signing document: ' . $e->getMessage());
            return redirect()->route('file-tracking.show', $fileTracking->id)->withErrors('Error signing document: ' . $e->getMessage());
        }
    }
    public function showSignFile($file)
{
    // Retrieve the file record
    $data = FileTracking::withDefaultGroupCompanyOrg()->findOrFail($file);
    $file = $data->signed_file; // Assuming the `signed_file` column contains the file path
    $filePath = storage_path('app/public/' . $file);

    // Check if the file exists
    if (!file_exists($filePath)) {
        abort(404, 'File not found.');
    }

    // Determine the file extension
    $fileExtension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

    // Set headers based on the file type
    $headers = [];
    if ($fileExtension === 'pdf') {
        $headers = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $data->document_number . '_signed.pdf"',
        ];
    } elseif ($fileExtension === 'docx') {
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'Content-Disposition' => 'inline; filename="' . $data->document_number . '_signed.docx"',
        ];
    } else {
        abort(415, 'Unsupported file type.');
    }

    // Serve the file with the appropriate headers
    return response()->file($filePath, $headers);
}

public function showFile($file)
{
    // Retrieve the file record
    $data = FileTracking::withDefaultGroupCompanyOrg()->findOrFail($file);
    $file = $data->file; // Assuming the `file` column contains the file path
    $filePath = storage_path('app/public/' . $file);

    // Check if the file exists
    if (!file_exists($filePath)) {
        abort(404, 'File not found.');
    }

    // Determine the file extension
    $fileExtension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

    // Set headers based on the file type
    $headers = [];
    if ($fileExtension === 'pdf') {
        $headers = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $data->document_number . '.pdf"',
        ];
    } elseif ($fileExtension === 'docx') {
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'Content-Disposition' => 'inline; filename="' . $data->document_number . '.docx"',
        ];
    } else {
        abort(415, 'Unsupported file type.');
    }

    // Serve the file with the appropriate headers
    return response()->file($filePath, $headers);
}
    function addSignaturesWithLineAndCaption($fileTrackingId)
    {
        $fileTracking = FileTracking::withDefaultGroupCompanyOrg()->findOrFail($fileTrackingId);
        $pdfPath = storage_path('app/public/' . $fileTracking->file);
        $jsonSignatures = $fileTracking->signed_by;

        $signatures = json_decode($jsonSignatures, true);
        if ($signatures) {
            $pdf = new Fpdi();
            $pageCount = $pdf->setSourceFile($pdfPath);

            // Add all original pages to the PDF
            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                $templateId = $pdf->importPage($pageNo);
                $size = $pdf->getTemplateSize($templateId);
                $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                $pdf->useTemplate($templateId);
            }

            // Add a blank page for signatures
            $lastPageWidth = $size['width'];
            $lastPageHeight = $size['height'];
            $pdf->AddPage($size['orientation'], [$lastPageWidth, $lastPageHeight]);

            // Add a heading to the signature page
            $pdf->SetFont('Arial', 'B', 16); // Bold font, size 16
            $pdf->SetXY(10, 10); // Position for the heading
            $pdf->Cell(0, 10, 'Signature Page', 0, 1, 'C'); // Center-aligned heading

            // Signature section layout
            $pdf->SetFillColor(245, 245, 245); // Light gray background
            $pdf->SetFont('Arial', '', 10);
            $x = 10; // Left margin
            $y = 30; // Start below the heading
            $rowHeight = 40; // Height for each signature block (image + text)

            foreach ($signatures as $signature) {
                $signaturePath = storage_path('app/public/' . $signature['signature']);
                if (file_exists($signaturePath)) {
                    // Check if the next block fits on the page
                    if ($y + $rowHeight > $lastPageHeight) {
                        $pdf->AddPage($size['orientation'], [$lastPageWidth, $lastPageHeight]);
                        $y = 20; // Reset Y position for the new page
                    }

                    // Draw signature image
                    $pdf->Image($signaturePath, $x, $y, 40, 20);

                    // Add text details
                    $textX = $x + 45; // Adjust text placement to the right of the image
                    $pdf->SetXY($textX, $y);
                    $pdf->Cell(0, 6, 'Name: ' . $signature['user_name'], 0, 1);
                    $pdf->SetXY($textX, $y + 8);
                    $pdf->Cell(0, 6, 'Designation: ' . $signature['designation'], 0, 1);
                    $pdf->SetXY($textX, $y + 16);
                    $pdf->Cell(0, 6, 'Remarks: ' . $signature['remarks'], 0, 1);
                    $pdf->SetXY($textX, $y + 24);
                    $pdf->Cell(0, 6, 'Date: ' . date('d/m/Y'), 0, 1);

                    // Move Y for the next signature block
                    $y += $rowHeight;
                }
            }

            // Save the PDF to the file system
            $outputPath = storage_path('app/public/file_tracking/' . pathinfo($fileTracking->file, PATHINFO_FILENAME) . '_signed.pdf');
            $outputDirectory = dirname($outputPath);
            if (!is_dir($outputDirectory)) {
                mkdir($outputDirectory, 0777, true);
            }

            $pdf->Output('F', $outputPath);

            // Update the file tracking record
            $fileTracking->signed_file = 'file_tracking/' . pathinfo($fileTracking->file, PATHINFO_FILENAME) . '_signed.pdf';
            $fileTracking->save();

            return $outputPath;
        }

        return null;
    }


    function addSignaturesWithLineAndCaptionToWord($fileTrackingId)
    {
        try {
            $fileTracking = FileTracking::withDefaultGroupCompanyOrg()->findOrFail($fileTrackingId);
            $docxPath = storage_path('app/public/' . $fileTracking->file);
            $jsonSignatures = $fileTracking->signed_by;

            $signatures = json_decode($jsonSignatures, true);
            if ($signatures) {
                // Load the existing Word file
                $phpWord = IOFactory::load($docxPath);

                // Add a new section for the signature page
                $section = $phpWord->addSection();
                $section->addText(
                    'Signature Page',
                    ['bold' => true, 'size' => 16],
                    ['alignment' => 'center']
                );
                $section->addText('');

                foreach ($signatures as $signature) {
                    // Add a new row for each signature
                    $table = $section->addTable();
                    $table->addRow();

                    // Add the signature image
                    $signaturePath = storage_path('app/public/' . $signature['signature']);
                    if (file_exists($signaturePath)) {
                        $table->addCell(2000)->addImage(
                            $signaturePath,
                            [
                                'width' => 120,
                                'height' => 40,
                                'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
                            ]
                        );
                    } else {
                        throw new Exception("Signature image not found: " . $signaturePath);
                    }

                    // Add the details in the next cell
                    $cell = $table->addCell(8000);
                    $cell->addText('Name: ' . ($signature['user_name'] ?? 'N/A'));
                    $cell->addText('Designation: ' . ($signature['designation'] ?? 'N/A'));
                    $cell->addText('Remarks: ' . ($signature['remarks'] ?? 'N/A'));
                    $cell->addText('Date: ' . date('d/m/Y'));
                    $cell->addText('');
                }

                // Save the updated Word file
                $outputPath = storage_path('app/public/file_tracking/' . pathinfo($fileTracking->file, PATHINFO_FILENAME) . '_signed.docx');
                $outputDirectory = dirname($outputPath);
                if (!is_dir($outputDirectory)) {
                    mkdir($outputDirectory, 0777, true);
                }

                $writer = IOFactory::createWriter($phpWord, 'Word2007');
                $writer->save($outputPath);

                // Update the file tracking record
                $fileTracking->signed_file = 'file_tracking/' . pathinfo($fileTracking->file, PATHINFO_FILENAME) . '_signed.docx';
                $fileTracking->save();

                return $outputPath;
            } else {
                throw new Exception("No signatures found for the provided file tracking ID: " . $fileTrackingId);
            }
        } catch (\PhpOffice\PhpWord\Exception\Exception $e) {
            // Handle PhpWord-specific exceptions
            Log::error("PhpWord error: " . $e->getMessage());
            return "An error occurred while processing the Word file.";
        } catch (Exception $e) {
            // Handle generic exceptions
            Log::error("General error: " . $e->getMessage());
            return "An unexpected error occurred: " . $e->getMessage();
        }
    }

    //for add in all pages

    public static function approval_list($id)
    {

        try {
            $signer_data = [];
            $pending = [];

            $fileTracking = FileTracking::with(relations: ['teams' => function ($query) {
                $query->orderBy('id', 'asc');  // Sort teams by 'id' in ascending order
            }])->where('id', $id)->first();  // Use first() to get a single FileTracking instance

            if ($fileTracking) {
                foreach ($fileTracking->teams as $index => $team) {
                    $signer_data[] = [
                        'approval_id' => $team->id,
                        'user_id' => $team->user_id,
                        'user_type' => $team->user_type,
                        'user_name' => $team->user->name,
                        'level' => $team->level->level,
                    ];
                }
            }
            $history = Helper::getApprovalHistory($fileTracking->book_id, $id, 0);
            foreach ($history as $his) {
                if ($his->approval_type == 'pending') {
                    $pending[] = [
                        'user_id' => $his->user_id,
                        'user_type' => $his->user_type,
                        'user_name' => $his->name,
                    ];
                }
            }

            $fileTracking->approval_teams = json_encode($signer_data);
            $fileTracking->pending_signer = empty($pending) ? null : json_encode($pending);
            $fileTracking->save();
        } catch (\Exception $e) {
            Log::error('Error fetching approval list: ' . $e->getMessage());
        }
    }
    public static function checkApprovalRequired($book_id = null)
    {
        $user = Helper::getAuthenticatedUser();
        $aw = BookLevel::where('book_id', $book_id)
            ->where('organization_id', $user->organization_id)
            ->where('level', 1)
            ->count();
        if ($aw > 0) {
            return ConstantHelper::SUBMITTED;
        } else {
            return null;
        }
    }
    public static function actionButtonDisplay($bookId, $docStatus, $docId, $docApprLevel, int $createdBy = 0, $creatorType, $revisionNumber = 0)
    {
        // dd($docStatus);
        $draft = false;
        $submit = false;
        $approve = false;
        $amend = false;
        $post = false;
        $voucher = false;
        $user = Helper::getAuthenticatedUser();
        $book = Book::where('id', $bookId)->first();
        $bookTypeServiceAlias = $book?->service?->alias;
        $currUser = Helper::userCheck();
        $currUser['type'] = get_class(Helper::getAuthenticatedUser()) == "App\Models\Employee" ? 'employee' : 'user';


        if ($docStatus == ConstantHelper::DRAFT || $docStatus == ConstantHelper::REJECTED) {
            $draft = true;
            $submit = true;
        }
        if ($docStatus == ConstantHelper::SUBMITTED) {
            $approvalWorkflow = BookLevel::where('book_id', $book->id)
                ->where('organization_id', $user->organization_id)
                ->where('level', 1)
                ->whereHas('approvers', function ($approver) use ($currUser) {
                    $approver->where('user_id', $currUser['user_id'])
                        ->where('user_type', $currUser['type']);
                })
                ->first();
            if ($approvalWorkflow) {
                $approve = true;
            }
        }
        if ($docStatus == ConstantHelper::APPROVED || $docStatus == ConstantHelper::APPROVAL_NOT_REQUIRED) {
            $amendmentWorkflow = AmendmentWorkflow::where('book_id', $book->id)
                ->where('organization_id', $user->organization_id)
                ->whereHas('approvers', function ($user) use ($currUser) {
                    $user->where('user_id', $currUser['user_id'])
                        ->where('user_type', $currUser['type']);
                })
                ->first();
            if ($amendmentWorkflow) {
                $amend = true;
            }
            $postingRequiredParam = OrganizationBookParameter::where('book_id', $bookId)
                ->where('parameter_name', ServiceParametersHelper::GL_POSTING_REQUIRED_PARAM)->first();
            if (isset($postingRequiredParam)) {
                $isPostingRequired = ($postingRequiredParam->parameter_value[0] ?? '') === "yes" ? true : false;
                $post = $isPostingRequired;
            }
        }
        if ($docStatus == ConstantHelper::PARTIALLY_APPROVED) {
            $approvalWorkflow = BookLevel::where('book_id', $book->id)
                ->where('organization_id', $user->organization_id)
                ->whereHas('approvers', function ($approver) use ($currUser) {
                    $approver->where('user_id', $currUser['user_id'])
                        ->where('user_type', $currUser['type']);
                })
                ->first();
            if ($approvalWorkflow) {
                $checkApproved = Helper::checkApprovedHistory($bookTypeServiceAlias, $docId, $revisionNumber, [$user->id]);
               if (!count($checkApproved)) {
                    if ($approvalWorkflow->level == $docApprLevel) {
                        $approve = true;
                    }
                }
            }
        }
        if ($docStatus == ConstantHelper::POSTED) {
            $voucher = true;
        }
        //Creator of document cannot approve
        if ($user->id === $createdBy && $currUser['type'] == $creatorType) {
            $approve = false;
        }
        return [
            'draft' => $draft,
            'submit' => $submit,
            'approve' => $approve,
            'amend' => $amend,
            'post' => $post,
            'voucher' => $voucher
        ];
    }
    public function processFileAndAddSignatures($id)
    {
        try {
            // Retrieve the file tracking record
            $fileTracking = FileTracking::findOrFail($id);

            // Extract the file extension
            $filePath = $fileTracking->file;
            $fileExtension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

            // Call the appropriate method based on the file extension
            if ($fileExtension === 'pdf') {
                return self::addSignaturesWithLineAndCaption($id);
            } elseif ($fileExtension === 'docx') {
                return self::addSignaturesWithLineAndCaptionToWord($id);
            } else {
                throw new Exception("Unsupported file type: " . $fileExtension);
            }
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error("File tracking record not found: " . $e->getMessage());
            return "File tracking record not found.";
        } catch (Exception $e) {
            Log::error("Error processing file: " . $e->getMessage());
            return "An error occurred: " . $e->getMessage();
        }
    }
}
