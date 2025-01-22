<?php
namespace App\Http\Controllers;

use App\Models\ErpSaleInvoice;
use DB;
use App\Helpers\Helper;
use App\Helpers\InventoryHelper;

use App\Models\Bom;
use App\Models\ErpSaleOrder;
use App\Models\ExpenseHeader;
use App\Models\MrnDetail;
use App\Models\MrnHeader;
use App\Models\PbHeader;
use App\Models\PurchaseIndent;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
class DocumentApprovalController extends Controller
{
    # Bom Approval
    public function bom(Request $request)
    {
        $request->validate([
            'remarks' => 'nullable',
            'attachment' => 'nullable'
        ]);
        DB::beginTransaction();
        try {
            $bom = Bom::find($request->id);
            $bookId = $bom->book_id; 
            $docId = $bom->id;
            $docValue = $bom->total_value;
            $remarks = $request->remarks;
            $attachments = $request->file('attachment');
            $currentLevel = $bom->approval_level;
            $revisionNumber = $bom->revision_number ?? 0;
            $actionType = $request->action_type; // Approve or reject
            $modelName = get_class($bom);
            $approveDocument = Helper::approveDocument($bookId, $docId, $revisionNumber , $remarks, $attachments, $currentLevel, $actionType, $docValue,$modelName);
            $bom->approval_level = $approveDocument['nextLevel'];
            $bom->document_status = $approveDocument['approvalStatus'];
            $bom->save();

            DB::commit();
            return response()->json([
                'message' => "Document $actionType successfully!",
                'data' => $bom,
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => "Error occurred while $actionType bom document.",
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    # PO Approval
    public function po(Request $request)
    {
        $request->validate([
            'remarks' => 'nullable',
            'attachment' => 'nullable'
        ]);
        DB::beginTransaction();
        try {
            $po = PurchaseOrder::find($request->id);
            $bookId = $po->book_id; 
            $docId = $po->id;
            $docValue = $po->grand_total_amount;
            $remarks = $request->remarks;
            $attachments = $request->file('attachment');
            $currentLevel = $po->approval_level;
            $revisionNumber = $po->revision_number ?? 0;
            $actionType = $request->action_type; // Approve or reject
            $modelName = get_class($po);
            $approveDocument = Helper::approveDocument($bookId, $docId, $revisionNumber , $remarks, $attachments, $currentLevel, $actionType, $docValue,$modelName);
            $po->approval_level = $approveDocument['nextLevel'];
            $po->document_status = $approveDocument['approvalStatus'];
            $po->save();

            DB::commit();
            return response()->json([
                'message' => "Document $actionType successfully!",
                'data' => $po,
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => "Error occurred while $actionType po document.",
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    # PO Approval
    public function pi(Request $request)
    {
        $request->validate([
            'remarks' => 'nullable',
            'attachment' => 'nullable'
        ]);
        DB::beginTransaction();
        try {
            $pi = PurchaseIndent::find($request->id);
            $bookId = $pi->book_id; 
            $docId = $pi->id;
            $remarks = $request->remarks;
            $attachments = $request->file('attachment');
            $currentLevel = $pi->approval_level;
            $revisionNumber = $pi->revision_number ?? 0;
            $actionType = $request->action_type; // Approve or reject
            $modelName = get_class($pi);
            $approveDocument = Helper::approveDocument($bookId, $docId, $revisionNumber , $remarks, $attachments, $currentLevel, $actionType, 0, $modelName);
            $pi->approval_level = $approveDocument['nextLevel'];
            $pi->document_status = $approveDocument['approvalStatus'];
            $pi->save();

            DB::commit();
            return response()->json([
                'message' => "Document $actionType successfully!",
                'data' => $pi,
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => "Error occurred while $actionType pi document.",
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function saleOrder(Request $request)
    {
        $request->validate([
            'remarks' => 'nullable|string|max:255',
            'attachment' => 'nullable'
        ]);
        DB::beginTransaction();
        try {
            $saleOrder = ErpSaleOrder::find($request->id);
            $bookId = $saleOrder->book_id; 
            $docId = $saleOrder->id;
            $docValue = $saleOrder->total_amount;
            $remarks = $request->remarks;
            $attachments = $request->file('attachments');
            $currentLevel = $saleOrder->approval_level;
            $revisionNumber = $saleOrder->revision_number ?? 0;
            $actionType = $request->action_type; // Approve or reject
            $modelName = get_class($saleOrder);
            $approveDocument = Helper::approveDocument($bookId, $docId, $revisionNumber , $remarks, $attachments, $currentLevel, $actionType, $docValue, $modelName);
            $saleOrder->approval_level = $approveDocument['nextLevel'];
            $saleOrder->document_status = $approveDocument['approvalStatus'];
            $saleOrder->save();

            DB::commit();
            return response()->json([
                'message' => "Document $actionType successfully!",
                'data' => $saleOrder,
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => "Error occurred while $actionType Sale Order document.",
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    //Sales Invoice / Delivery Note / Delivery Note CUM Invoice
    public function saleInvoice(Request $request)
    {
        $request->validate([
            'remarks' => 'nullable|string|max:255',
            'attachment' => 'nullable'
        ]);
        DB::beginTransaction();
        try {
            $saleInvoice = ErpSaleInvoice::find($request->id);
            $bookId = $saleInvoice->book_id; 
            $docId = $saleInvoice->id;
            $docValue = $saleInvoice->total_amount;
            $remarks = $request->remarks;
            $attachments = $request->file('attachments');
            $currentLevel = $saleInvoice->approval_level;
            $revisionNumber = $saleInvoice->revision_number ?? 0;
            $actionType = $request->action_type; // Approve or reject
            $modelName = get_class($saleInvoice);
            $approveDocument = Helper::approveDocument($bookId, $docId, $revisionNumber , $remarks, $attachments, $currentLevel, $actionType, $docValue, $modelName);
            $saleInvoice->approval_level = $approveDocument['nextLevel'];
            $saleInvoice->document_status = $approveDocument['approvalStatus'];
            $saleInvoice->save();

            DB::commit();
            return response()->json([
                'message' => "Document $actionType successfully!",
                'data' => $saleInvoice,
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => "Error occurred while $actionType Sale Order document.",
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // MRN Document Approval
    public function mrn(Request $request)
    {
        $request->validate([
            'remarks' => 'nullable',
            'attachment' => 'nullable'
        ]);
        DB::beginTransaction();
        try {
            $mrn = MrnHeader::find($request->id);
            $bookId = $mrn->series_id; 
            $docId = $mrn->id;
            $docValue = $mrn->total_amount;
            $remarks = $request->remarks;
            $attachments = $request->file('attachment');
            $currentLevel = $mrn->approval_level;
            $revisionNumber = $mrn->revision_number ?? 0;
            $actionType = $request->action_type; // Approve or reject
            $modelName = get_class($mrn);
            $approveDocument = Helper::approveDocument($bookId, $docId, $revisionNumber , $remarks, $attachments, $currentLevel, $actionType, $docValue, $modelName);
            $mrn->approval_level = $approveDocument['nextLevel'];
            $mrn->document_status = $approveDocument['approvalStatus'];
            $mrn->save();

            DB::commit();
            return response()->json([
                'message' => "Document $actionType successfully!",
                'data' => $mrn,
            ]);
        } catch (Exception $e) {
            dd($e);
            DB::rollBack();
            return response()->json([
                'message' => "Error occurred while $actionType mrn document.",
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Expense Document Approval
    public function expense(Request $request)
    {
        $request->validate([
            'remarks' => 'nullable',
            'attachment' => 'nullable'
        ]);
        DB::beginTransaction();
        try {
            $expense = ExpenseHeader::find($request->id);
            $bookId = $expense->series_id; 
            $docId = $expense->id;
            $docValue = $expense->total_amount;
            $remarks = $request->remarks;
            $attachments = $request->file('attachment');
            $currentLevel = $expense->approval_level;
            $revisionNumber = $expense->revision_number ?? 0;
            $actionType = $request->action_type; // Approve or reject
            $modelName = get_class($expense);
            $approveDocument = Helper::approveDocument($bookId, $docId, $revisionNumber , $remarks, $attachments, $currentLevel, $actionType, $docValue, $modelName);
            $expense->approval_level = $approveDocument['nextLevel'];
            $expense->document_status = $approveDocument['approvalStatus'];
            $expense->save();

            DB::commit();
            return response()->json([
                'message' => "Document $actionType successfully!",
                'data' => $expense,
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => "Error occurred while $actionType expense document.",
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // MRN Document Approval
    public function purchaseBill(Request $request)
    {
        $request->validate([
            'remarks' => 'nullable',
            'attachment' => 'nullable'
        ]);
        DB::beginTransaction();
        try {
            $mrn = PbHeader::find($request->id);
            $bookId = $mrn->series_id; 
            $docId = $mrn->id;
            $docValue = $mrn->total_amount;
            $remarks = $request->remarks;
            $attachments = $request->file('attachment');
            $currentLevel = $mrn->approval_level;
            $revisionNumber = $mrn->revision_number ?? 0;
            $actionType = $request->action_type; // Approve or reject
            $modelName = get_class($mrn);
            $approveDocument = Helper::approveDocument($bookId, $docId, $revisionNumber , $remarks, $attachments, $currentLevel, $actionType, $docValue, $modelName);
            $mrn->approval_level = $approveDocument['nextLevel'];
            $mrn->document_status = $approveDocument['approvalStatus'];
            $mrn->save();

            DB::commit();
            return response()->json([
                'message' => "Document $actionType successfully!",
                'data' => $mrn,
            ]);
        } catch (Exception $e) {
            dd($e);
            DB::rollBack();
            return response()->json([
                'message' => "Error occurred while $actionType purchase bill document.",
                'error' => $e->getMessage(),
            ], 500);
        }
    }

}
