<?php

namespace App\Helpers;
use App\Models\Book;
use App\Models\LandLease;
use App\Models\LandLeasePlot;
use App\Models\StockLedger;
use App\Models\Vendor;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\DiscountMaster;
use App\Models\DocumentApproval;
use App\Models\ErpInvoiceItem;
use App\Models\ErpSaleInvoice;
use App\Models\ErpSaleInvoiceTed;
use App\Models\ErpSaleOrder;
use App\Models\ExpenseMaster;
use App\Models\Group;
use App\Models\ItemDetail;
use App\Models\Ledger;
use App\Models\OrganizationBookParameter;
use App\Models\Service;
use App\Models\TaxDetail;
use App\Models\PaymentVoucher;
use App\Models\Voucher;
use App\Models\HomeLoan;
use App\Models\LoanDisbursement;
use App\Models\LoanFinancialAccount;
use App\Models\OrganizationCompany;
use App\Models\CurrencyExchange;
use App\Models\LoanProcessFee;
use App\Models\RecoveryLoan;
use App\Models\LoanSettlement;

use App\Models\MrnHeader;
use App\Models\MrnDetail;
use App\Models\MrnExtraAmount;
use Illuminate\Support\Collection;

use App\Models\PbHeader;
use App\Models\PbDetail;
use App\Models\PbTed;

use App\Models\PRHeader;
use App\Models\PRDetail;
use App\Models\PRTed;

use App\Models\ExpenseHeader;
use App\Models\ExpenseDetail;
use App\Models\ExpenseTed;

class FinancialPostingHelper
{
    const DEBIT = "Debit";
    const CREDIT = "Credit";
    const COGS_ACCOUNT = 'COGS';
    const SALES_ACCOUNT = 'Sales';
    const STOCK_ACCOUNT = 'Stock';
    const CUSTOMER_ACCOUNT = 'Customer';
    const PAYMENT_ACCOUNT = 'Payment';
    const VENDOR_ACCOUNT = 'Party';

    const WRITE_OFF_ACCOUNT = 'Writeoff';
    const Loan_Customer_Receivable_ACCOUNT='Loan Customer Receivable';

    const INTEREST_ACCOUNT = 'Interest';
    const ProcessFee_ACCOUNT = 'ProcessFee';
    const Bank_ACCOUNT = 'Bank';
    const SUPPLIER_ACCOUNT = 'Supplier';
    const TAX_ACCOUNT = 'Tax';
    const EXPENSE_ACCOUNT = 'Expense';
    const DISCOUNT_ACCOUNT = 'Discount';
    const GRIR_ACCOUNT = 'GR/IR';

    const ERROR_PREFIX = "Error while posting : ";
    const DN_SERVICE_POSTING_ACCOUNT = [
        self::COGS_ACCOUNT => self::DEBIT,
        self::STOCK_ACCOUNT => self::CREDIT,
    ];
    const SI_SERVICE_POSTING_ACCOUNT = [
        self::CUSTOMER_ACCOUNT => self::DEBIT,
        self::SALES_ACCOUNT => self::CREDIT,
        self::TAX_ACCOUNT => self::CREDIT,
        // self::DISCOUNT_ACCOUNT => self::DEBIT,
    ];
    const LOAN_POSTING_ACCOUNT = [
        self::Bank_ACCOUNT => self::DEBIT,
        self::ProcessFee_ACCOUNT => self::CREDIT,
    ];
    const DIS_POSTING_ACCOUNT = [
        self::Bank_ACCOUNT => self::CREDIT,
        self::Loan_Customer_Receivable_ACCOUNT => self::DEBIT,
    ];

    const LOAN_RECOVER_POSTING_ACCOUNT = [
        self::Bank_ACCOUNT => self::DEBIT,
        self::CUSTOMER_ACCOUNT => self::CREDIT,
        self::INTEREST_ACCOUNT => self::CREDIT,
    ];
    const PAYMENT_VOUCHER_RECEIPT_POSTING_ACCOUNT = [
        self::Bank_ACCOUNT => self::DEBIT,
        self::CUSTOMER_ACCOUNT => self::CREDIT,
    ];

    const LOAN_SETTLE_POSTING_ACCOUNT = [
        self::CUSTOMER_ACCOUNT => self::CREDIT,
        self::WRITE_OFF_ACCOUNT => self::DEBIT,
    ];


    const DN_CUM_INVOICE_SERVICE_POSTING_ACCOUNT = [
        self::CUSTOMER_ACCOUNT => self::DEBIT,
        self::SALES_ACCOUNT => self::CREDIT,
        self::TAX_ACCOUNT => self::CREDIT,
        self::DISCOUNT_ACCOUNT => self::DEBIT,
        self::COGS_ACCOUNT => self::DEBIT,
        self::STOCK_ACCOUNT => self::CREDIT,
    ];
    const SERVICE_POSTING_MAPPING = [
        ConstantHelper::DELIVERY_CHALLAN_SERVICE_ALIAS => self::DN_SERVICE_POSTING_ACCOUNT,
        ConstantHelper::SI_SERVICE_ALIAS => self::SI_SERVICE_POSTING_ACCOUNT,
        ConstantHelper::HOMELOAN => self::LOAN_POSTING_ACCOUNT,
        ConstantHelper::LOAN_DISBURSEMENT => self::DIS_POSTING_ACCOUNT,
        ConstantHelper::LOAN_RECOVERY => self::LOAN_RECOVER_POSTING_ACCOUNT,
        ConstantHelper::LOAN_SETTLEMENT => self::LOAN_SETTLE_POSTING_ACCOUNT,
        ConstantHelper::PAYMENTS_SERVICE_ALIAS => self::PAYMENT_VOUCHER_RECEIPT_POSTING_ACCOUNT,
        ConstantHelper::RECEIPTS_SERVICE_ALIAS => self::PAYMENT_VOUCHER_RECEIPT_POSTING_ACCOUNT,
        ConstantHelper::DELIVERY_CHALLAN_CUM_SI_SERVICE_ALIAS => self::DN_CUM_INVOICE_SERVICE_POSTING_ACCOUNT,
    ];

    public static function financeVoucherPosting(int $bookId, int $documentId, string $type, bool $onApproval=false) : array|bool
    {
        //Check Book
        $book = Book::find($bookId);
        if (!isset($book)) {
            return array(
                'status' => false,
                'message' => 'Book not found',
                'data' => []
            );
        }
        //Check Service
        $service = Service::find($book -> service_id);
        if (!isset($service)) {
            return array(
                'status' => false,
                'message' => 'Service not found',
                'data' => []
            );
        }
        $isFinanceVoucherDefined = ServiceParametersHelper::getFinancialServiceAlias($service -> alias);
        if (!isset($isFinanceVoucherDefined)) {
            return array(
                'status' => false,
                'message' => '',
                'data' => []
            );
        }
        //Check Posting parameters
        $financialPostParam = OrganizationBookParameter::where('book_id', $book->id)->where('parameter_name', ServiceParametersHelper::GL_POSTING_REQUIRED_PARAM)->first();
        if (!isset($financialPostParam)) {
            return array(
                'status' => false,
                'message' => 'GL Posting Parameter not specified',
                'data' => []
            );
        }
        $isPostingRequired = (($financialPostParam->parameter_value[0] ?? '') === 'yes' ? true : false);
        if (!$isPostingRequired) {
            return array(
                'status' => false,
                'message' => '',
                'data' => []
            );
        }
        //Check if this helper is called upon approval
        if ($onApproval) {
            $postOnApproveParam = OrganizationBookParameter::where('book_id', $book->id)->where('parameter_name', ServiceParametersHelper::POST_ON_ARROVE_PARAM)->first();
            if (!isset($postOnApproveParam)) {
                return array(
                    'status' => false,
                    'message' => 'Post on Approval Parameter not found',
                    'data' => []
                );
            }
            $isPostOnApprovalRequired = (($postOnApproveParam->parameter_value[0] ?? '') === "yes" ? true : false);
            if (!$isPostOnApprovalRequired) {
                return array(
                    'status' => false,
                    'message' => '',
                    'data' => []
                );
            }
        }
        //Call helpers according to service
        $serviceAlias = $service -> alias;
        if ($serviceAlias === ConstantHelper::SI_SERVICE_ALIAS) {
            $entries = self::invoiceVoucherDetails($documentId, $type);
            if (!$entries['status']) {
                return array(
                    'status' => false,
                    'message' => $entries['message'],
                    'data' => []
                );
            }
        } else if ($serviceAlias === ConstantHelper::DELIVERY_CHALLAN_SERVICE_ALIAS) {
            $entries = self::dnVoucherDetails($documentId, $type);
            if (!$entries['status']) {
                return array(
                    'status' => false,
                    'message' => $entries['message'],
                    'data' => []
                );
            }
        }  else if ($serviceAlias === ConstantHelper::MRN_SERVICE_ALIAS) {
            $entries = self::mrnVoucherDetails($documentId, $type);
            if (!$entries['status']) {
                return array(
                    'status' => false,
                    'message' => $entries['message'],
                    'data' => []
                );
            }
        }  else if ($serviceAlias === ConstantHelper::PB_SERVICE_ALIAS) {
            $entries = self::pbVoucherDetails($documentId, $type);
            if (!$entries['status']) {
                return array(
                    'status' => false,
                    'message' => $entries['message'],
                    'data' => []
                );
            }
        }  else if ($serviceAlias === ConstantHelper::EXPENSE_ADVISE_SERVICE_ALIAS) {
            $entries = self::expenseAdviseVoucherDetails($documentId, $type);
            if (!$entries['status']) {
                return array(
                    'status' => false,
                    'message' => $entries['message'],
                    'data' => []
                );
            }
        }  else if ($serviceAlias === ConstantHelper::PURCHASE_RETURN_SERVICE_ALIAS) {
            $entries = self::purchaseReturnVoucherDetails($documentId, $type);
            if (!$entries['status']) {
                return array(
                    'status' => false,
                    'message' => $entries['message'],
                    'data' => []
                );
            }
        }   else if ($serviceAlias === ConstantHelper::LEASE_INVOICE_SERVICE_ALIAS) {
            $entries = self::leaseInvoiceVoucherDetails($documentId, $type);
            if (!$entries['status']) {
                return array(
                    'status' => false,
                    'message' => $entries['message'],
                    'data' => []
                );
            }
        } else {
            $entries = array(
                'status' => false,
                'message' => 'No method found',
                'data' => []
            );
        }
        if ($type === 'post') {
            return self::postVoucher($entries['data']);
        } else {
            return $entries;
        }
    }

    public static function loanVoucherPosting(int $bookId, int $documentId, string $type,string $remarks) : array|bool
    {
        if($remarks == null)
        {
            $remarks = '';
        }
        //Check Book
        $book = Book::find($bookId);
        if (!isset($book)) {
            return array(
                'status' => false,
                'message' => 'Book not found',
                'data' => []
            );
        }
        //Check Service
        $service = Service::find($book -> service_id);
        if (!isset($service)) {
            return array(
                'status' => false,
                'message' => 'Service not found',
                'data' => []
            );
        }
        $isFinanceVoucherDefined = ServiceParametersHelper::getFinancialServiceAlias($service -> alias);
        if (!isset($isFinanceVoucherDefined)) {
            return array(
                'status' => false,
                'message' => '',
                'data' => []
            );
        }
        //Check Posting parameters
        $financialPostParam = OrganizationBookParameter::where('book_id', $book->id)->where('parameter_name', ServiceParametersHelper::GL_POSTING_REQUIRED_PARAM)->first();
        if (!isset($financialPostParam)) {
            return array(
                'status' => false,
                'message' => 'GL Posting Parameter not specified',
                'data' => []
            );
        }
        $isPostingRequired = (($financialPostParam->parameter_value[0] ?? '') === 'yes' ? true : false);
        if (!$isPostingRequired) {
            return array(
                'status' => false,
                'message' => '',
                'data' => []
            );
        }

        //Call helpers according to service
        $serviceAlias = $service -> alias;
        if ($serviceAlias === ConstantHelper::HOMELOAN) {
            $entries = self::loaninvoiceVoucherDetails($documentId, $type,$remarks);
            if (!$entries['status']) {
                return array(
                    'status' => false,
                    'message' => $entries['message'],
                    'data' => []
                );
            }
        } else {
            $entries = array(
                'status' => false,
                'message' => 'No method found',
                'data' => []
            );
        }
        if ($type === 'post')
        {
            $entries['data']['remarks'] = $remarks;
            return self::postVoucher($entries['data']);
        } else {
            return $entries;
        }
    }

    public static function disVoucherPosting(int $bookId, int $documentId, string $type) : array|bool
    {
        //Check Book
        $book = Book::find($bookId);
        if (!isset($book)) {
            return array(
                'status' => false,
                'message' => 'Book not found',
                'data' => []
            );
        }
        //Check Service
        $service = Service::find($book -> service_id);
        if (!isset($service)) {
            return array(
                'status' => false,
                'message' => 'Service not found',
                'data' => []
            );
        }
        $isFinanceVoucherDefined = ServiceParametersHelper::getFinancialServiceAlias($service -> alias);
        if (!isset($isFinanceVoucherDefined)) {
            return array(
                'status' => false,
                'message' => '',
                'data' => []
            );
        }
        //Check Posting parameters
        $financialPostParam = OrganizationBookParameter::where('book_id', $book->id)->where('parameter_name', ServiceParametersHelper::GL_POSTING_REQUIRED_PARAM)->first();
        if (!isset($financialPostParam)) {
            return array(
                'status' => false,
                'message' => 'GL Posting Parameter not specified',
                'data' => []
            );
        }
        $isPostingRequired = (($financialPostParam->parameter_value[0] ?? '') === 'yes' ? true : false);
        if (!$isPostingRequired) {
            return array(
                'status' => false,
                'message' => '',
                'data' => []
            );
        }

        //Call helpers according to service
        $serviceAlias = $service -> alias;
        if ($serviceAlias === ConstantHelper::LOAN_DISBURSEMENT) {
            $entries = self::disinvoiceVoucherDetails($documentId, $type);
            if (!$entries['status']) {
                return array(
                    'status' => false,
                    'message' => $entries['message'],
                    'data' => []
                );
            }
        } else {
            $entries = array(
                'status' => false,
                'message' => 'No method found',
                'data' => []
            );
        }
        if ($type === 'post')
        {
            return self::postVoucher($entries['data']);
        } else {
            return $entries;
        }
    }

    public static function loanRecoverVoucherPosting(int $bookId, int $documentId, string $type, string $remarks) : array|bool
    {
        //Check Book
        $book = Book::find($bookId);
        if (!isset($book)) {
            return array(
                'status' => false,
                'message' => 'Book not found',
                'data' => []
            );
        }
        //Check Service
        $service = Service::find($book -> service_id);
        if (!isset($service)) {
            return array(
                'status' => false,
                'message' => 'Service not found',
                'data' => []
            );
        }
        $isFinanceVoucherDefined = ServiceParametersHelper::getFinancialServiceAlias($service -> alias);
        if (!isset($isFinanceVoucherDefined)) {
            return array(
                'status' => false,
                'message' => '',
                'data' => []
            );
        }
        //Check Posting parameters
        $financialPostParam = OrganizationBookParameter::where('book_id', $book->id)->where('parameter_name', ServiceParametersHelper::GL_POSTING_REQUIRED_PARAM)->first();
        if (!isset($financialPostParam)) {
            return array(
                'status' => false,
                'message' => 'GL Posting Parameter not specified',
                'data' => []
            );
        }
        $isPostingRequired = (($financialPostParam->parameter_value[0] ?? '') === 'yes' ? true : false);
        if (!$isPostingRequired) {
            return array(
                'status' => false,
                'message' => '',
                'data' => []
            );
        }

        //Call helpers according to service
        $serviceAlias = $service -> alias;
        if ($serviceAlias === ConstantHelper::LOAN_RECOVERY) {
            $entries = self::loanRecoverInvoiceVoucherDetails($documentId,$remarks);
            if (!$entries['status']) {
                return array(
                    'status' => false,
                    'message' => $entries['message'],
                    'data' => []
                );
            }
        } else {
            $entries = array(
                'status' => false,
                'message' => 'No method found',
                'data' => []
            );
        }
        if ($type === 'post')
        {
            $entries['data']['remarks'] = $remarks;
            return self::postVoucher($entries['data']);
        } else {
            return $entries;
        }
    }
    public static function loanSettleVoucherPosting(int $bookId, int $documentId, string $type, string $remarks) : array|bool
    {
        //Check Book
        $book = Book::find($bookId);
        if (!isset($book)) {
            return array(
                'status' => false,
                'message' => 'Book not found',
                'data' => []
            );
        }
        //Check Service
        $service = Service::find($book -> service_id);
        if (!isset($service)) {
            return array(
                'status' => false,
                'message' => 'Service not found',
                'data' => []
            );
        }
        $isFinanceVoucherDefined = ServiceParametersHelper::getFinancialServiceAlias($service -> alias);
        if (!isset($isFinanceVoucherDefined)) {
            return array(
                'status' => false,
                'message' => '',
                'data' => []
            );
        }
        //Check Posting parameters
        $financialPostParam = OrganizationBookParameter::where('book_id', $book->id)->where('parameter_name', ServiceParametersHelper::GL_POSTING_REQUIRED_PARAM)->first();
        if (!isset($financialPostParam)) {
            return array(
                'status' => false,
                'message' => 'GL Posting Parameter not specified',
                'data' => []
            );
        }
        $isPostingRequired = (($financialPostParam->parameter_value[0] ?? '') === 'yes' ? true : false);
        if (!$isPostingRequired) {
            return array(
                'status' => false,
                'message' => '',
                'data' => []
            );
        }

        //Call helpers according to service
        $serviceAlias = $service -> alias;
        if ($serviceAlias === ConstantHelper::LOAN_SETTLEMENT) {
            $entries = self::loanSettleInvoiceVoucherDetails($documentId,$remarks);
            if (!$entries['status']) {
                return array(
                    'status' => false,
                    'message' => $entries['message'],
                    'data' => []
                );
            }
        } else {
            $entries = array(
                'status' => false,
                'message' => 'No method found',
                'data' => []
            );
        }
        if ($type === 'post')
        {
            $entries['data']['remarks'] = $remarks;
            return self::postVoucher($entries['data']);
        } else {
            return $entries;
        }
    }

    public static function postVoucher(array $details) : array
    {
            //Post Voucher
            $exitingVoucher = Voucher::where('reference_service', $details['voucher_header']['reference_service']) -> where('reference_doc_id', $details['voucher_header']['reference_doc_id']) -> first();
            if ($exitingVoucher) {
                return array(
                    'message' => 'Voucher already posted',
                    'status' => false
                );
            }

            $voucher = Voucher::create($details['voucher_header']);
            foreach ($details['voucher_details'] as &$voucherDetail) {
                $voucherDetail['voucher_id'] = $voucher -> id;
                ItemDetail::create($voucherDetail);
            }
            //Create log
            $userData = Helper::userCheck();

            $referenceModelName = isset(ConstantHelper::SERVICE_ALIAS_MODELS[$voucher -> reference_service]) ? ConstantHelper::SERVICE_ALIAS_MODELS[$voucher -> reference_service] : null;
            if ($referenceModelName) {
                $referenceModel = resolve("App\\Models\\" . $referenceModelName);
                $referenceDoc = $referenceModel::find($voucher -> reference_doc_id);
                if (isset($referenceDoc)) {
                    //Post the original document
                    $referenceDoc -> document_status = ConstantHelper::POSTED;
                    $referenceDoc -> save();
                    $docApproval = new DocumentApproval;
                    $docApproval->document_type = $voucher -> reference_service;
                    $docApproval->document_id = $voucher -> reference_doc_id;
                    $docApproval->document_name = $referenceModel::class;
                    $docApproval->approval_type =ConstantHelper::POSTED;
                    $docApproval->approval_date = now();
                    $docApproval->revision_number = $referenceDoc->revision_number ?? 0;
                    $docApproval->remarks = null;
                    $docApproval->user_id = $userData['user_id'];
                    $user_type = $userData['type'];
                    $docApproval->user_type = $user_type;
                    $docApproval->save();
                }
            }
            return array(
                'message' => 'Voucher posted',
                'status' => true
            );
    }

    public static function invoiceVoucherDetails(int $documentId, string $type) : array
    {
        $accountSetup = isset(self::SERVICE_POSTING_MAPPING[ConstantHelper::SI_SERVICE_ALIAS])
        ? self::SERVICE_POSTING_MAPPING[ConstantHelper::SI_SERVICE_ALIAS] : [];
        if (!isset($accountSetup) || count($accountSetup) == 0) {
            return array(
                'status' => false,
                'message' => 'Account Setup not found',
                'data' => []
            );
        }
        $document = ErpSaleInvoice::find($documentId);
        if (!isset($document)) {
            return array(
                'status' => false,
                'message' => 'Document not found',
                'data' => []
            );
        }
        //Make array according to setup
        $postingArray = array(
            self::CUSTOMER_ACCOUNT => [],
            self::DISCOUNT_ACCOUNT => [],
            self::SALES_ACCOUNT => [],
            self::TAX_ACCOUNT => [],
            self::EXPENSE_ACCOUNT => []
        );
        //Assign Credit and Debit amount for tally check
        $totalCreditAmount = 0;
        $totalDebitAmount = 0;
        //Customer Account initialize
        $customer = Customer::find($document -> customer_id);
        $customerLedgerId = $customer -> ledger_id;
        $customerLedgerGroupId = $customer -> ledger_group_id;
        $customerLedger = Ledger::find($customerLedgerId);
        $customerLedgerGroup = Group::find($customerLedgerGroupId);
        //Customer Ledger account not found
        if (!isset($customerLedger) || !isset($customerLedgerGroup)) {
            return array(
                'status' => false,
                'message' => 'Customer Ledger not setup',
                'data' => []
            );
        }
        $discountPostingParam = OrganizationBookParameter::where('book_id', $document -> book_id)
        -> where('parameter_name', ServiceParametersHelper::GL_SEPERATE_DISCOUNT_PARAM) -> first();
        if (isset($discountPostingParam)) {
            $discountSeperatePosting = $discountPostingParam -> parameter_value[0] === "yes" ? true : false;
        } else {
            $discountSeperatePosting = false;
        }
        //Status to check if all ledger entries were properly set
        $ledgerErrorStatus = null;
        foreach ($document -> items as $docItemKey => $docItem) {
            //Assign Item values
            $itemValue = $docItem -> rate * $docItem -> order_qty;
            $itemTotalDiscount = $docItem -> header_discount_amount + $docItem -> item_discount_amount;
            $itemValueAfterDiscount = $itemValue - $itemTotalDiscount;
            //SALES ACCOUNT
            $salesAccountLedgerDetails = AccountHelper::getLedgerGroupAndLedgerIdForSalesAccount($document -> organization_id, $document -> customer_id, $docItem -> item_id, $document -> book_id);
            $salesAccountLedgerId = is_a($salesAccountLedgerDetails, Collection::class) ? $salesAccountLedgerDetails -> first()['ledger_id'] : null;
            $salesAccountLedgerGroupId = is_a($salesAccountLedgerDetails, Collection::class) ? $salesAccountLedgerDetails-> first()['ledger_group'] : null;
            $salesAccountLedger = Ledger::find($salesAccountLedgerId);
            $salesAccountLedgerGroup = Group::find($salesAccountLedgerGroupId);
            //LEDGER NOT FOUND
            if (!isset($salesAccountLedger) || !isset($salesAccountLedgerGroup)) {
                $ledgerErrorStatus = 'Sales Account Ledger not setup';
                break;
            }
            $salesCreditAmount = $discountSeperatePosting ? $itemValue : $itemValueAfterDiscount;
            //Check for same ledger and group in SALES ACCOUNT
            $existingSalesLedger = array_filter($postingArray[self::SALES_ACCOUNT], function ($posting) use($salesAccountLedgerId, $salesAccountLedgerGroupId) {
                return $posting['ledger_id'] == $salesAccountLedgerId && $posting['ledger_group_id'] == $salesAccountLedgerGroupId;
            });
            //Ledger found
            if (count($existingSalesLedger) > 0) {
                $postingArray[self::SALES_ACCOUNT][0]['credit_amount'] +=  $salesCreditAmount;
            } else { //Assign a new ledger
                array_push($postingArray[self::SALES_ACCOUNT], [
                    'ledger_id' => $salesAccountLedgerId,
                    'ledger_group_id' => $salesAccountLedgerGroupId,
                    'ledger_code' => $salesAccountLedger ?-> code,
                    'ledger_name' => $salesAccountLedger ?-> name,
                    'ledger_group_code' => $salesAccountLedgerGroup ?-> name,
                    'credit_amount' => $salesCreditAmount,
                    'debit_amount' => 0
                ]);
            }
            //Check for same ledger and group in CUSTOMER ACCOUNT
            $existingcustomerLedger = array_filter($postingArray[self::CUSTOMER_ACCOUNT], function ($posting) use($customerLedgerId, $customerLedgerGroupId) {
                return $posting['ledger_id'] == $customerLedgerId && $posting['ledger_group_id'] === $customerLedgerGroupId;
            });
            //Ledger found
            if (count($existingcustomerLedger) > 0) {
                $postingArray[self::CUSTOMER_ACCOUNT][0]['debit_amount'] += $itemValueAfterDiscount;
            } else { //Assign a new ledger
                array_push($postingArray[self::CUSTOMER_ACCOUNT], [
                    'ledger_id' => $customerLedgerId,
                    'ledger_group_id' => $customerLedgerGroupId,
                    'ledger_code' => $customerLedger ?-> code,
                    'ledger_name' => $customerLedger ?-> name,
                    'ledger_group_code' => $customerLedgerGroup ?-> name,
                    'debit_amount' => $itemValueAfterDiscount,
                    'credit_amount' => 0
                ]);
            }
        }
        //TAXES ACCOUNT
        $taxes = ErpSaleInvoiceTed::where('sale_invoice_id', $document -> id) -> where('ted_type', "Tax") -> get();
        foreach ($taxes as $tax) {
            $taxDetail = TaxDetail::find($tax -> ted_id);
            $taxLedgerId = $taxDetail -> ledger_id ?? null; //MAKE IT DYNAMIC
            $taxLedgerGroupId = $taxDetail -> ledger_group_id ?? null; //MAKE IT DYNAMIC
            $taxLedger = Ledger::find($taxLedgerId);
            $taxLedgerGroup = Group::find($taxLedgerGroupId);
            if (!isset($taxLedger) || !isset($taxLedgerGroup)) {
                $ledgerErrorStatus = 'Tax Account Ledger not setup';
                break;
            }
            $existingTaxLedger = array_filter($postingArray[self::TAX_ACCOUNT], function ($posting) use($taxLedgerId, $taxLedgerGroupId) {
                return $posting['ledger_id'] == $taxLedgerId && $posting['ledger_group_id'] === $taxLedgerGroupId;
            });
            //Ledger found
            if (count($existingTaxLedger) > 0) {
                $postingArray[self::TAX_ACCOUNT][0]['credit_amount'] += $tax -> ted_amount;
            } else { //Assign a new ledger
                array_push($postingArray[self::TAX_ACCOUNT], [
                    'ledger_id' => $taxLedgerId,
                    'ledger_group_id' => $taxLedgerGroupId,
                    'ledger_code' => $taxLedger ?-> code,
                    'ledger_name' => $taxLedger ?-> name,
                    'ledger_group_code' => $taxLedgerGroup ?-> name,
                    'credit_amount' => $tax -> ted_amount,
                    'debit_amount' => 0,
                ]);
            }
            //Tax for CUSTOMER ACCOUNT
            $existingCustomerLedger = array_filter($postingArray[self::CUSTOMER_ACCOUNT], function ($posting) use($customerLedgerId, $customerLedgerGroupId) {
                return $posting['ledger_id'] == $customerLedgerId && $posting['ledger_group_id'] === $customerLedgerGroupId;
            });
            //Ledger found
            if (count($existingCustomerLedger) > 0) {
                $postingArray[self::CUSTOMER_ACCOUNT][0]['debit_amount'] += $tax -> ted_amount;
            } else { //Assign new ledger
                array_push($postingArray[self::CUSTOMER_ACCOUNT], [
                    'ledger_id' => $taxLedgerId,
                    'ledger_group_id' => $taxLedgerGroupId,
                    'ledger_code' => $taxLedger ?-> code,
                    'ledger_name' => $taxLedger ?-> name,
                    'ledger_group_code' => $taxLedgerGroup ?-> name,
                    'credit_amount' => 0,
                    'debit_amount' => $tax -> ted_amount,
                ]);
            }
        }
        //EXPENSES
        $expenses = ErpSaleInvoiceTed::where('sale_invoice_id', $document -> id) -> where('ted_type', "Expense") -> get();
        foreach ($expenses as $expense) {
            $expenseDetail = ExpenseMaster::find($expense -> ted_id);
            $expenseLedgerId = $expenseDetail ?-> expense_ledger_id; //MAKE IT DYNAMIC - 5
            $expenseLedgerGroupId = $expenseDetail ?-> expense_ledger_group_id; //MAKE IT DYNAMIC - 9
            $expenseLedger = Ledger::find($expenseLedgerId);
            $expenseLedgerGroup = Group::find($expenseLedgerGroupId);
            if (!isset($expenseLedger) || !isset($expenseLedgerGroup)) {
                $ledgerErrorStatus = 'Expense Account Ledger not setup';
                break;
            }
            $existingExpenseLedger = array_filter($postingArray[self::EXPENSE_ACCOUNT], function ($posting) use($expenseLedgerId, $expenseLedgerGroupId) {
                return $posting['ledger_id'] == $expenseLedgerId && $posting['ledger_group_id'] === $expenseLedgerGroupId;
            });
            //Ledger found
            if (count($existingExpenseLedger) > 0) {
                $postingArray[self::EXPENSE_ACCOUNT][0]['credit_amount'] += $expense -> ted_amount;
            } else { //Assign a new ledger
                array_push($postingArray[self::EXPENSE_ACCOUNT], [
                    'ledger_id' => $expenseLedgerId,
                    'ledger_group_id' => $expenseLedgerGroupId,
                    'ledger_code' => $expenseLedger ?-> code,
                    'ledger_name' => $expenseLedger ?-> name,
                    'ledger_group_code' => $expenseLedgerGroup ?-> name,
                    'credit_amount' => $expense -> ted_amount,
                    'debit_amount' => 0,
                ]);
            }
            //Expense for CUSTOMER ACCOUNT
            $existingCustomerLedger = array_filter($postingArray[self::CUSTOMER_ACCOUNT], function ($posting) use($customerLedgerId, $customerLedgerGroupId) {
                return $posting['ledger_id'] == $customerLedgerId && $posting['ledger_group_id'] === $customerLedgerGroupId;
            });
            //Ledger found
            if (count($existingCustomerLedger) > 0) {
                $postingArray[self::CUSTOMER_ACCOUNT][0]['debit_amount'] += $expense -> ted_amount;
            } else { //Assign new ledger
                array_push($postingArray[self::EXPENSE_ACCOUNT], [
                    'ledger_id' => $expenseLedgerId,
                    'ledger_group_id' => $expenseLedgerGroupId,
                    'ledger_code' => $expenseLedger ?-> code,
                    'ledger_name' => $expenseLedger ?-> name,
                    'ledger_group_code' => $expenseLedgerGroup ?-> name,
                    'credit_amount' => 0,
                    'debit_amount' => $expense -> ted_amount,
                ]);
            }
        }
        //Seperate posting of Discount
        if ($discountSeperatePosting) {
            $discounts = ErpSaleInvoiceTed::where('sale_invoice_id', $document -> id) -> where('ted_type', "Discount") -> get();
            foreach ($discounts as $discount) {
                $discountDetail = DiscountMaster::find($discount -> ted_id);
                $discountLedgerId = $discountDetail ?-> discount_ledger_id; //MAKE IT DYNAMIC
                $discountLedgerGroupId = $discountDetail ?-> discount_ledger_group_id; //MAKE IT DYNAMIC
                $discountLedger = Ledger::find($discountLedgerId);
                $discountLedgerGroup = Group::find($discountLedgerGroupId);
                if (!isset($discountLedger) || !isset($discountLedgerGroup)) {
                    $ledgerErrorStatus = 'Discount Account Ledger not setup';
                    break;
                }
                $existingDiscountLedger = array_filter($postingArray[self::DISCOUNT_ACCOUNT], function ($posting) use($discountLedgerId, $discountLedgerGroupId) {
                    return $posting['ledger_id'] == $discountLedgerId && $posting['ledger_group_id'] === $discountLedgerGroupId;
                });
                //Ledger found
                if (count($existingDiscountLedger) > 0) {
                    $postingArray[self::DISCOUNT_ACCOUNT][0]['debit_amount'] += $discount -> ted_amount;
                } else { //Assign a new ledger
                    array_push($postingArray[self::DISCOUNT_ACCOUNT], [
                        'ledger_id' => $discountLedgerId,
                        'ledger_group_id' => $discountLedgerGroupId,
                        'ledger_code' => $discountLedger ?-> code,
                        'ledger_name' => $discountLedger ?-> name,
                        'ledger_group_code' => $discountLedgerGroup ?-> name,
                        'debit_amount' => $discount -> ted_amount,
                        'credit_amount' => 0,
                    ]);
                }
            }
        }
        //Check if All Legders exists and posting is properly set
        if ($ledgerErrorStatus) {
            return array(
                'status' => false,
                'message' => $ledgerErrorStatus,
                'data' => []
            );
        }
        //Check debit and credit tally
        foreach ($postingArray as $postAccount) {
            foreach ($postAccount as $postingValue) {
                $totalCreditAmount += $postingValue['credit_amount'];
                $totalDebitAmount += $postingValue['debit_amount'];
            }
        }
        //Balance does not match
        if ($totalDebitAmount !== $totalCreditAmount) {
            return array(
                'status' => false,
                'message' => 'Credit Amount does not match Debit Amount',
                'data' => []
            );
        }
        //Get Header Details
        $book = Book::find($document -> book_id);
        $glPostingBookParam = OrganizationBookParameter::where('book_id', $book -> id) -> where('parameter_name', ServiceParametersHelper::GL_POSTING_SERIES_PARAM) -> first();
        if (isset($glPostingBookParam)) {
            $glPostingBookId = $glPostingBookParam->parameter_value[0];
        } else {
            return array(
                'status' => false,
                'message' => 'Financial Book Code is not specified',
                'data' => []
            );
        }
        $currency = Currency::find($document -> currency_id);
        $userData = Helper::userCheck();
        $voucherHeader = [
            'voucher_no' => $document->document_number,
            'document_date' => $document -> document_date,
            'book_id' => $glPostingBookId,
            'date' => $document -> document_date,
            'amount' => $totalCreditAmount,
            'currency_id' => $document -> currency_id,
            'currency_code' => $document -> currency_code,
            'org_currency_id' => $document -> org_currency_id,
            'org_currency_code' => $document -> org_currency_code,
            'org_currency_exg_rate' => $document -> org_currency_exg_rate,
            'comp_currency_id' => $document -> comp_currency_id,
            'comp_currency_code' => $document -> comp_currency_code,
            'comp_currency_exg_rate' => $document -> comp_currency_exg_rate,
            'group_currency_id' => $document -> group_currency_id,
            'group_currency_code' => $document -> group_currency_code,
            'group_currency_exg_rate' => $document -> group_currency_exg_rate,
            'reference_service' => $book ?-> service ?-> alias,
            'reference_doc_id' => $document -> id,
            'group_id' => $document -> group_id,
            'company_id' => $document -> company_id,
            'organization_id' => $document -> organization_id,
            'voucherable_type' => $userData['user_type'],
            'voucherable_id' => $userData['user_id'],
            'document_status' => ConstantHelper::APPROVED,
            'approvalLevel' => $document -> approval_level
       ];
       $voucherDetails = [];
       foreach ($postingArray as $entryType => $postDetails) {
        foreach ($postDetails as $post) {
            array_push($voucherDetails, [
                'ledger_id' => $post['ledger_id'],
                'ledger_parent_id' => $post['ledger_group_id'],
                'debit_amt' => $post['debit_amount'],
                'credit_amt' => $post['credit_amount'],
                'debit_amt_org' => $post['debit_amount'] * $voucherHeader['org_currency_exg_rate'],
                'credit_amt_org' => $post['credit_amount'] * $voucherHeader['org_currency_exg_rate'],
                'debit_amt_comp' => $post['debit_amount'] * $voucherHeader['comp_currency_exg_rate'],
                'credit_amt_comp' => $post['credit_amount'] * $voucherHeader['comp_currency_exg_rate'],
                'debit_amt_group' => $post['debit_amount'] * $voucherHeader['group_currency_exg_rate'],
                'credit_amt_group' => $post['credit_amount'] * $voucherHeader['group_currency_exg_rate'],
                'entry_type' => $entryType,
                // 'cost_center_id',
                // 'notes',
                // 'group_id' => $voucherHeader['group_id'],
                // 'company_id' => $voucherHeader['company_id'],
                // 'organization_id' => $voucherHeader['organization_id']
            ]);
        }
       }
        return array(
            'status' => true,
            'message' => 'Posting Details found',
            'data' => [
                'voucher_header' => $voucherHeader,
                'voucher_details' => $voucherDetails,
                'document_date' => $document -> document_date,
                'ledgers' => $postingArray,
                'total_debit' => $totalDebitAmount,
                'total_credit' => $totalCreditAmount,
                'book_code' => $book ?-> book_code,
                'document_number' => $document -> document_number,
                'currency_code' => $currency ?-> short_name
            ]
        );
    }
    public static function leaseInvoiceVoucherDetails(int $documentId, string $type) : array
    {
        $document = ErpSaleInvoice::find($documentId);
        if (!isset($document)) {
            return array(
                'status' => false,
                'message' => 'Document not found',
                'data' => []
            );
        }
        //Make array according to setup
        $postingArray = array(
            self::CUSTOMER_ACCOUNT => [],
            self::DISCOUNT_ACCOUNT => [],
            self::SALES_ACCOUNT => [],
            self::TAX_ACCOUNT => [],
            self::EXPENSE_ACCOUNT => []
        );
        //Assign Credit and Debit amount for tally check
        $totalCreditAmount = 0;
        $totalDebitAmount = 0;
        //Customer Account initialize
        $customer = Customer::find($document -> customer_id);
        $customerLedgerId = $customer -> ledger_id;
        $customerLedgerGroupId = $customer -> ledger_group_id;
        $customerLedger = Ledger::find($customerLedgerId);
        $customerLedgerGroup = Group::find($customerLedgerGroupId);
        //Customer Ledger account not found
        if (!isset($customerLedger) || !isset($customerLedgerGroup)) {
            return array(
                'status' => false,
                'message' => 'Customer Ledger not setup',
                'data' => []
            );
        }
        $discountPostingParam = OrganizationBookParameter::where('book_id', $document -> book_id)
        -> where('parameter_name', ServiceParametersHelper::GL_SEPERATE_DISCOUNT_PARAM) -> first();
        if (isset($discountPostingParam)) {
            $discountSeperatePosting = $discountPostingParam -> parameter_value[0] === "yes" ? true : false;
        } else {
            $discountSeperatePosting = false;
        }
        //Status to check if all ledger entries were properly set
        $ledgerErrorStatus = null;
        foreach ($document -> items as $docItemKey => $docItem) {
            //Assign Item values
            $itemValue = $docItem -> rate * $docItem -> order_qty;
            $itemTotalDiscount = $docItem -> header_discount_amount + $docItem -> item_discount_amount;
            $itemValueAfterDiscount = $itemValue - $itemTotalDiscount;
            //SALES ACCOUNT
            $landLeasePlot = LandLeasePlot::where('lease_id', $docItem -> land_lease_id) -> get() -> first();
            $landParcelId = $landLeasePlot ?-> land_parcel_id;
            $salesAccountLedgerDetails = AccountHelper::getLedgerGroupAndLedgerIdForLeaseRevenue($landParcelId, $docItem -> lease_item_type);
            $salesAccountLedgerId = $salesAccountLedgerDetails['ledger_id'];
            $salesAccountLedgerGroupId =$salesAccountLedgerDetails['ledger_group_id'];
            $salesAccountLedger = Ledger::find($salesAccountLedgerId);
            $salesAccountLedgerGroup = Group::find($salesAccountLedgerGroupId);
            //LEDGER NOT FOUND
            if (!isset($salesAccountLedger) || !isset($salesAccountLedgerGroup)) {
                $ledgerErrorStatus = 'Lease Revenue Ledger not setup';
                break;
            }
            $salesCreditAmount = $discountSeperatePosting ? $itemValue : $itemValueAfterDiscount;
            //Check for same ledger and group in SALES ACCOUNT
            $existingSalesLedger = array_filter($postingArray[self::SALES_ACCOUNT], function ($posting) use($salesAccountLedgerId, $salesAccountLedgerGroupId) {
                return $posting['ledger_id'] == $salesAccountLedgerId && $posting['ledger_group_id'] == $salesAccountLedgerGroupId;
            });
            //Ledger found
            if (count($existingSalesLedger) > 0) {
                $postingArray[self::SALES_ACCOUNT][0]['credit_amount'] +=  $salesCreditAmount;
            } else { //Assign a new ledger
                array_push($postingArray[self::SALES_ACCOUNT], [
                    'ledger_id' => $salesAccountLedgerId,
                    'ledger_group_id' => $salesAccountLedgerGroupId,
                    'ledger_code' => $salesAccountLedger ?-> code,
                    'ledger_name' => $salesAccountLedger ?-> name,
                    'ledger_group_code' => $salesAccountLedgerGroup ?-> name,
                    'credit_amount' => $salesCreditAmount,
                    'debit_amount' => 0
                ]);
            }
            //Check for same ledger and group in CUSTOMER ACCOUNT
            $existingcustomerLedger = array_filter($postingArray[self::CUSTOMER_ACCOUNT], function ($posting) use($customerLedgerId, $customerLedgerGroupId) {
                return $posting['ledger_id'] == $customerLedgerId && $posting['ledger_group_id'] === $customerLedgerGroupId;
            });
            //Ledger found
            if (count($existingcustomerLedger) > 0) {
                $postingArray[self::CUSTOMER_ACCOUNT][0]['debit_amount'] += $itemValueAfterDiscount;
            } else { //Assign a new ledger
                array_push($postingArray[self::CUSTOMER_ACCOUNT], [
                    'ledger_id' => $customerLedgerId,
                    'ledger_group_id' => $customerLedgerGroupId,
                    'ledger_code' => $customerLedger ?-> code,
                    'ledger_name' => $customerLedger ?-> name,
                    'ledger_group_code' => $customerLedgerGroup ?-> name,
                    'debit_amount' => $itemValueAfterDiscount,
                    'credit_amount' => 0
                ]);
            }
        }
        //TAXES ACCOUNT
        $taxes = ErpSaleInvoiceTed::where('sale_invoice_id', $document -> id) -> where('ted_type', "Tax") -> get();
        foreach ($taxes as $tax) {
            $taxDetail = TaxDetail::find($tax -> ted_id);
            $taxLedgerId = $taxDetail -> ledger_id ?? null; //MAKE IT DYNAMIC
            $taxLedgerGroupId = $taxDetail -> ledger_group_id ?? null; //MAKE IT DYNAMIC
            $taxLedger = Ledger::find($taxLedgerId);
            $taxLedgerGroup = Group::find($taxLedgerGroupId);
            if (!isset($taxLedger) || !isset($taxLedgerGroup)) {
                $ledgerErrorStatus = 'Tax Account Ledger not setup';
                break;
            }
            $existingTaxLedger = array_filter($postingArray[self::TAX_ACCOUNT], function ($posting) use($taxLedgerId, $taxLedgerGroupId) {
                return $posting['ledger_id'] == $taxLedgerId && $posting['ledger_group_id'] === $taxLedgerGroupId;
            });
            //Ledger found
            if (count($existingTaxLedger) > 0) {
                $postingArray[self::TAX_ACCOUNT][0]['credit_amount'] += $tax -> ted_amount;
            } else { //Assign a new ledger
                array_push($postingArray[self::TAX_ACCOUNT], [
                    'ledger_id' => $taxLedgerId,
                    'ledger_group_id' => $taxLedgerGroupId,
                    'ledger_code' => $taxLedger ?-> code,
                    'ledger_name' => $taxLedger ?-> name,
                    'ledger_group_code' => $taxLedgerGroup ?-> name,
                    'credit_amount' => $tax -> ted_amount,
                    'debit_amount' => 0,
                ]);
            }
            //Tax for CUSTOMER ACCOUNT
            $existingCustomerLedger = array_filter($postingArray[self::CUSTOMER_ACCOUNT], function ($posting) use($customerLedgerId, $customerLedgerGroupId) {
                return $posting['ledger_id'] == $customerLedgerId && $posting['ledger_group_id'] === $customerLedgerGroupId;
            });
            //Ledger found
            if (count($existingCustomerLedger) > 0) {
                $postingArray[self::CUSTOMER_ACCOUNT][0]['debit_amount'] += $tax -> ted_amount;
            } else { //Assign new ledger
                array_push($postingArray[self::CUSTOMER_ACCOUNT], [
                    'ledger_id' => $taxLedgerId,
                    'ledger_group_id' => $taxLedgerGroupId,
                    'ledger_code' => $taxLedger ?-> code,
                    'ledger_name' => $taxLedger ?-> name,
                    'ledger_group_code' => $taxLedgerGroup ?-> name,
                    'credit_amount' => 0,
                    'debit_amount' => $tax -> ted_amount,
                ]);
            }
        }
        //EXPENSES
        $expenses = ErpSaleInvoiceTed::where('sale_invoice_id', $document -> id) -> where('ted_type', "Expense") -> get();
        foreach ($expenses as $expense) {
            $expenseDetail = ExpenseMaster::find($expense -> ted_id);
            $expenseLedgerId = $expenseDetail ?-> expense_ledger_id; //MAKE IT DYNAMIC - 5
            $expenseLedgerGroupId = $expenseDetail ?-> expense_ledger_group_id; //MAKE IT DYNAMIC - 9
            $expenseLedger = Ledger::find($expenseLedgerId);
            $expenseLedgerGroup = Group::find($expenseLedgerGroupId);
            if (!isset($expenseLedger) || !isset($expenseLedgerGroup)) {
                $ledgerErrorStatus = 'Expense Account Ledger not setup';
                break;
            }
            $existingExpenseLedger = array_filter($postingArray[self::EXPENSE_ACCOUNT], function ($posting) use($expenseLedgerId, $expenseLedgerGroupId) {
                return $posting['ledger_id'] == $expenseLedgerId && $posting['ledger_group_id'] === $expenseLedgerGroupId;
            });
            //Ledger found
            if (count($existingExpenseLedger) > 0) {
                $postingArray[self::EXPENSE_ACCOUNT][0]['credit_amount'] += $expense -> ted_amount;
            } else { //Assign a new ledger
                array_push($postingArray[self::EXPENSE_ACCOUNT], [
                    'ledger_id' => $expenseLedgerId,
                    'ledger_group_id' => $expenseLedgerGroupId,
                    'ledger_code' => $expenseLedger ?-> code,
                    'ledger_name' => $expenseLedger ?-> name,
                    'ledger_group_code' => $expenseLedgerGroup ?-> name,
                    'credit_amount' => $expense -> ted_amount,
                    'debit_amount' => 0,
                ]);
            }
            //Expense for CUSTOMER ACCOUNT
            $existingCustomerLedger = array_filter($postingArray[self::CUSTOMER_ACCOUNT], function ($posting) use($customerLedgerId, $customerLedgerGroupId) {
                return $posting['ledger_id'] == $customerLedgerId && $posting['ledger_group_id'] === $customerLedgerGroupId;
            });
            //Ledger found
            if (count($existingCustomerLedger) > 0) {
                $postingArray[self::CUSTOMER_ACCOUNT][0]['debit_amount'] += $expense -> ted_amount;
            } else { //Assign new ledger
                array_push($postingArray[self::EXPENSE_ACCOUNT], [
                    'ledger_id' => $expenseLedgerId,
                    'ledger_group_id' => $expenseLedgerGroupId,
                    'ledger_code' => $expenseLedger ?-> code,
                    'ledger_name' => $expenseLedger ?-> name,
                    'ledger_group_code' => $expenseLedgerGroup ?-> name,
                    'credit_amount' => 0,
                    'debit_amount' => $expense -> ted_amount,
                ]);
            }
        }
        //Seperate posting of Discount
        if ($discountSeperatePosting) {
            $discounts = ErpSaleInvoiceTed::where('sale_invoice_id', $document -> id) -> where('ted_type', "Discount") -> get();
            foreach ($discounts as $discount) {
                $discountDetail = DiscountMaster::find($discount -> ted_id);
                $discountLedgerId = $discountDetail ?-> discount_ledger_id; //MAKE IT DYNAMIC
                $discountLedgerGroupId = $discountDetail ?-> discount_ledger_group_id; //MAKE IT DYNAMIC
                $discountLedger = Ledger::find($discountLedgerId);
                $discountLedgerGroup = Group::find($discountLedgerGroupId);
                if (!isset($discountLedger) || !isset($discountLedgerGroup)) {
                    $ledgerErrorStatus = 'Discount Account Ledger not setup';
                    break;
                }
                $existingDiscountLedger = array_filter($postingArray[self::DISCOUNT_ACCOUNT], function ($posting) use($discountLedgerId, $discountLedgerGroupId) {
                    return $posting['ledger_id'] == $discountLedgerId && $posting['ledger_group_id'] === $discountLedgerGroupId;
                });
                //Ledger found
                if (count($existingDiscountLedger) > 0) {
                    $postingArray[self::DISCOUNT_ACCOUNT][0]['debit_amount'] += $discount -> ted_amount;
                } else { //Assign a new ledger
                    array_push($postingArray[self::DISCOUNT_ACCOUNT], [
                        'ledger_id' => $discountLedgerId,
                        'ledger_group_id' => $discountLedgerGroupId,
                        'ledger_code' => $discountLedger ?-> code,
                        'ledger_name' => $discountLedger ?-> name,
                        'ledger_group_code' => $discountLedgerGroup ?-> name,
                        'debit_amount' => $discount -> ted_amount,
                        'credit_amount' => 0,
                    ]);
                }
            }
        }
        //Check if All Legders exists and posting is properly set
        if ($ledgerErrorStatus) {
            return array(
                'status' => false,
                'message' => $ledgerErrorStatus,
                'data' => []
            );
        }
        //Check debit and credit tally
        foreach ($postingArray as $postAccount) {
            foreach ($postAccount as $postingValue) {
                $totalCreditAmount += $postingValue['credit_amount'];
                $totalDebitAmount += $postingValue['debit_amount'];
            }
        }
        //Balance does not match
        if ($totalDebitAmount !== $totalCreditAmount) {
            return array(
                'status' => false,
                'message' => 'Credit Amount does not match Debit Amount',
                'data' => []
            );
        }
        //Get Header Details
        $book = Book::find($document -> book_id);
        $glPostingBookParam = OrganizationBookParameter::where('book_id', $book -> id) -> where('parameter_name', ServiceParametersHelper::GL_POSTING_SERIES_PARAM) -> first();
        if (isset($glPostingBookParam)) {
            $glPostingBookId = $glPostingBookParam->parameter_value[0];
        } else {
            return array(
                'status' => false,
                'message' => 'Financial Book Code is not specified',
                'data' => []
            );
        }
        $currency = Currency::find($document -> currency_id);
        $userData = Helper::userCheck();
        $voucherHeader = [
            'voucher_no' => $document->document_number,
            'document_date' => $document -> document_date,
            'book_id' => $glPostingBookId,
            'date' => $document -> document_date,
            'amount' => $totalCreditAmount,
            'currency_id' => $document -> currency_id,
            'currency_code' => $document -> currency_code,
            'org_currency_id' => $document -> org_currency_id,
            'org_currency_code' => $document -> org_currency_code,
            'org_currency_exg_rate' => $document -> org_currency_exg_rate,
            'comp_currency_id' => $document -> comp_currency_id,
            'comp_currency_code' => $document -> comp_currency_code,
            'comp_currency_exg_rate' => $document -> comp_currency_exg_rate,
            'group_currency_id' => $document -> group_currency_id,
            'group_currency_code' => $document -> group_currency_code,
            'group_currency_exg_rate' => $document -> group_currency_exg_rate,
            'reference_service' => $book ?-> service ?-> alias,
            'reference_doc_id' => $document -> id,
            'group_id' => $document -> group_id,
            'company_id' => $document -> company_id,
            'organization_id' => $document -> organization_id,
            'voucherable_type' => $userData['user_type'],
            'voucherable_id' => $userData['user_id'],
            'document_status' => ConstantHelper::APPROVED,
            'approvalLevel' => $document -> approval_level
       ];
       $voucherDetails = [];
       foreach ($postingArray as $entryType => $postDetails) {
        foreach ($postDetails as $post) {
            array_push($voucherDetails, [
                'ledger_id' => $post['ledger_id'],
                'ledger_parent_id' => $post['ledger_group_id'],
                'debit_amt' => $post['debit_amount'],
                'credit_amt' => $post['credit_amount'],
                'debit_amt_org' => $post['debit_amount'] * $voucherHeader['org_currency_exg_rate'],
                'credit_amt_org' => $post['credit_amount'] * $voucherHeader['org_currency_exg_rate'],
                'debit_amt_comp' => $post['debit_amount'] * $voucherHeader['comp_currency_exg_rate'],
                'credit_amt_comp' => $post['credit_amount'] * $voucherHeader['comp_currency_exg_rate'],
                'debit_amt_group' => $post['debit_amount'] * $voucherHeader['group_currency_exg_rate'],
                'credit_amt_group' => $post['credit_amount'] * $voucherHeader['group_currency_exg_rate'],
                'entry_type' => $entryType,
                // 'cost_center_id',
                // 'notes',
                // 'group_id' => $voucherHeader['group_id'],
                // 'company_id' => $voucherHeader['company_id'],
                // 'organization_id' => $voucherHeader['organization_id']
            ]);
        }
       }
        return array(
            'status' => true,
            'message' => 'Posting Details found',
            'data' => [
                'voucher_header' => $voucherHeader,
                'voucher_details' => $voucherDetails,
                'document_date' => $document -> document_date,
                'ledgers' => $postingArray,
                'total_debit' => $totalDebitAmount,
                'total_credit' => $totalCreditAmount,
                'book_code' => $book ?-> book_code,
                'document_number' => $document -> document_number,
                'currency_code' => $currency ?-> short_name
            ]
        );
    }

    public static function loaninvoiceVoucherDetails(int $documentId, string $type, string $remarks) : array
    {
        $accountSetup = isset(self::SERVICE_POSTING_MAPPING[ConstantHelper::HOMELOAN])
        ? self::SERVICE_POSTING_MAPPING[ConstantHelper::HOMELOAN] : [];
        if (!isset($accountSetup) || count($accountSetup) == 0) {
            return array(
                'status' => false,
                'message' => 'Account Setup not found',
                'data' => []
            );
        }
        $document = HomeLoan::find($documentId);
        if (!isset($document)) {
            return array(
                'status' => false,
                'message' => 'Document not found',
                'data' => []
            );
        }
        //Make array according to setup
        $postingArray = array(
            self::Bank_ACCOUNT => [],
            self::ProcessFee_ACCOUNT => [],
        );
        //Assign Credit and Debit amount for tally check
        $totalCreditAmount = 0;
        $totalDebitAmount = 0;
        //Customer Account initialize
        $financesetup = LoanFinancialAccount::first();
        if(!empty($financesetup))
        {
            $FinanceLedgerId = $financesetup->pro_ledger_id;
            $FinanceLedgerGroupId = $financesetup->pro_ledger_group_id;
            $FinanceLedger = Ledger::find($FinanceLedgerId);
            $FinanceLedgerGroup = Group::find($FinanceLedgerGroupId);
        }

        //Customer Ledger account not found
        if (!isset($FinanceLedger) || !isset($FinanceLedgerGroup)) {
            return array(
                'status' => false,
                'message' => 'Finanical Setup Ledger not setup',
                'data' => []
            );
        }

        //Status to check if all ledger entries were properly set
        $ledgerErrorStatus = null;

        $loandata = LoanProcessFee::where('loan_application_id',$document->id)->first();
        $loanLedger = Ledger::find($loandata->ledger_id);
        $loanLedgerGroup = Group::find($loandata->ledger_group_id);






            array_push($postingArray[self::Bank_ACCOUNT], [
                'ledger_id' => $loandata->ledger_id,
                'ledger_group_id' => $loandata->ledger_group_id,
                'ledger_code' => $loanLedger ?-> code,
                'ledger_name' => $loanLedger ?-> name,
                'ledger_group_code' => $loanLedgerGroup ?-> name,
                'debit_amount' => $loandata->fee_amount,
                'credit_amount' => 0
            ]);

            array_push($postingArray[self::ProcessFee_ACCOUNT], [
                'ledger_id' => $FinanceLedgerId,
                'ledger_group_id' => $FinanceLedgerGroupId,
                'ledger_code' => $FinanceLedger ?-> code,
                'ledger_name' => $FinanceLedger ?-> name,
                'ledger_group_code' => $FinanceLedgerGroup?-> name,
                'credit_amount' => $loandata->fee_amount,
                'debit_amount' => 0
            ]);


        //Check if All Legders exists and posting is properly set
        if ($ledgerErrorStatus) {
            return array(
                'status' => false,
                'message' => $ledgerErrorStatus,
                'data' => []
            );
        }
        //Check debit and credit tally
        foreach ($postingArray as $postAccount) {
            foreach ($postAccount as $postingValue) {

                $totalDebitAmount += $postingValue['debit_amount'];
                $totalCreditAmount += $postingValue['credit_amount'];
            }
        }
        //Balance does not match
        if ($totalDebitAmount !== $totalCreditAmount) {
            return array(
                'status' => false,
                'message' => 'Credit Amount does not match Debit Amount',
                'data' => []
            );
        }
        //Get Header Details
        $book = Book::find($document->book_id);
        $glPostingBookParam = OrganizationBookParameter::where('book_id', $book -> id) -> where('parameter_name', ServiceParametersHelper::GL_POSTING_SERIES_PARAM) -> first();
        if (isset($glPostingBookParam)) {
            $glPostingBookId = $glPostingBookParam -> parameter_value[0];
        } else {
            return array(
                'status' => false,
                'message' => 'Financial Book Code is not specified',
                'data' => []
            );
        }

        $currdata = OrganizationCompany::where('id',$document->company_id)->first();
        $excurrdata = CurrencyExchange::where('from_currency_id',$currdata->currency_id)->first();
        $currency = Currency::find($currdata->currency_id);

        $userData = Helper::userCheck();

        $booksdata = Book::where('book_name','JOURNAL_VOUCHER')->first();
        $numberPatternData = Helper::generateDocumentNumberNew($booksdata->id, $document->document_date);
        if (!isset($numberPatternData)) {
            return response()->json([
                'message' => "Invalid Book",
                'error' => "",
            ], 422);
        }
        $document_number = $numberPatternData['document_number'] ? $numberPatternData['document_number'] : null;

        $voucherHeader = [
            'voucher_no' => $document_number,
            'voucher_name' => $booksdata->book_code,
            'doc_prefix' => $numberPatternData['prefix'],
            'doc_suffix' => $numberPatternData['suffix'],
            'doc_no' => $numberPatternData['doc_no'],
            'doc_reset_pattern' => $numberPatternData['reset_pattern'],
            'document_date' => $document->document_date,
            'book_id' => $booksdata->id,
            'book_type_id' => $booksdata->org_service_id,
            'date' => $document->document_date,
            'amount' => $totalCreditAmount,
            'currency_id' => $currdata->currency_id,
            'currency_code' => $currdata->currency_code,
            'org_currency_id' => $currdata->currency_id,
            'org_currency_code' => $currdata->currency_code,
            'org_currency_exg_rate' => $excurrdata->exchange_rate,
            'comp_currency_id' => $currdata->currency_id, // Missing comma added here
            'comp_currency_code' => $currdata->currency_code,
            'comp_currency_exg_rate' => $excurrdata->exchange_rate,
            'group_currency_id' => $currdata->currency_id,
            'group_currency_code' => $currdata->currency_code,
            'group_currency_exg_rate' => $excurrdata->exchange_rate,
            'reference_service' => $book?->service?->alias,
            'reference_doc_id' => $document->id,
            'group_id' => $document->group_id,
            'company_id' => $document->company_id,
            'organization_id' => $document->organization_id,
            'voucherable_type' => $userData['user_type'],
            'voucherable_id' => $userData['user_id'],
            'approvalStatus' => ConstantHelper::APPROVED,
            'document_status' => ConstantHelper::APPROVED,
            'approvalLevel' => $document->approval_level,
            'remarks'=>$remarks,
        ];

       $voucherDetails = [];
       foreach ($postingArray as $entryType => $postDetails) {
        foreach ($postDetails as $post) {
            array_push($voucherDetails, [
                'ledger_id' => $post['ledger_id'],
                'group_id' => $document->group_id,
                'company_id' => $document->company_id,
                'organization_id'=>$document->organization_id,
                'ledger_parent_id' => $post['ledger_group_id'],
                'debit_amt' => $post['debit_amount'],
                'credit_amt' => $post['credit_amount'],
                'debit_amt_org' => $post['debit_amount'] * $voucherHeader['org_currency_exg_rate'],
                'credit_amt_org' => $post['credit_amount'] * $voucherHeader['org_currency_exg_rate'],
                'debit_amt_comp' => $post['debit_amount'] * $voucherHeader['comp_currency_exg_rate'],
                'credit_amt_comp' => $post['credit_amount'] * $voucherHeader['comp_currency_exg_rate'],
                'debit_amt_group' => $post['debit_amount'] * $voucherHeader['group_currency_exg_rate'],
                'credit_amt_group' => $post['credit_amount'] * $voucherHeader['group_currency_exg_rate'],
                'entry_type' => $entryType,
            ]);
        }
       }
        return array(
            'status' => true,
            'message' => 'Posting Details found',
            'data' => [
                'voucher_header' => $voucherHeader,
                'voucher_details' => $voucherDetails,
                'document_date' => $document->created_at,
                'ledgers' => $postingArray,
                'total_debit' => $totalDebitAmount,
                'total_credit' => $totalCreditAmount,
                'book_code' => $booksdata ?-> book_code,
                'document_number' =>$document_number,
                'currency_code' => $currency ?-> short_name
            ]
        );
    }

    public static function disinvoiceVoucherDetails(int $documentId, string $type) : array
    {
        $accountSetup = isset(self::SERVICE_POSTING_MAPPING[ConstantHelper::LOAN_DISBURSEMENT])
        ? self::SERVICE_POSTING_MAPPING[ConstantHelper::LOAN_DISBURSEMENT] : [];
        if (!isset($accountSetup) || count($accountSetup) == 0) {
            return array(
                'status' => false,
                'message' => 'Account Setup not found',
                'data' => []
            );
        }
        $document = LoanDisbursement::find($documentId);
        if (!isset($document)) {
            return array(
                'status' => false,
                'message' => 'Document not found',
                'data' => []
            );
        }
        //Make array according to setup
        $postingArray = array(
            self::Bank_ACCOUNT => [],
            self::Loan_Customer_Receivable_ACCOUNT => [],
        );
        //Assign Credit and Debit amount for tally check
        $totalCreditAmount = 0;
        $totalDebitAmount = 0;
        //Customer Account initialize
        $financesetup = LoanFinancialAccount::first();
        if(!empty($financesetup))
        {
            $FinanceLedgerId = $financesetup->dis_ledger_id;
            $FinanceLedgerGroupId = $financesetup->dis_ledger_group_id;
            $FinanceLedger = Ledger::find($FinanceLedgerId);
            $FinanceLedgerGroup = Group::find($FinanceLedgerGroupId);
        }

        //Customer Ledger account not found
        if (!isset($FinanceLedger) || !isset($FinanceLedgerGroup)) {
            return array(
                'status' => false,
                'message' => 'Finanical Setup Ledger not setup',
                'data' => []
            );
        }

        //Status to check if all ledger entries were properly set
        $ledgerErrorStatus = null;

        $loandata = HomeLoan::where('id',$document->home_loan_id)->first();
        if(!empty($loandata))
        {
            $loanLedger = Ledger::find($loandata->cus_receivable_ledgerid);
            $loanLedgerGroup = Group::find($loandata->cus_receivable_ledgergroup);
        }

        if (!isset($loanLedger) || !isset($loanLedgerGroup)) {
            return array(
                'status' => false,
                'message' => 'Loan Cus Rec Ledger not setup',
                'data' => []
            );
        }


            array_push($postingArray[self::Bank_ACCOUNT], [
                'ledger_id' =>$loandata->cus_receivable_ledgerid,
                'ledger_group_id' => $loandata->cus_receivable_ledgergroup,
                'ledger_code' => $loanLedger ?-> code,
                'ledger_name' => $loanLedger ?-> name,
                'ledger_group_code' => $loanLedgerGroup ?-> name,
                'debit_amount' => $document->actual_dis,
                'credit_amount' => 0
            ]);

            array_push($postingArray[self::Loan_Customer_Receivable_ACCOUNT], [
                'ledger_id' => $FinanceLedgerId,
                'ledger_group_id' => $FinanceLedgerGroupId,
                'ledger_code' => $FinanceLedger ?-> code,
                'ledger_name' => $FinanceLedger ?-> name,
                'ledger_group_code' => $FinanceLedgerGroup?-> name,
                'credit_amount' => $document->actual_dis,
                'debit_amount' => 0
            ]);




        //Check if All Legders exists and posting is properly set
        if ($ledgerErrorStatus) {
            return array(
                'status' => false,
                'message' => $ledgerErrorStatus,
                'data' => []
            );
        }
        //Check debit and credit tally
        foreach ($postingArray as $postAccount) {
            foreach ($postAccount as $postingValue) {

                $totalDebitAmount += $postingValue['debit_amount'];
                $totalCreditAmount += $postingValue['credit_amount'];
            }
        }
        //Balance does not match
        if ($totalDebitAmount !== $totalCreditAmount) {
            return array(
                'status' => false,
                'message' => 'Credit Amount does not match Debit Amount',
                'data' => []
            );
        }
        //Get Header Details
        $book = Book::find($document->book_id);
        $glPostingBookParam = OrganizationBookParameter::where('book_id', $book -> id) -> where('parameter_name', ServiceParametersHelper::GL_POSTING_SERIES_PARAM) -> first();
        if (isset($glPostingBookParam)) {
            $glPostingBookId = $glPostingBookParam -> parameter_value[0];
        } else {
            return array(
                'status' => false,
                'message' => 'Financial Book Code is not specified',
                'data' => []
            );
        }

        $currdata = OrganizationCompany::where('id',$document->company_id)->first();
        $excurrdata = CurrencyExchange::where('from_currency_id',$currdata->currency_id)->first();
        $currency = Currency::find($currdata->currency_id);

        $userData = Helper::userCheck();

        $booksdata = Book::where('book_name','JOURNAL_VOUCHER')->first();
        $numberPatternData = Helper::generateDocumentNumberNew($booksdata->id, $document->document_date);
        if (!isset($numberPatternData)) {
            return response()->json([
                'message' => "Invalid Book",
                'error' => "",
            ], 422);
        }
        $document_number = $numberPatternData['document_number'] ? $numberPatternData['document_number'] : null;

        $voucherHeader = [
            'voucher_no' => $document_number,
            'voucher_name' => $booksdata->book_code,
            'doc_prefix' => $numberPatternData['prefix'],
            'doc_suffix' => $numberPatternData['suffix'],
            'doc_no' => $numberPatternData['doc_no'],
            'doc_reset_pattern' => $numberPatternData['reset_pattern'],
            'document_date' => $document->document_date,
            'book_id' => $booksdata->id,
            'book_type_id' => $booksdata->org_service_id,
            'date' => $document->document_date,
            'amount' => $totalCreditAmount,
            'currency_id' => $currdata->currency_id,
            'currency_code' => $currdata->currency_code,
            'org_currency_id' => $currdata->currency_id,
            'org_currency_code' => $currdata->currency_code,
            'org_currency_exg_rate' => $excurrdata->exchange_rate,
            'comp_currency_id' => $currdata->currency_id, // Missing comma added here
            'comp_currency_code' => $currdata->currency_code,
            'comp_currency_exg_rate' => $excurrdata->exchange_rate,
            'group_currency_id' => $currdata->currency_id,
            'group_currency_code' => $currdata->currency_code,
            'group_currency_exg_rate' => $excurrdata->exchange_rate,
            'reference_service' => $book?->service?->alias,
            'reference_doc_id' => $document->id,
            'group_id' => $document->group_id,
            'company_id' => $document->company_id,
            'organization_id' => $document->organization_id,
            'voucherable_type' => $userData['user_type'],
            'voucherable_id' => $userData['user_id'],
            'approvalStatus' => ConstantHelper::APPROVED,
            'document_status' => ConstantHelper::APPROVED,
            'approvalLevel' => $document->approval_level,
            'remarks'=>$document->dis_remarks,
        ];

       $voucherDetails = [];
       foreach ($postingArray as $entryType => $postDetails) {
        foreach ($postDetails as $post) {
            array_push($voucherDetails, [
                'ledger_id' => $post['ledger_id'],
                'group_id' => $document->group_id,
                'company_id' => $document->company_id,
                'organization_id'=>$document->organization_id,
                'ledger_parent_id' => $post['ledger_group_id'],
                'debit_amt' => $post['debit_amount'],
                'credit_amt' => $post['credit_amount'],
                'debit_amt_org' => $post['debit_amount'] * $voucherHeader['org_currency_exg_rate'],
                'credit_amt_org' => $post['credit_amount'] * $voucherHeader['org_currency_exg_rate'],
                'debit_amt_comp' => $post['debit_amount'] * $voucherHeader['comp_currency_exg_rate'],
                'credit_amt_comp' => $post['credit_amount'] * $voucherHeader['comp_currency_exg_rate'],
                'debit_amt_group' => $post['debit_amount'] * $voucherHeader['group_currency_exg_rate'],
                'credit_amt_group' => $post['credit_amount'] * $voucherHeader['group_currency_exg_rate'],
                'entry_type' => $entryType,
            ]);
        }
       }
        return array(
            'status' => true,
            'message' => 'Posting Details found',
            'data' => [
                'voucher_header' => $voucherHeader,
                'voucher_details' => $voucherDetails,
                'document_date' => $document->created_at,
                'ledgers' => $postingArray,
                'total_debit' => $totalDebitAmount,
                'total_credit' => $totalCreditAmount,
                'book_code' => $booksdata ?-> book_code,
                'document_number' =>$document_number,
                'currency_code' => $currency ?-> short_name
            ]
        );
    }

    public static function loanRecoverInvoiceVoucherDetails(int $documentId, string $remarks) : array
    {
        $accountSetup = isset(self::SERVICE_POSTING_MAPPING[ConstantHelper::LOAN_RECOVERY])
        ? self::SERVICE_POSTING_MAPPING[ConstantHelper::LOAN_RECOVERY] : [];
        if (!isset($accountSetup) || count($accountSetup) == 0) {
            return array(
                'status' => false,
                'message' => 'Account Setup not found',
                'data' => []
            );
        }
        $document = RecoveryLoan::find($documentId);
        $loandata=$document;
        if (!isset($document)) {
            return array(
                'status' => false,
                'message' => 'Document not found',
                'data' => []
            );
        }
        //Make array according to setup
        $postingArray = array(
            self::Bank_ACCOUNT => [],
            self::CUSTOMER_ACCOUNT => [],
            self::INTEREST_ACCOUNT => [],
        );
        //Assign Credit and Debit amount for tally check
        $totalCreditAmount = 0;
        $totalDebitAmount = 0;
        //Customer Account initialize
        $financesetup = LoanFinancialAccount::first();
        if(!empty($financesetup))
        {
            $FinanceLedgerId = $financesetup->pro_ledger_id;
            $FinanceLedgerGroupId = $financesetup->pro_ledger_group_id;
            $FinanceLedger = Ledger::find($FinanceLedgerId);
            $FinanceLedgerGroup = Group::find($FinanceLedgerGroupId);
            $InterestLedgerId = $financesetup->int_ledger_id;
            $InterestLedgerGroupId = $financesetup->int_ledger_group_id;
            $InterestLedger = Ledger::find($InterestLedgerId);
            $InterestLedgerGroup = Group::find($InterestLedgerGroupId);

        }

        //Customer Ledger account not found
        if (!isset($FinanceLedger) || !isset($FinanceLedgerGroup)) {
            return array(
                'status' => false,
                'message' => 'Finanical Setup Ledger not setup',
                'data' => []
            );
        }

        //Interest Ledger account not found
        if (!isset($InterestLedger) || !isset($InterestLedgerGroup)) {
            return array(
                'status' => false,
                'message' => 'Finanical Setup Interest Ledger not setup',
                'data' => []
            );
        }
        //Status to check if all ledger entries were properly set
        $ledgerErrorStatus = null;



        if(!empty($loandata))
        {
            $BankLedgerId = $loandata->bank->ledger_id;
            $BankLedgerGroupId = $loandata->bank->ledger_group_id;
            $BankLedger = Ledger::find($BankLedgerId);
            $BankLedgerGroup = Group::find($BankLedgerGroupId);

        }

        if (!isset($BankLedger) || !isset($BankLedgerGroup)) {
            return array(
                'status' => false,
                'message' => 'Bank Ledger not setup',
                'data' => []
            );
        }

            array_push($postingArray[self::Bank_ACCOUNT], [
                'ledger_id' => $loandata->bank->ledger_id,
                'ledger_group_id' => $loandata->bank->ledger_id,
                'ledger_code' => $BankLedger ?-> code,
                'ledger_name' => $BankLedger ?-> name,
                'ledger_group_code' => $BankLedgerGroup ?-> name,
                'debit_amount' => $loandata->settled_principal+$loandata->settled_interest,
                'credit_amount' => 0
            ]);

            array_push($postingArray[self::CUSTOMER_ACCOUNT], [
                'ledger_id' => $FinanceLedgerId,
                'ledger_group_id' => $FinanceLedgerGroupId,
                'ledger_code' => $FinanceLedger ?-> code,
                'ledger_name' => $FinanceLedger ?-> name,
                'ledger_group_code' => $FinanceLedgerGroup?-> name,
                'credit_amount' => $loandata->settled_principal,
                'debit_amount' => 0
            ]);


            array_push($postingArray[self::INTEREST_ACCOUNT], [
                'ledger_id' => $InterestLedgerId,
                'ledger_group_id' => $InterestLedgerGroupId,
                'ledger_code' => $InterestLedger ?-> code,
                'ledger_name' => $InterestLedger ?-> name,
                'ledger_group_code' => $InterestLedgerGroup?-> name,
                'credit_amount' => $loandata->settled_interest,
                'debit_amount' => 0
            ]);

        //Check if All Legders exists and posting is properly set
        if ($ledgerErrorStatus) {
            return array(
                'status' => false,
                'message' => $ledgerErrorStatus,
                'data' => []
            );
        }
        //Check debit and credit tally
        foreach ($postingArray as $postAccount) {
            foreach ($postAccount as $postingValue) {

                $totalDebitAmount += $postingValue['debit_amount'];
                $totalCreditAmount += $postingValue['credit_amount'];
            }
        }
        //Balance does not match
        if ($totalDebitAmount !== $totalCreditAmount) {
            return array(
                'status' => false,
                'message' => 'Credit Amount does not match Debit Amount',
                'data' => []
            );
        }
        //Get Header Details
        $book = Book::find($document->book_id);
        $glPostingBookParam = OrganizationBookParameter::where('book_id', $book->id)->where('parameter_name', ServiceParametersHelper::GL_POSTING_SERIES_PARAM)->first();
        if (isset($glPostingBookParam)) {
            $glPostingBookId = $glPostingBookParam -> parameter_value[0];
        } else {
            return array(
                'status' => false,
                'message' => 'Financial Book Code is not specified',
                'data' => []
            );
        }

        $currdata = OrganizationCompany::where('id',$document->company_id)->first();
        $excurrdata = CurrencyExchange::where('from_currency_id',$currdata->currency_id)->first();
        $currency = Currency::find($currdata->currency_id);

        $userData = Helper::userCheck();

        $booksdata = Book::where('book_name','JOURNAL_VOUCHER')->first();
        $numberPatternData = Helper::generateDocumentNumberNew($booksdata->id, $document->document_date);
        if (!isset($numberPatternData)) {
            return response()->json([
                'message' => "Invalid Book",
                'error' => "",
            ], 422);
        }
        $document_number = $numberPatternData['document_number'] ? $numberPatternData['document_number'] : null;

        $voucherHeader = [
            'voucher_no' => $document_number,
            'voucher_name' => $booksdata->book_code,
            'doc_prefix' => $numberPatternData['prefix'],
            'doc_suffix' => $numberPatternData['suffix'],
            'doc_no' => $numberPatternData['doc_no'],
            'doc_reset_pattern' => $numberPatternData['reset_pattern'],
            'document_date' => $document->document_date,
            'book_id' => $booksdata->id,
            'book_type_id' => $booksdata->org_service_id,
            'date' => $document->document_date,
            'amount' => $totalCreditAmount,
            'currency_id' => $currdata->currency_id,
            'currency_code' => $currdata->currency_code,
            'org_currency_id' => $currdata->currency_id,
            'org_currency_code' => $currdata->currency_code,
            'org_currency_exg_rate' => $excurrdata->exchange_rate,
            'comp_currency_id' => $currdata->currency_id, // Missing comma added here
            'comp_currency_code' => $currdata->currency_code,
            'comp_currency_exg_rate' => $excurrdata->exchange_rate,
            'group_currency_id' => $currdata->currency_id,
            'group_currency_code' => $currdata->currency_code,
            'group_currency_exg_rate' => $excurrdata->exchange_rate,
            'reference_service' => $book?->service?->alias,
            'reference_doc_id' => $document->id,
            'group_id' => $document->group_id,
            'company_id' => $document->company_id,
            'organization_id' => $document->organization_id,
            'voucherable_type' => $userData['user_type'],
            'voucherable_id' => $userData['user_id'],
            'approvalStatus' => ConstantHelper::APPROVED,
            'document_status' => ConstantHelper::APPROVED,
            'approvalLevel' => $document->approval_level,
            'remarks'=>$remarks,
        ];

       $voucherDetails = [];
       foreach ($postingArray as $entryType => $postDetails) {
        foreach ($postDetails as $post) {
            array_push($voucherDetails, [
                'ledger_id' => $post['ledger_id'],
                'group_id' => $document->group_id,
                'company_id' => $document->company_id,
                'organization_id'=>$document->organization_id,
                'ledger_parent_id' => $post['ledger_group_id'],
                'debit_amt' => $post['debit_amount'],
                'credit_amt' => $post['credit_amount'],
                'debit_amt_org' => $post['debit_amount'] * $voucherHeader['org_currency_exg_rate'],
                'credit_amt_org' => $post['credit_amount'] * $voucherHeader['org_currency_exg_rate'],
                'debit_amt_comp' => $post['debit_amount'] * $voucherHeader['comp_currency_exg_rate'],
                'credit_amt_comp' => $post['credit_amount'] * $voucherHeader['comp_currency_exg_rate'],
                'debit_amt_group' => $post['debit_amount'] * $voucherHeader['group_currency_exg_rate'],
                'credit_amt_group' => $post['credit_amount'] * $voucherHeader['group_currency_exg_rate'],
                'entry_type' => $entryType,
            ]);
        }
       }
        return array(
            'status' => true,
            'message' => 'Posting Details found',
            'data' => [
                'voucher_header' => $voucherHeader,
                'voucher_details' => $voucherDetails,
                'document_date' => $document->created_at,
                'ledgers' => $postingArray,
                'total_debit' => $totalDebitAmount,
                'total_credit' => $totalCreditAmount,
                'book_code' => $booksdata ?-> book_code,
                'document_number' =>$document_number,
                'currency_code' => $currency ?-> short_name
            ]
        );
    }

    public static function loanSettleInvoiceVoucherDetails(int $documentId, string $remarks) : array
{
    $accountSetup = isset(self::SERVICE_POSTING_MAPPING[ConstantHelper::LOAN_SETTLEMENT])
    ? self::SERVICE_POSTING_MAPPING[ConstantHelper::LOAN_SETTLEMENT] : [];
    if (!isset($accountSetup) || count($accountSetup) == 0) {
        return array(
            'status' => false,
            'message' => 'Account Setup not found',
            'data' => []
        );
    }
    $document = LoanSettlement::find($documentId);
    $loandata=$document;
    if (!isset($document)) {
        return array(
            'status' => false,
            'message' => 'Document not found',
            'data' => []
        );
    }
    //Make array according to setup
    $postingArray = array(
        self::CUSTOMER_ACCOUNT => [],
        self::WRITE_OFF_ACCOUNT => [],
    );
    //Assign Credit and Debit amount for tally check
    $totalCreditAmount = 0;
    $totalDebitAmount = 0;
    //Customer Account initialize
    $financesetup = LoanFinancialAccount::first();
    if(!empty($financesetup))
    {
        $FinanceLedgerId = $financesetup->pro_ledger_id;
        $FinanceLedgerGroupId = $financesetup->pro_ledger_group_id;
        $FinanceLedger = Ledger::find($FinanceLedgerId);
        $FinanceLedgerGroup = Group::find($FinanceLedgerGroupId);
        $WriteOffLedgerId = $financesetup->wri_ledger_id;
        $WriteOffLedgerGroupId = $financesetup->wri_ledger_group_id;
        $WriteOffLedger = Ledger::find($WriteOffLedgerId);
        $WriteOffLedgerGroup = Group::find($WriteOffLedgerGroupId);

    }

    //Customer Ledger account not found
    if (!isset($FinanceLedger) || !isset($FinanceLedgerGroup)) {
        return array(
            'status' => false,
            'message' => 'Finanical Setup Customer Ledger not setup',
            'data' => []
        );
    }

    //WriteOff Ledger account not found
    if (!isset($WriteOffLedger) || !isset($WriteOffLedgerGroup)) {
        return array(
            'status' => false,
            'message' => 'Finanical Setup WriteOff Ledger not setup',
            'data' => []
        );
    }
    //Status to check if all ledger entries were properly set
    $ledgerErrorStatus = null;



        array_push($postingArray[self::CUSTOMER_ACCOUNT], [
            'ledger_id' => $FinanceLedgerId,
            'ledger_group_id' => $FinanceLedgerGroupId,
            'ledger_code' => $FinanceLedger ?-> code,
            'ledger_name' => $FinanceLedger ?-> name,
            'ledger_group_code' => $FinanceLedgerGroup?-> name,
            'credit_amount' => Helper::removeCommas($loandata->settle_amnnt),
            'debit_amount' => 0
        ]);


        array_push($postingArray[self::WRITE_OFF_ACCOUNT], [
            'ledger_id' => $WriteOffLedgerId,
            'ledger_group_id' => $WriteOffLedgerGroupId,
            'ledger_code' => $WriteOffLedger ?-> code,
            'ledger_name' => $WriteOffLedger ?-> name,
            'ledger_group_code' => $WriteOffLedgerGroup?-> name,
            'credit_amount' => 0,
            'debit_amount' => Helper::removeCommas($loandata->settle_amnnt)
        ]);

    //Check if All Legders exists and posting is properly set
    if ($ledgerErrorStatus) {
        return array(
            'status' => false,
            'message' => $ledgerErrorStatus,
            'data' => []
        );
    }
    //Check debit and credit tally
    foreach ($postingArray as $postAccount) {
        foreach ($postAccount as $postingValue) {

            $totalDebitAmount += $postingValue['debit_amount'];
            $totalCreditAmount += $postingValue['credit_amount'];
        }
    }
    //Balance does not match
    if ($totalDebitAmount !== $totalCreditAmount) {
        return array(
            'status' => false,
            'message' => 'Credit Amount does not match Debit Amount',
            'data' => []
        );
    }
    //Get Header Details
    $book = Book::find($document->book_id);
    $glPostingBookParam = OrganizationBookParameter::where('book_id', $book->id)->where('parameter_name', ServiceParametersHelper::GL_POSTING_SERIES_PARAM)->first();
    if (isset($glPostingBookParam)) {
        $glPostingBookId = $glPostingBookParam -> parameter_value[0];
    } else {
        return array(
            'status' => false,
            'message' => 'Financial Book Code is not specified',
            'data' => []
        );
    }

    $currdata = OrganizationCompany::where('id',$document->company_id)->first();
    $excurrdata = CurrencyExchange::where('from_currency_id',$currdata->currency_id)->first();
    $currency = Currency::find($currdata->currency_id);

    $userData = Helper::userCheck();

    $booksdata = Book::where('book_name','JOURNAL_VOUCHER')->first();
    $numberPatternData = Helper::generateDocumentNumberNew($booksdata->id, $document->document_date);
    if (!isset($numberPatternData)) {
        return response()->json([
            'message' => "Invalid Book",
            'error' => "",
        ], 422);
    }
    $document_number = $numberPatternData['document_number'] ? $numberPatternData['document_number'] : null;

    $voucherHeader = [
        'voucher_no' => $document_number,
        'voucher_name' => $booksdata->book_code,
        'doc_prefix' => $numberPatternData['prefix'],
        'doc_suffix' => $numberPatternData['suffix'],
        'doc_no' => $numberPatternData['doc_no'],
        'doc_reset_pattern' => $numberPatternData['reset_pattern'],
        'document_date' => $document->document_date,
        'book_id' => $booksdata->id,
        'book_type_id' => $booksdata->org_service_id,
        'date' => $document->document_date,
        'amount' => $totalCreditAmount,
        'currency_id' => $currdata->currency_id,
        'currency_code' => $currdata->currency_code,
        'org_currency_id' => $currdata->currency_id,
        'org_currency_code' => $currdata->currency_code,
        'org_currency_exg_rate' => $excurrdata->exchange_rate,
        'comp_currency_id' => $currdata->currency_id, // Missing comma added here
        'comp_currency_code' => $currdata->currency_code,
        'comp_currency_exg_rate' => $excurrdata->exchange_rate,
        'group_currency_id' => $currdata->currency_id,
        'group_currency_code' => $currdata->currency_code,
        'group_currency_exg_rate' => $excurrdata->exchange_rate,
        'reference_service' => $book?->service?->alias,
        'reference_doc_id' => $document->id,
        'group_id' => $document->group_id,
        'company_id' => $document->company_id,
        'organization_id' => $document->organization_id,
        'voucherable_type' => $userData['user_type'],
        'voucherable_id' => $userData['user_id'],
        'approvalStatus' => ConstantHelper::APPROVED,
        'document_status' => ConstantHelper::APPROVED,
        'approvalLevel' => $document->approval_level,
        'remarks'=>$remarks,
    ];

   $voucherDetails = [];
   foreach ($postingArray as $entryType => $postDetails) {
    foreach ($postDetails as $post) {
        array_push($voucherDetails, [
            'ledger_id' => $post['ledger_id'],
            'group_id' => $document->group_id,
            'company_id' => $document->company_id,
            'organization_id'=>$document->organization_id,
            'ledger_parent_id' => $post['ledger_group_id'],
            'debit_amt' => $post['debit_amount'],
            'credit_amt' => $post['credit_amount'],
            'debit_amt_org' => $post['debit_amount'] * $voucherHeader['org_currency_exg_rate'],
            'credit_amt_org' => $post['credit_amount'] * $voucherHeader['org_currency_exg_rate'],
            'debit_amt_comp' => $post['debit_amount'] * $voucherHeader['comp_currency_exg_rate'],
            'credit_amt_comp' => $post['credit_amount'] * $voucherHeader['comp_currency_exg_rate'],
            'debit_amt_group' => $post['debit_amount'] * $voucherHeader['group_currency_exg_rate'],
            'credit_amt_group' => $post['credit_amount'] * $voucherHeader['group_currency_exg_rate'],
            'entry_type' => $entryType,
        ]);
    }
   }
    return array(
        'status' => true,
        'message' => 'Posting Details found',
        'data' => [
            'voucher_header' => $voucherHeader,
            'voucher_details' => $voucherDetails,
            'document_date' => $document->created_at,
            'ledgers' => $postingArray,
            'total_debit' => $totalDebitAmount,
            'total_credit' => $totalCreditAmount,
            'book_code' => $booksdata ?-> book_code,
            'document_number' =>$document_number,
            'currency_code' => $currency ?-> short_name
        ]
    );
}


    public static function dnVoucherDetails(int $documentId, string $type) : array
    {
        $document = ErpSaleInvoice::find($documentId);
        if (!isset($document)) {
            return array(
                'status' => false,
                'message' => 'Document not found',
                'data' => []
            );
        }

        //Invoice to follow
        $invoiceToFollow = $document -> invoice_required;
        $postingArray = array(
            self::CUSTOMER_ACCOUNT => [],
            self::DISCOUNT_ACCOUNT => [],
            self::SALES_ACCOUNT => [],
            self::TAX_ACCOUNT => [],
            self::EXPENSE_ACCOUNT => [],
            self::COGS_ACCOUNT => [],
            self::STOCK_ACCOUNT => []
        );
        //Assign Credit and Debit amount for tally check
        $totalCreditAmount = 0;
        $totalDebitAmount = 0;


        //Status to check if all ledger entries were properly set
        $ledgerErrorStatus = null;
        //COGS SETUP
        foreach ($document -> items as $docItemKey => $docItem) {
            $itemValue = 0;
            $stockLedger = StockLedger::where('book_type', 'invoice') -> where('document_header_id', $document -> id) -> where('document_detail_id', $docItem -> id) -> first();
            if (isset($stockLedger)) {
                $orgCurrencyCost = StockLedger::where('utilized_id', $stockLedger -> id) -> get() -> sum('org_currency_cost');
                $itemValue = $orgCurrencyCost / $document -> org_currency_exg_rate;
            }
            // $itemValue = ($docItem -> rate * $docItem -> order_qty * 0.80);//CHANGE
            $stockCreditAmount = round($itemValue, 2);
            $cogsDebitAmount = round($itemValue, 2);

            $cogsLedgerDetails = AccountHelper::getCogsLedgerGroupAndLedgerId($document -> organization_id, $docItem -> item_id, $document -> book_id);
            $cogsLedgerId = is_a($cogsLedgerDetails, Collection::class) ? $cogsLedgerDetails -> first()['ledger_id'] : null;
            $cogsLedgerGroupId = is_a($cogsLedgerDetails, Collection::class) ? $cogsLedgerDetails-> first()['ledger_group'] : null;
            $cogsLedger = Ledger::find($cogsLedgerId);
            $cogsLedgerGroup = Group::find($cogsLedgerGroupId);
            //LEDGER NOT FOUND
            if (!isset($cogsLedger) || !isset($cogsLedgerGroup)) {
                $ledgerErrorStatus = self::ERROR_PREFIX.'COGS Account not setup';
                break;
            }
            //Check for same ledger and group in SALES ACCOUNT
            $existingCogsLedger = array_filter($postingArray[self::COGS_ACCOUNT], function ($posting) use($cogsLedgerId, $cogsLedgerGroupId) {
                return $posting['ledger_id'] == $cogsLedgerId && $posting['ledger_group_id'] == $cogsLedgerGroupId;
            });
            //Ledger found
            if (count($existingCogsLedger) > 0) {
                $postingArray[self::COGS_ACCOUNT][0]['debit_amount'] +=  $cogsDebitAmount;
                $postingArray[self::COGS_ACCOUNT][0]['debit_amount_org'] +=  $orgCurrencyCost;
            } else { //Assign a new ledger
                array_push($postingArray[self::COGS_ACCOUNT], [
                    'ledger_id' => $cogsLedgerId,
                    'ledger_group_id' => $cogsLedgerGroupId,
                    'ledger_code' => $cogsLedger ?-> code,
                    'ledger_name' => $cogsLedger ?-> name,
                    'ledger_group_code' => $cogsLedgerGroup ?-> name,
                    'credit_amount' => 0,
                    'credit_amount_org' => 0,
                    'debit_amount' => $cogsDebitAmount,
                    'debit_amount_org' => $orgCurrencyCost
                ]);
            }

            $stockLedgerDetails = AccountHelper::getStockLedgerGroupAndLedgerId($document -> organization_id, $docItem -> item_id, $document -> book_id);
            $stockLedgerId = is_a($stockLedgerDetails, Collection::class) ? $stockLedgerDetails -> first()['ledger_id'] : null;
            $stockLedgerGroupId = is_a($stockLedgerDetails, Collection::class) ? $stockLedgerDetails-> first()['ledger_group'] : null;
            $stockLedger = Ledger::find($stockLedgerId);
            $stockLedgerGroup = Group::find($stockLedgerGroupId);
            //LEDGER NOT FOUND
            if (!isset($stockLedger) || !isset($stockLedgerGroup)) {
                $ledgerErrorStatus = self::ERROR_PREFIX.'Stock Account not setup';
                break;
            }

            //Check for same ledger and group in SALES ACCOUNT
            $existingstockLedger = array_filter($postingArray[self::STOCK_ACCOUNT], function ($posting) use($stockLedgerId, $stockLedgerGroupId) {
                return $posting['ledger_id'] == $stockLedgerId && $posting['ledger_group_id'] == $stockLedgerGroupId;
            });
            //Ledger found
            if (count($existingstockLedger) > 0) {
                $postingArray[self::STOCK_ACCOUNT][0]['credit_amount'] +=  $stockCreditAmount;
                $postingArray[self::STOCK_ACCOUNT][0]['credit_amount_org'] +=  $orgCurrencyCost;
            } else { //Assign a new ledger
                array_push($postingArray[self::STOCK_ACCOUNT], [
                    'ledger_id' => $stockLedgerId,
                    'ledger_group_id' => $stockLedgerGroupId,
                    'ledger_code' => $stockLedger ?-> code,
                    'ledger_name' => $stockLedger ?-> name,
                    'ledger_group_code' => $stockLedgerGroup ?-> name,
                    'credit_amount' => $stockCreditAmount,
                    'credit_amount_org' => $orgCurrencyCost,
                    'debit_amount' => 0,
                    'debit_amount_org' => 0,
                ]);
            }
        }
        //Customer Account initialize
        if (!$invoiceToFollow) {

            $customer = Customer::find($document -> customer_id);
            $customerLedgerId = $customer -> ledger_id;
            $customerLedgerGroupId = $customer -> ledger_group_id;
            $customerLedger = Ledger::find($customerLedgerId);
            $customerLedgerGroup = Group::find($customerLedgerGroupId);
            //Customer Ledger account not found
            if (!isset($customerLedger) || !isset($customerLedgerGroup)) {
                return array(
                    'status' => false,
                    'message' => self::ERROR_PREFIX.'Customer Account not setup',
                    'data' => []
                );
            }
            $discountPostingParam = OrganizationBookParameter::where('book_id', $document -> book_id)
            -> where('parameter_name', ServiceParametersHelper::GL_SEPERATE_DISCOUNT_PARAM) -> first();
            if (isset($discountPostingParam)) {
                $discountSeperatePosting = $discountPostingParam -> parameter_value[0] === "yes" ? true : false;
            } else {
                $discountSeperatePosting = false;
            }
            foreach ($document -> items as $docItemKey => $docItem) {
                //Assign Item values
                $itemValue = $docItem -> rate * $docItem -> order_qty;
                $itemTotalDiscount = $docItem -> header_discount_amount + $docItem -> item_discount_amount;
                $itemValueAfterDiscount = $itemValue - $itemTotalDiscount;
                //SALES ACCOUNT
                $salesAccountLedgerDetails = AccountHelper::getLedgerGroupAndLedgerIdForSalesAccount($document -> organization_id, $document -> customer_id, $docItem -> item_id, $document -> book_id);
                $salesAccountLedgerId = is_a($salesAccountLedgerDetails, Collection::class) ? $salesAccountLedgerDetails -> first()['ledger_id'] : null;
                $salesAccountLedgerGroupId = is_a($salesAccountLedgerDetails, Collection::class) ? $salesAccountLedgerDetails-> first()['ledger_group'] : null;
                $salesAccountLedger = Ledger::find($salesAccountLedgerId);
                $salesAccountLedgerGroup = Group::find($salesAccountLedgerGroupId);
                //LEDGER NOT FOUND
                if (!isset($salesAccountLedger) || !isset($salesAccountLedgerGroup)) {
                    $ledgerErrorStatus = self::ERROR_PREFIX.'Sales Account not setup';
                    break;
                }
                $salesCreditAmount = $discountSeperatePosting ? $itemValue : $itemValueAfterDiscount;
                //Check for same ledger and group in SALES ACCOUNT
                $existingSalesLedger = array_filter($postingArray[self::SALES_ACCOUNT], function ($posting) use($salesAccountLedgerId, $salesAccountLedgerGroupId) {
                    return $posting['ledger_id'] == $salesAccountLedgerId && $posting['ledger_group_id'] == $salesAccountLedgerGroupId;
                });
                //Ledger found
                if (count($existingSalesLedger) > 0) {
                    $postingArray[self::SALES_ACCOUNT][0]['credit_amount'] +=  $salesCreditAmount;
                } else { //Assign a new ledger
                    array_push($postingArray[self::SALES_ACCOUNT], [
                        'ledger_id' => $salesAccountLedgerId,
                        'ledger_group_id' => $salesAccountLedgerGroupId,
                        'ledger_code' => $salesAccountLedger ?-> code,
                        'ledger_name' => $salesAccountLedger ?-> name,
                        'ledger_group_code' => $salesAccountLedgerGroup ?-> name,
                        'credit_amount' => $salesCreditAmount,
                        'debit_amount' => 0
                    ]);
                }
                //Check for same ledger and group in CUSTOMER ACCOUNT
                $existingcustomerLedger = array_filter($postingArray[self::CUSTOMER_ACCOUNT], function ($posting) use($customerLedgerId, $customerLedgerGroupId) {
                    return $posting['ledger_id'] == $customerLedgerId && $posting['ledger_group_id'] === $customerLedgerGroupId;
                });
                //Ledger found
                if (count($existingcustomerLedger) > 0) {
                    $postingArray[self::CUSTOMER_ACCOUNT][0]['debit_amount'] += $itemValueAfterDiscount;
                } else { //Assign a new ledger
                    array_push($postingArray[self::CUSTOMER_ACCOUNT], [
                        'ledger_id' => $customerLedgerId,
                        'ledger_group_id' => $customerLedgerGroupId,
                        'ledger_code' => $customerLedger ?-> code,
                        'ledger_name' => $customerLedger ?-> name,
                        'ledger_group_code' => $customerLedgerGroup ?-> name,
                        'debit_amount' => $itemValueAfterDiscount,
                        'credit_amount' => 0
                    ]);
                }
            }
            //TAXES ACCOUNT
            $taxes = ErpSaleInvoiceTed::where('sale_invoice_id', $document -> id) -> where('ted_type', "Tax") -> get();
            foreach ($taxes as $tax) {
                $taxDetail = TaxDetail::find($tax -> ted_id);
                $taxLedgerId = $taxDetail -> ledger_id ?? null; //MAKE IT DYNAMIC
                $taxLedgerGroupId = $taxDetail -> ledger_group_id ?? null; //MAKE IT DYNAMIC
                $taxLedger = Ledger::find($taxLedgerId);
                $taxLedgerGroup = Group::find($taxLedgerGroupId);
                if (!isset($taxLedger) || !isset($taxLedgerGroup)) {
                    $ledgerErrorStatus = self::ERROR_PREFIX.'Tax Account not setup';
                    break;
                }
                $existingTaxLedger = array_filter($postingArray[self::TAX_ACCOUNT], function ($posting) use($taxLedgerId, $taxLedgerGroupId) {
                    return $posting['ledger_id'] == $taxLedgerId && $posting['ledger_group_id'] === $taxLedgerGroupId;
                });
                //Ledger found
                if (count($existingTaxLedger) > 0) {
                    $postingArray[self::TAX_ACCOUNT][0]['credit_amount'] += $tax -> ted_amount;
                } else { //Assign a new ledger
                    array_push($postingArray[self::TAX_ACCOUNT], [
                        'ledger_id' => $taxLedgerId,
                        'ledger_group_id' => $taxLedgerGroupId,
                        'ledger_code' => $taxLedger ?-> code,
                        'ledger_name' => $taxLedger ?-> name,
                        'ledger_group_code' => $taxLedgerGroup ?-> name,
                        'credit_amount' => $tax -> ted_amount,
                        'debit_amount' => 0,
                    ]);
                }
                //Tax for CUSTOMER ACCOUNT
                $existingCustomerLedger = array_filter($postingArray[self::CUSTOMER_ACCOUNT], function ($posting) use($customerLedgerId, $customerLedgerGroupId) {
                    return $posting['ledger_id'] == $customerLedgerId && $posting['ledger_group_id'] === $customerLedgerGroupId;
                });
                //Ledger found
                if (count($existingCustomerLedger) > 0) {
                    $postingArray[self::CUSTOMER_ACCOUNT][0]['debit_amount'] += $tax -> ted_amount;
                } else { //Assign new ledger
                    array_push($postingArray[self::CUSTOMER_ACCOUNT], [
                        'ledger_id' => $taxLedgerId,
                        'ledger_group_id' => $taxLedgerGroupId,
                        'ledger_code' => $taxLedger ?-> code,
                        'ledger_name' => $taxLedger ?-> name,
                        'ledger_group_code' => $taxLedgerGroup ?-> name,
                        'credit_amount' => 0,
                        'debit_amount' => $tax -> ted_amount,
                    ]);
                }
            }
            //EXPENSES
            $expenses = ErpSaleInvoiceTed::where('sale_invoice_id', $document -> id) -> where('ted_type', "Expense") -> get();
            foreach ($expenses as $expense) {
                $expenseDetail = ExpenseMaster::find($expense -> ted_id);
                $expenseLedgerId = $expenseDetail ?-> expense_ledger_id; //MAKE IT DYNAMIC - 5
                $expenseLedgerGroupId = $expenseDetail ?-> expense_ledger_group_id; //MAKE IT DYNAMIC - 9
                $expenseLedger = Ledger::find($expenseLedgerId);
                $expenseLedgerGroup = Group::find($expenseLedgerGroupId);
                if (!isset($expenseLedger) || !isset($expenseLedgerGroup)) {
                    $ledgerErrorStatus = self::ERROR_PREFIX.'Expense Account not setup';
                    break;
                }
                $existingExpenseLedger = array_filter($postingArray[self::EXPENSE_ACCOUNT], function ($posting) use($expenseLedgerId, $expenseLedgerGroupId) {
                    return $posting['ledger_id'] == $expenseLedgerId && $posting['ledger_group_id'] === $expenseLedgerGroupId;
                });
                //Ledger found
                if (count($existingExpenseLedger) > 0) {
                    $postingArray[self::EXPENSE_ACCOUNT][0]['credit_amount'] += $expense -> ted_amount;
                } else { //Assign a new ledger
                    array_push($postingArray[self::EXPENSE_ACCOUNT], [
                        'ledger_id' => $expenseLedgerId,
                        'ledger_group_id' => $expenseLedgerGroupId,
                        'ledger_code' => $expenseLedger ?-> code,
                        'ledger_name' => $expenseLedger ?-> name,
                        'ledger_group_code' => $expenseLedgerGroup ?-> name,
                        'credit_amount' => $expense -> ted_amount,
                        'debit_amount' => 0,
                    ]);
                }
                //Expense for CUSTOMER ACCOUNT
                $existingCustomerLedger = array_filter($postingArray[self::CUSTOMER_ACCOUNT], function ($posting) use($customerLedgerId, $customerLedgerGroupId) {
                    return $posting['ledger_id'] == $customerLedgerId && $posting['ledger_group_id'] === $customerLedgerGroupId;
                });
                //Ledger found
                if (count($existingCustomerLedger) > 0) {
                    $postingArray[self::CUSTOMER_ACCOUNT][0]['debit_amount'] += $expense -> ted_amount;
                } else { //Assign new ledger
                    array_push($postingArray[self::EXPENSE_ACCOUNT], [
                        'ledger_id' => $expenseLedgerId,
                        'ledger_group_id' => $expenseLedgerGroupId,
                        'ledger_code' => $expenseLedger ?-> code,
                        'ledger_name' => $expenseLedger ?-> name,
                        'ledger_group_code' => $expenseLedgerGroup ?-> name,
                        'credit_amount' => 0,
                        'debit_amount' => $expense -> ted_amount,
                    ]);
                }
            }
            //Seperate posting of Discount
            if ($discountSeperatePosting) {
                $discounts = ErpSaleInvoiceTed::where('sale_invoice_id', $document -> id) -> where('ted_type', "Discount") -> get();
                foreach ($discounts as $discount) {
                    $discountDetail = DiscountMaster::find($discount -> ted_id);
                    $discountLedgerId = $discountDetail ?-> discount_ledger_id; //MAKE IT DYNAMIC
                    $discountLedgerGroupId = $discountDetail ?-> discount_ledger_group_id; //MAKE IT DYNAMIC
                    $discountLedger = Ledger::find($discountLedgerId);
                    $discountLedgerGroup = Group::find($discountLedgerGroupId);
                    if (!isset($discountLedger) || !isset($discountLedgerGroup)) {
                        $ledgerErrorStatus = self::ERROR_PREFIX.'Discount Account not setup';
                        break;
                    }
                    $existingDiscountLedger = array_filter($postingArray[self::DISCOUNT_ACCOUNT], function ($posting) use($discountLedgerId, $discountLedgerGroupId) {
                        return $posting['ledger_id'] == $discountLedgerId && $posting['ledger_group_id'] === $discountLedgerGroupId;
                    });
                    //Ledger found
                    if (count($existingDiscountLedger) > 0) {
                        $postingArray[self::DISCOUNT_ACCOUNT][0]['debit_amount'] += $discount -> ted_amount;
                    } else { //Assign a new ledger
                        array_push($postingArray[self::DISCOUNT_ACCOUNT], [
                            'ledger_id' => $discountLedgerId,
                            'ledger_group_id' => $discountLedgerGroupId,
                            'ledger_code' => $discountLedger ?-> code,
                            'ledger_name' => $discountLedger ?-> name,
                            'ledger_group_code' => $discountLedgerGroup ?-> name,
                            'debit_amount' => $discount -> ted_amount,
                            'credit_amount' => 0,
                        ]);
                    }
                }
            }
        }
        //Check if All Legders exists and posting is properly set
        if ($ledgerErrorStatus) {
            return array(
                'status' => false,
                'message' => $ledgerErrorStatus,
                'data' => []
            );
        }
        //Check debit and credit tally
        foreach ($postingArray as $postAccount) {
            foreach ($postAccount as $postingValue) {
                $totalCreditAmount += $postingValue['credit_amount'];
                $totalDebitAmount += $postingValue['debit_amount'];
            }
        }
        //Balance does not match
        if ($totalDebitAmount !== $totalCreditAmount) {
            return array(
                'status' => false,
                'message' => self::ERROR_PREFIX.'Credit Amount does not match Debit Amount',
                'data' => []
            );
        }
        //Get Header Details
        $book = Book::find($document -> book_id);
        $glPostingBookParam = OrganizationBookParameter::where('book_id', $book -> id) -> where('parameter_name', ServiceParametersHelper::GL_POSTING_SERIES_PARAM) -> first();
        if (isset($glPostingBookParam)) {
            $glPostingBookId = $glPostingBookParam -> parameter_value[0];
        } else {
            return array(
                'status' => false,
                'message' => self::ERROR_PREFIX.'Financial Book Code is not specified',
                'data' => []
            );
        }
        $currency = Currency::find($document -> currency_id);
        $userData = Helper::userCheck();
        $voucherHeader = [
            'voucher_no' => $document -> document_number,
            'document_date' => $document -> document_date,
            'book_id' => $glPostingBookId,
            'date' => $document -> document_date,
            'amount' => $totalCreditAmount,
            'currency_id' => $document -> currency_id,
            'currency_code' => $document -> currency_code,
            'org_currency_id' => $document -> org_currency_id,
            'org_currency_code' => $document -> org_currency_code,
            'org_currency_exg_rate' => $document -> org_currency_exg_rate,
            'comp_currency_id' => $document -> comp_currency_id,
            'comp_currency_code' => $document -> comp_currency_code,
            'comp_currency_exg_rate' => $document -> comp_currency_exg_rate,
            'group_currency_id' => $document -> group_currency_id,
            'group_currency_code' => $document -> group_currency_code,
            'group_currency_exg_rate' => $document -> group_currency_exg_rate,
            'reference_service' => $book ?-> service ?-> alias,
            'reference_doc_id' => $document -> id,
            'group_id' => $document -> group_id,
            'company_id' => $document -> company_id,
            'organization_id' => $document -> organization_id,
            'voucherable_type' => $userData['user_type'],
            'voucherable_id' => $userData['user_id'],
            'document_status' => ConstantHelper::APPROVED,
            'approvalLevel' => $document -> approval_level
       ];
       $voucherDetails = [];
       foreach ($postingArray as $entryType => $postDetails) {
        foreach ($postDetails as $post) {
            $debitAmtOrg = $post['debit_amount'] * $voucherHeader['org_currency_exg_rate'];
            $creditAmtOrg = $post['credit_amount'] * $voucherHeader['org_currency_exg_rate'];

            $debitAmtComp = $post['debit_amount'] * $voucherHeader['comp_currency_exg_rate'];
            $creditAmtComp = $post['credit_amount'] * $voucherHeader['comp_currency_exg_rate'];

            $debitAmtGroup= $post['debit_amount'] * $voucherHeader['group_currency_exg_rate'];
            $creditAmtGroup = $post['credit_amount'] * $voucherHeader['group_currency_exg_rate'];

            if ($entryType === self::COGS_ACCOUNT || $entryType === self::STOCK_ACCOUNT) {
                $debitAmtOrg = $post['debit_amount_org'];
                $creditAmtOrg = $post['credit_amount_org'];
                if ($voucherHeader['org_currency_code'] === $voucherHeader['comp_currency_code']) {
                    $debitAmtComp = $post['debit_amount_org'];
                    $creditAmtComp = $post['credit_amount_org'];
                }
                if ($voucherHeader['org_currency_code'] === $voucherHeader['group_currency_code']) {
                    $debitAmtGroup = $post['debit_amount_org'];
                    $creditAmtGroup = $post['credit_amount_org'];
                }
            }
            array_push($voucherDetails, [
                'ledger_id' => $post['ledger_id'],
                'ledger_parent_id' => $post['ledger_group_id'],
                'debit_amt' => $post['debit_amount'],
                'credit_amt' => $post['credit_amount'],
                'debit_amt_org' => $debitAmtOrg,
                'credit_amt_org' => $creditAmtOrg,
                'debit_amt_comp' => $debitAmtComp,
                'credit_amt_comp' => $creditAmtComp,
                'debit_amt_group' => $debitAmtGroup,
                'credit_amt_group' => $creditAmtGroup,
                'entry_type' => $entryType,
            ]);
        }
       }
        return array(
            'status' => true,
            'message' => 'Posting Details found',
            'data' => [
                'voucher_header' => $voucherHeader,
                'voucher_details' => $voucherDetails,
                'document_date' => $document -> document_date,
                'ledgers' => $postingArray,
                'total_debit' => $totalDebitAmount,
                'total_credit' => $totalCreditAmount,
                'book_code' => $book ?-> book_code,
                'document_number' => $document -> document_number,
                'currency_code' => $currency ?-> short_name
            ]
        );
    }

    public static function mrnVoucherDetails(int $documentId, string $type) : array
    {
        // dd($documentId);
        $document = MrnHeader::find($documentId);
        if (!isset($document)) {
            return array(
                'status' => false,
                'message' => 'Document not found',
                'data' => []
            );
        }
        // dd($document->toArray());
        //Invoice to follow
        $invoiceToFollow = ($document -> bill_to_follow == 'yes') ? true : false;
        $postingArray = array(
            self::STOCK_ACCOUNT => [],
            self::TAX_ACCOUNT => [],
            self::EXPENSE_ACCOUNT => [],
            self::DISCOUNT_ACCOUNT => [],
            self::GRIR_ACCOUNT => [],
            self::SUPPLIER_ACCOUNT => [],
        );
        //Assign Credit and Debit amount for tally check
        $totalCreditAmount = 0;
        $totalDebitAmount = 0;

        // Vendor Detail
        $vendor = Vendor::find($document -> vendor_id);
        $vendorLedgerId = $vendor -> ledger_id;
        $vendorLedgerGroupId = $vendor -> ledger_group_id;
        $vendorLedger = Ledger::find($vendorLedgerId);
        $vendorLedgerGroup = Group::find($vendorLedgerGroupId);

        //Vendor Ledger account not found
        if (!isset($vendorLedger) || !isset($vendorLedgerGroup)) {
            return array(
                'status' => false,
                'message' => self::ERROR_PREFIX.'Vendor Account not setup',
                'data' => []
            );
        }

        $discountPostingParam = OrganizationBookParameter::where('book_id', $document -> book_id)
        -> where('parameter_name', ServiceParametersHelper::GL_SEPERATE_DISCOUNT_PARAM) -> first();
        if (isset($discountPostingParam)) {
            $discountSeperatePosting = $discountPostingParam -> parameter_value[0] === "yes" ? true : false;
        } else {
            $discountSeperatePosting = false;
        }


        //Status to check if all ledger entries were properly set
        $ledgerErrorStatus = null;
        //COGS SETUP
        foreach ($document -> items as $docItemKey => $docItem) {
            $itemValue = ($docItem -> rate * $docItem -> accepted_qty);
            $itemTotalDiscount = $docItem -> header_discount_amount + $docItem -> discount_amount;
            $itemValueAfterDiscount = $itemValue - $itemTotalDiscount;
            $stockDebitAmount = $discountSeperatePosting ? $itemValue : $itemValueAfterDiscount;

            $stockLedgerDetails = AccountHelper::getStockLedgerGroupAndLedgerId($document -> organization_id, $docItem -> item_id, $document -> book_id);
            // dd($stockLedgerDetails);
            $stockLedgerId = $stockLedgerDetails -> first()['ledger_id'] ?? null;
            $stockLedgerGroupId = $stockLedgerDetails-> first()['ledger_group'] ?? null;
            // dd($stockLedgerDetails -> first()['ledger_id']);
            $stockLedger = Ledger::find($stockLedgerId);
            $stockLedgerGroup = Group::find($stockLedgerGroupId);
            //LEDGER NOT FOUND
            if (!isset($stockLedger) || !isset($stockLedgerGroup)) {
                $ledgerErrorStatus = self::ERROR_PREFIX.'Stock Account not setup';
                break;
            }

            //Check for same ledger and group in SALES ACCOUNT
            $existingstockLedger = array_filter($postingArray[self::STOCK_ACCOUNT], function ($posting) use($stockLedgerId, $stockLedgerGroupId) {
                return $posting['ledger_id'] == $stockLedgerId && $posting['ledger_group_id'] == $stockLedgerGroupId;
            });
            //Ledger found
            if (count($existingstockLedger) > 0) {
                $postingArray[self::STOCK_ACCOUNT][0]['debit_amount'] +=  $stockDebitAmount;
            } else { //Assign a new ledger
                array_push($postingArray[self::STOCK_ACCOUNT], [
                    'ledger_id' => $stockLedgerId,
                    'ledger_group_id' => $stockLedgerGroupId,
                    'ledger_code' => $stockLedger ?-> code,
                    'ledger_name' => $stockLedger ?-> name,
                    'ledger_group_code' => $stockLedgerGroup ?-> name,
                    'credit_amount' => 0,
                    'debit_amount' => $stockDebitAmount
                ]);
            }
            if ($invoiceToFollow) {
                $grirCreditAmount = $itemValueAfterDiscount;
                $grirLedgerDetails = AccountHelper::getGrLedgerGroupAndLedgerId($document -> organization_id, $docItem -> item_id, $document -> book_id);
                $grirLedgerId = $grirLedgerDetails -> first()['ledger_id'] ?? null;
                $grirLedgerGroupId = $grirLedgerDetails-> first()['ledger_group'] ?? null;
                $grirLedger = Ledger::find($grirLedgerId);
                $grirLedgerGroup = Group::find($grirLedgerGroupId);
                //LEDGER NOT FOUND
                if (!isset($grirLedger) || !isset($grirLedgerGroup)) {
                    $ledgerErrorStatus = self::ERROR_PREFIX.'GR/IR Account not setup';
                    break;
                }
                //Check for same ledger and group in SALES ACCOUNT
                $existingGrirLedger = array_filter($postingArray[self::GRIR_ACCOUNT], function ($posting) use($grirLedgerId, $grirLedgerGroupId) {
                    return $posting['ledger_id'] == $grirLedgerId && $posting['ledger_group_id'] == $grirLedgerGroupId;
                });
                //Ledger found
                if (count($existingGrirLedger) > 0) {
                    $postingArray[self::GRIR_ACCOUNT][0]['credit_amount'] +=  $grirCreditAmount;
                } else { //Assign a new ledger
                    array_push($postingArray[self::GRIR_ACCOUNT], [
                        'ledger_id' => $grirLedgerId,
                        'ledger_group_id' => $grirLedgerGroupId,
                        'ledger_code' => $grirLedger ?-> code,
                        'ledger_name' => $grirLedger ?-> name,
                        'ledger_group_code' => $grirLedgerGroup ?-> name,
                        'credit_amount' => $grirCreditAmount,
                        'debit_amount' => 0
                    ]);
                }
            } else {
                //Stock for SUPPLIER ACCOUNT
                $existingVendorLedger = array_filter($postingArray[self::SUPPLIER_ACCOUNT], function ($posting) use($vendorLedgerId, $vendorLedgerGroupId) {
                    return $posting['ledger_id'] == $vendorLedgerId && $posting['ledger_group_id'] === $vendorLedgerGroupId;
                });
                //Ledger found
                if (count($existingVendorLedger) > 0) {
                    $postingArray[self::SUPPLIER_ACCOUNT][0]['credit_amount'] += $itemValueAfterDiscount;
                } else { //Assign new ledger
                    array_push($postingArray[self::SUPPLIER_ACCOUNT], [
                        'ledger_id' => $vendorLedgerId,
                        'ledger_group_id' => $vendorLedgerGroupId,
                        'ledger_code' => $vendorLedger ?-> code,
                        'ledger_name' => $vendorLedger ?-> name,
                        'ledger_group_code' => $vendorLedgerGroup ?-> name,
                        'credit_amount' => $itemValueAfterDiscount,
                        'debit_amount' => 0
                    ]);
                }

            }
        }

        if (!$invoiceToFollow) {
            //TAXES ACCOUNT
            $taxes = MrnExtraAmount::where('mrn_header_id', $document -> id) -> where('ted_type', "Tax") -> get();
            foreach ($taxes as $tax) {
                $taxDetail = TaxDetail::find($tax -> ted_id);
                $taxLedgerId = $taxDetail -> ledger_id ?? null; //MAKE IT DYNAMIC
                $taxLedgerGroupId = $taxDetail -> ledger_group_id ?? null; //MAKE IT DYNAMIC
                $taxLedger = Ledger::find($taxLedgerId);
                $taxLedgerGroup = Group::find($taxLedgerGroupId);
                if (!isset($taxLedger) || !isset($taxLedgerGroup)) {
                    $ledgerErrorStatus = self::ERROR_PREFIX.'Tax Account not setup';
                    break;
                }
                $existingTaxLedger = array_filter($postingArray[self::TAX_ACCOUNT], function ($posting) use($taxLedgerId, $taxLedgerGroupId) {
                    return $posting['ledger_id'] == $taxLedgerId && $posting['ledger_group_id'] === $taxLedgerGroupId;
                });
                //Ledger found
                if (count($existingTaxLedger) > 0) {
                    $postingArray[self::TAX_ACCOUNT][0]['debit_amount'] += $tax -> ted_amount;
                } else { //Assign a new ledger
                    array_push($postingArray[self::TAX_ACCOUNT], [
                        'ledger_id' => $taxLedgerId,
                        'ledger_group_id' => $taxLedgerGroupId,
                        'ledger_code' => $taxLedger ?-> code,
                        'ledger_name' => $taxLedger ?-> name,
                        'ledger_group_code' => $taxLedgerGroup ?-> name,
                        'credit_amount' => 0,
                        'debit_amount' => $tax -> ted_amount,
                    ]);
                }
                // dd($tax -> ted_amount);
                //Tax for SUPPLIER ACCOUNT
                $existingVendorLedger = array_filter($postingArray[self::SUPPLIER_ACCOUNT], function ($posting) use($vendorLedgerId, $vendorLedgerGroupId) {
                    return $posting['ledger_id'] == $vendorLedgerId && $posting['ledger_group_id'] === $vendorLedgerGroupId;
                });
                //Ledger found
                if (count($existingVendorLedger) > 0) {
                    $postingArray[self::SUPPLIER_ACCOUNT][0]['credit_amount'] += $tax -> ted_amount;
                } else { //Assign new ledger
                    array_push($postingArray[self::SUPPLIER_ACCOUNT], [
                        'ledger_id' => $vendorLedgerId,
                        'ledger_group_id' => $vendorLedgerGroupId,
                        'ledger_code' => $vendorLedger ?-> code,
                        'ledger_name' => $vendorLedger ?-> name,
                        'ledger_group_code' => $vendorLedgerGroup ?-> name,
                        'credit_amount' => $tax -> ted_amount,
                        'debit_amount' => 0,
                    ]);
                }
            }

            //EXPENSES
            $expenses = MrnExtraAmount::where('mrn_header_id', $document -> id) -> where('ted_type', "Expense") -> get();
            foreach ($expenses as $expense) {
                $expenseDetail = ExpenseMaster::find($expense -> ted_id);
                $expenseLedgerId = $expenseDetail ?-> expense_ledger_id; //MAKE IT DYNAMIC - 5
                $expenseLedgerGroupId = $expenseDetail ?-> expense_ledger_group_id; //MAKE IT DYNAMIC - 9
                $expenseLedger = Ledger::find($expenseLedgerId);
                $expenseLedgerGroup = Group::find($expenseLedgerGroupId);
                if (!isset($expenseLedger) || !isset($expenseLedgerGroup)) {
                    $ledgerErrorStatus = self::ERROR_PREFIX.'Expense Account not setup';
                    break;
                }
                $existingExpenseLedger = array_filter($postingArray[self::EXPENSE_ACCOUNT], function ($posting) use($expenseLedgerId, $expenseLedgerGroupId) {
                    return $posting['ledger_id'] == $expenseLedgerId && $posting['ledger_group_id'] === $expenseLedgerGroupId;
                });
                //Ledger found
                if (count($existingExpenseLedger) > 0) {
                    $postingArray[self::EXPENSE_ACCOUNT][0]['debit_amount'] += $expense -> ted_amount;
                } else { //Assign a new ledger
                    array_push($postingArray[self::EXPENSE_ACCOUNT], [
                        'ledger_id' => $expenseLedgerId,
                        'ledger_group_id' => $expenseLedgerGroupId,
                        'ledger_code' => $expenseLedger ?-> code,
                        'ledger_name' => $expenseLedger ?-> name,
                        'ledger_group_code' => $expenseLedgerGroup ?-> name,
                        'credit_amount' => 0,
                        'debit_amount' => $expense -> ted_amount,
                    ]);
                }
                //Expense for SUPPLIER ACCOUNT
                $existingVendorLedger = array_filter($postingArray[self::SUPPLIER_ACCOUNT], function ($posting) use($vendorLedgerId, $vendorLedgerGroupId) {
                    return $posting['ledger_id'] == $vendorLedgerId && $posting['ledger_group_id'] === $vendorLedgerGroupId;
                });
                //Ledger found
                if (count($existingVendorLedger) > 0) {
                    $postingArray[self::SUPPLIER_ACCOUNT][0]['credit_amount'] += $expense -> ted_amount;
                } else { //Assign new ledger
                    array_push($postingArray[self::EXPENSE_ACCOUNT], [
                        'ledger_id' => $vendorLedgerId,
                        'ledger_group_id' => $vendorLedgerGroupId,
                        'ledger_code' => $vendorLedger ?-> code,
                        'ledger_name' => $vendorLedger ?-> name,
                        'ledger_group_code' => $vendorLedgerGroup ?-> name,
                        'credit_amount' => $expense -> ted_amount,
                        'debit_amount' => 0,
                    ]);
                }
            }
        }
        //Seperate posting of Discount
        if ($discountSeperatePosting) {
            $discounts = MrnExtraAmount::where('mrn_header_id', $document -> id) -> where('ted_type', "Discount") -> get();
            foreach ($discounts as $discount) {
                $discountDetail = DiscountMaster::find($discount -> ted_id);
                $discountLedgerId = $discountDetail ?-> discount_ledger_id; //MAKE IT DYNAMIC
                $discountLedgerGroupId = $discountDetail ?-> discount_ledger_group_id; //MAKE IT DYNAMIC
                $discountLedger = Ledger::find($discountLedgerId);
                $discountLedgerGroup = Group::find($discountLedgerGroupId);
                if (!isset($discountLedger) || !isset($discountLedgerGroup)) {
                    $ledgerErrorStatus = self::ERROR_PREFIX.'Discount Account not setup';
                    break;
                }
                $existingDiscountLedger = array_filter($postingArray[self::DISCOUNT_ACCOUNT], function ($posting) use($discountLedgerId, $discountLedgerGroupId) {
                    return $posting['ledger_id'] == $discountLedgerId && $posting['ledger_group_id'] === $discountLedgerGroupId;
                });
                //Ledger found
                if (count($existingDiscountLedger) > 0) {
                    $postingArray[self::DISCOUNT_ACCOUNT][0]['credit_amount'] += $discount -> ted_amount;
                } else { //Assign a new ledger
                    array_push($postingArray[self::DISCOUNT_ACCOUNT], [
                        'ledger_id' => $discountLedgerId,
                        'ledger_group_id' => $discountLedgerGroupId,
                        'ledger_code' => $discountLedger ?-> code,
                        'ledger_name' => $discountLedger ?-> name,
                        'ledger_group_code' => $discountLedgerGroup ?-> name,
                        'debit_amount' => 0,
                        'credit_amount' => $discount -> ted_amount,
                    ]);
                }
            }
            // dd($postingArray);
        }

        //Check if All Legders exists and posting is properly set
        if ($ledgerErrorStatus) {
            return array(
                'status' => false,
                'message' => $ledgerErrorStatus,
                'data' => []
            );
        }
        //Check debit and credit tally
        foreach ($postingArray as $postAccount) {
            foreach ($postAccount as $postingValue) {
                $totalCreditAmount += $postingValue['credit_amount'];
                $totalDebitAmount += $postingValue['debit_amount'];
            }
        }
        // dd($totalDebitAmount, $totalCreditAmount);
        //Balance does not match
        if ($totalDebitAmount !== $totalCreditAmount) {
            return array(
                'status' => false,
                'message' => self::ERROR_PREFIX.'Credit Amount does not match Debit Amount',
                'data' => []
            );
        }
        //Get Header Details
        $book = Book::find($document -> book_id);
        $glPostingBookParam = OrganizationBookParameter::where('book_id', $book -> id) -> where('parameter_name', ServiceParametersHelper::GL_POSTING_SERIES_PARAM) -> first();
        // dd($glPostingBookParam);
        if (isset($glPostingBookParam)) {
            $glPostingBookId = $glPostingBookParam -> parameter_value[0];
        } else {
            return array(
                'status' => false,
                'message' => self::ERROR_PREFIX.'Financial Book Code is not specified',
                'data' => []
            );
        }
        $currency = Currency::find($document -> currency_id);
        $userData = Helper::userCheck();
        $voucherHeader = [
            'voucher_no' => $document -> document_number,
            'document_date' => $document -> document_date,
            'book_id' => $glPostingBookId,
            'date' => $document -> document_date,
            'amount' => $totalCreditAmount,
            'currency_id' => $document -> currency_id,
            'currency_code' => $document -> currency_code,
            'org_currency_id' => $document -> org_currency_id,
            'org_currency_code' => $document -> org_currency_code,
            'org_currency_exg_rate' => $document -> org_currency_exg_rate,
            'comp_currency_id' => $document -> comp_currency_id,
            'comp_currency_code' => $document -> comp_currency_code,
            'comp_currency_exg_rate' => $document -> comp_currency_exg_rate,
            'group_currency_id' => $document -> group_currency_id,
            'group_currency_code' => $document -> group_currency_code,
            'group_currency_exg_rate' => $document -> group_currency_exg_rate,
            'reference_service' => $book ?-> service ?-> alias,
            'reference_doc_id' => $document -> id,
            'group_id' => $document -> group_id,
            'company_id' => $document -> company_id,
            'organization_id' => $document -> organization_id,
            'voucherable_type' => $userData['user_type'],
            'voucherable_id' => $userData['user_id'],
            'document_status' => ConstantHelper::APPROVED,
            'approvalLevel' => $document -> approval_level
        ];
        $voucherDetails = [];
        foreach ($postingArray as $entryType => $postDetails) {
            foreach ($postDetails as $post) {
                array_push($voucherDetails, [
                    'ledger_id' => $post['ledger_id'],
                    'ledger_parent_id' => $post['ledger_group_id'],
                    'debit_amt' => $post['debit_amount'],
                    'credit_amt' => $post['credit_amount'],
                    'debit_amt_org' => $post['debit_amount'] * $voucherHeader['org_currency_exg_rate'],
                    'credit_amt_org' => $post['credit_amount'] * $voucherHeader['org_currency_exg_rate'],
                    'debit_amt_comp' => $post['debit_amount'] * $voucherHeader['comp_currency_exg_rate'],
                    'credit_amt_comp' => $post['credit_amount'] * $voucherHeader['comp_currency_exg_rate'],
                    'debit_amt_group' => $post['debit_amount'] * $voucherHeader['group_currency_exg_rate'],
                    'credit_amt_group' => $post['credit_amount'] * $voucherHeader['group_currency_exg_rate'],
                    'entry_type' => $entryType,

                ]);
            }
        }
        return array(
            'status' => true,
            'message' => 'Posting Details found',
            'data' => [
                'voucher_header' => $voucherHeader,
                'voucher_details' => $voucherDetails,
                'document_date' => $document -> document_date,
                'ledgers' => $postingArray,
                'total_debit' => $totalDebitAmount,
                'total_credit' => $totalCreditAmount,
                'book_code' => $book ?-> book_code,
                'document_number' => $document -> document_number,
                'currency_code' => $currency ?-> short_name
            ]
        );
    }

    public static function pbVoucherDetails(int $documentId, string $type) : array
    {
        // dd($documentId);
        $document = PbHeader::find($documentId);
        if (!isset($document)) {
            return array(
                'status' => false,
                'message' => 'Document not found',
                'data' => []
            );
        }
        $postingArray = array(
            self::GRIR_ACCOUNT => [],
            self::TAX_ACCOUNT => [],
            self::EXPENSE_ACCOUNT => [],
            self::DISCOUNT_ACCOUNT => [],
            self::SUPPLIER_ACCOUNT => [],
        );
        //Assign Credit and Debit amount for tally check
        $totalCreditAmount = 0;
        $totalDebitAmount = 0;

        // Vendor Detail
        $vendor = Vendor::find($document -> vendor_id);
        $vendorLedgerId = $vendor -> ledger_id;
        $vendorLedgerGroupId = $vendor -> ledger_group_id;
        $vendorLedger = Ledger::find($vendorLedgerId);
        $vendorLedgerGroup = Group::find($vendorLedgerGroupId);

        //Status to check if all ledger entries were properly set
        $ledgerErrorStatus = null;

        $discountPostingParam = OrganizationBookParameter::where('book_id', $document -> book_id)
        -> where('parameter_name', ServiceParametersHelper::GL_SEPERATE_DISCOUNT_PARAM) -> first();
        if (isset($discountPostingParam)) {
            $discountSeperatePosting = $discountPostingParam -> parameter_value[0] === "yes" ? true : false;
        } else {
            $discountSeperatePosting = false;
        }
        foreach ($document -> items as $docItemKey => $docItem) {
            //Assign Item values
            $itemValue = ($docItem -> rate * $docItem -> accepted_qty);
            $itemTotalDiscount = $docItem -> header_discount_amount + $docItem -> discount_amount;
            $itemValueAfterDiscount = $itemValue - $itemTotalDiscount;

            //COGS SETUP
            $grirCreditAmount = $discountSeperatePosting ? $itemValue : $itemValueAfterDiscount;
            $grirLedgerDetails = AccountHelper::getGrLedgerGroupAndLedgerId($document -> organization_id, $docItem -> item_id, $document -> book_id);
            $grirLedgerId = $grirLedgerDetails -> first()['ledger_id'] ?? null;
            $grirLedgerGroupId = $grirLedgerDetails-> first()['ledger_group'] ?? null;
            $grirLedger = Ledger::find($grirLedgerId);
            $grirLedgerGroup = Group::find($grirLedgerGroupId);
            //LEDGER NOT FOUND
            if (!isset($grirLedger) || !isset($grirLedgerGroup)) {
                $ledgerErrorStatus = self::ERROR_PREFIX.'GR/IR Account not setup';
                break;
            }

            //Check for same ledger and group in SALES ACCOUNT
            $existingGrirLedger = array_filter($postingArray[self::GRIR_ACCOUNT], function ($posting) use($grirLedgerId, $grirLedgerGroupId) {
                return $posting['ledger_id'] == $grirLedgerId && $posting['ledger_group_id'] == $grirLedgerGroupId;
            });
            //Ledger found
            if (count($existingGrirLedger) > 0) {
                $postingArray[self::GRIR_ACCOUNT][0]['debit_amount'] +=  $grirCreditAmount;
            } else { //Assign a new ledger
                array_push($postingArray[self::GRIR_ACCOUNT], [
                    'ledger_id' => $grirLedgerId,
                    'ledger_group_id' => $grirLedgerGroupId,
                    'ledger_code' => $grirLedger ?-> code,
                    'ledger_name' => $grirLedger ?-> name,
                    'ledger_group_code' => $grirLedgerGroup ?-> name,
                    'credit_amount' => 0,
                    'debit_amount' => $grirCreditAmount
                ]);
            }

            //GRIR for SUPPLIER ACCOUNT
            $existingVendorLedger = array_filter($postingArray[self::SUPPLIER_ACCOUNT], function ($posting) use($vendorLedgerId, $vendorLedgerGroupId) {
                return $posting['ledger_id'] == $vendorLedgerId && $posting['ledger_group_id'] === $vendorLedgerGroupId;
            });
            //Ledger found
            if (count($existingVendorLedger) > 0) {
                $postingArray[self::SUPPLIER_ACCOUNT][0]['credit_amount'] += $itemValueAfterDiscount;
            } else { //Assign new ledger
                array_push($postingArray[self::SUPPLIER_ACCOUNT], [
                    'ledger_id' => $vendorLedgerId,
                    'ledger_group_id' => $vendorLedgerGroupId,
                    'ledger_code' => $vendorLedger ?-> code,
                    'ledger_name' => $vendorLedger ?-> name,
                    'ledger_group_code' => $vendorLedgerGroup ?-> name,
                    'credit_amount' => $itemValueAfterDiscount,
                    'debit_amount' => 0,
                ]);
            }
        }

        //TAXES ACCOUNT
        $taxes = PbTed::where('header_id', $document -> id) -> where('ted_type', "Tax") -> get();
        foreach ($taxes as $tax) {
            $taxDetail = TaxDetail::find($tax -> ted_id);
            $taxLedgerId = $taxDetail -> ledger_id ?? null; //MAKE IT DYNAMIC
            $taxLedgerGroupId = $taxDetail -> ledger_group_id ?? null; //MAKE IT DYNAMIC
            $taxLedger = Ledger::find($taxLedgerId);
            $taxLedgerGroup = Group::find($taxLedgerGroupId);
            if (!isset($taxLedger) || !isset($taxLedgerGroup)) {
                $ledgerErrorStatus = self::ERROR_PREFIX.'Tax Account not setup';
                break;
            }
            $existingTaxLedger = array_filter($postingArray[self::TAX_ACCOUNT], function ($posting) use($taxLedgerId, $taxLedgerGroupId) {
                return $posting['ledger_id'] == $taxLedgerId && $posting['ledger_group_id'] === $taxLedgerGroupId;
            });
            //Ledger found
            if (count($existingTaxLedger) > 0) {
                $postingArray[self::TAX_ACCOUNT][0]['debit_amount'] += $tax -> ted_amount;
            } else { //Assign a new ledger
                array_push($postingArray[self::TAX_ACCOUNT], [
                    'ledger_id' => $taxLedgerId,
                    'ledger_group_id' => $taxLedgerGroupId,
                    'ledger_code' => $taxLedger ?-> code,
                    'ledger_name' => $taxLedger ?-> name,
                    'ledger_group_code' => $taxLedgerGroup ?-> name,
                    'credit_amount' => 0,
                    'debit_amount' => $tax -> ted_amount,
                ]);
            }
            // dd($tax -> ted_amount);
            //Tax for SUPPLIER ACCOUNT
            $existingVendorLedger = array_filter($postingArray[self::SUPPLIER_ACCOUNT], function ($posting) use($vendorLedgerId, $vendorLedgerGroupId) {
                return $posting['ledger_id'] == $vendorLedgerId && $posting['ledger_group_id'] === $vendorLedgerGroupId;
            });
            //Ledger found
            if (count($existingVendorLedger) > 0) {
                $postingArray[self::SUPPLIER_ACCOUNT][0]['credit_amount'] += $tax -> ted_amount;
            } else { //Assign new ledger
                array_push($postingArray[self::SUPPLIER_ACCOUNT], [
                    'ledger_id' => $vendorLedgerId,
                    'ledger_group_id' => $vendorLedgerGroupId,
                    'ledger_code' => $vendorLedger ?-> code,
                    'ledger_name' => $vendorLedger ?-> name,
                    'ledger_group_code' => $vendorLedgerGroup ?-> name,
                    'credit_amount' => $tax -> ted_amount,
                    'debit_amount' => 0,
                ]);
            }
        }
        //EXPENSES
        $expenses = PbTed::where('header_id', $document -> id) -> where('ted_type', "Expense") -> get();
        foreach ($expenses as $expense) {
            $expenseDetail = ExpenseMaster::find($expense -> ted_id);
            $expenseLedgerId = $expenseDetail ?-> expense_ledger_id; //MAKE IT DYNAMIC - 5
            $expenseLedgerGroupId = $expenseDetail ?-> expense_ledger_group_id; //MAKE IT DYNAMIC - 9
            $expenseLedger = Ledger::find($expenseLedgerId);
            $expenseLedgerGroup = Group::find($expenseLedgerGroupId);
            if (!isset($expenseLedger) || !isset($expenseLedgerGroup)) {
                $ledgerErrorStatus = self::ERROR_PREFIX.'Expense Account not setup';
                break;
            }
            $existingExpenseLedger = array_filter($postingArray[self::EXPENSE_ACCOUNT], function ($posting) use($expenseLedgerId, $expenseLedgerGroupId) {
                return $posting['ledger_id'] == $expenseLedgerId && $posting['ledger_group_id'] === $expenseLedgerGroupId;
            });
            //Ledger found
            if (count($existingExpenseLedger) > 0) {
                $postingArray[self::EXPENSE_ACCOUNT][0]['debit_amount'] += $expense -> ted_amount;
            } else { //Assign a new ledger
                array_push($postingArray[self::EXPENSE_ACCOUNT], [
                    'ledger_id' => $expenseLedgerId,
                    'ledger_group_id' => $expenseLedgerGroupId,
                    'ledger_code' => $expenseLedger ?-> code,
                    'ledger_name' => $expenseLedger ?-> name,
                    'ledger_group_code' => $expenseLedgerGroup ?-> name,
                    'credit_amount' => 0,
                    'debit_amount' => $expense -> ted_amount,
                ]);
            }
            //Expense for SUPPLIER ACCOUNT
            $existingVendorLedger = array_filter($postingArray[self::SUPPLIER_ACCOUNT], function ($posting) use($vendorLedgerId, $vendorLedgerGroupId) {
                return $posting['ledger_id'] == $vendorLedgerId && $posting['ledger_group_id'] === $vendorLedgerGroupId;
            });
            //Ledger found
            if (count($existingVendorLedger) > 0) {
                $postingArray[self::SUPPLIER_ACCOUNT][0]['credit_amount'] += $expense -> ted_amount;
            } else { //Assign new ledger
                array_push($postingArray[self::EXPENSE_ACCOUNT], [
                    'ledger_id' => $vendorLedgerId,
                    'ledger_group_id' => $vendorLedgerGroupId,
                    'ledger_code' => $vendorLedger ?-> code,
                    'ledger_name' => $vendorLedger ?-> name,
                    'ledger_group_code' => $vendorLedgerGroup ?-> name,
                    'credit_amount' => $expense -> ted_amount,
                    'debit_amount' => 0,
                ]);
            }
        }
        //Seperate posting of Discount
        if ($discountSeperatePosting) {
            $discounts = PbTed::where('header_id', $document -> id) -> where('ted_type', "Discount") -> get();
            foreach ($discounts as $discount) {
                $discountDetail = DiscountMaster::find($discount -> ted_id);
                $discountLedgerId = $discountDetail ?-> discount_ledger_id; //MAKE IT DYNAMIC
                $discountLedgerGroupId = $discountDetail ?-> discount_ledger_group_id; //MAKE IT DYNAMIC
                $discountLedger = Ledger::find($discountLedgerId);
                $discountLedgerGroup = Group::find($discountLedgerGroupId);
                if (!isset($discountLedger) || !isset($discountLedgerGroup)) {
                    $ledgerErrorStatus = self::ERROR_PREFIX.'Discount Account not setup';
                    break;
                }
                $existingDiscountLedger = array_filter($postingArray[self::DISCOUNT_ACCOUNT], function ($posting) use($discountLedgerId, $discountLedgerGroupId) {
                    return $posting['ledger_id'] == $discountLedgerId && $posting['ledger_group_id'] === $discountLedgerGroupId;
                });
                //Ledger found
                if (count($existingDiscountLedger) > 0) {
                    $postingArray[self::DISCOUNT_ACCOUNT][0]['credit_amount'] += $discount -> ted_amount;
                } else { //Assign a new ledger
                    array_push($postingArray[self::DISCOUNT_ACCOUNT], [
                        'ledger_id' => $discountLedgerId,
                        'ledger_group_id' => $discountLedgerGroupId,
                        'ledger_code' => $discountLedger ?-> code,
                        'ledger_name' => $discountLedger ?-> name,
                        'ledger_group_code' => $discountLedgerGroup ?-> name,
                        'debit_amount' => 0,
                        'credit_amount' => $discount -> ted_amount,
                    ]);
                }
            }
        }

        //Check if All Legders exists and posting is properly set
        if ($ledgerErrorStatus) {
            return array(
                'status' => false,
                'message' => $ledgerErrorStatus,
                'data' => []
            );
        }
        //Check debit and credit tally
        foreach ($postingArray as $postAccount) {
            foreach ($postAccount as $postingValue) {
                $totalCreditAmount += $postingValue['credit_amount'];
                $totalDebitAmount += $postingValue['debit_amount'];
            }
        }
        // dd($totalDebitAmount, $totalCreditAmount);
        //Balance does not match
        if ($totalDebitAmount !== $totalCreditAmount) {
            return array(
                'status' => false,
                'message' => self::ERROR_PREFIX.'Credit Amount does not match Debit Amount',
                'data' => []
            );
        }
        //Get Header Details
        $book = Book::find($document -> book_id);
        $glPostingBookParam = OrganizationBookParameter::where('book_id', $book -> id) -> where('parameter_name', ServiceParametersHelper::GL_POSTING_SERIES_PARAM) -> first();
        // dd($glPostingBookParam);
        if (isset($glPostingBookParam)) {
            $glPostingBookId = $glPostingBookParam -> parameter_value[0];
        } else {
            return array(
                'status' => false,
                'message' => self::ERROR_PREFIX.'Financial Book Code is not specified',
                'data' => []
            );
        }
        $currency = Currency::find($document -> currency_id);
        $userData = Helper::userCheck();
        $voucherHeader = [
            'voucher_no' => $document -> document_number,
            'document_date' => $document -> document_date,
            'book_id' => $glPostingBookId,
            'date' => $document -> document_date,
            'amount' => $totalCreditAmount,
            'currency_id' => $document -> currency_id,
            'currency_code' => $document -> currency_code,
            'org_currency_id' => $document -> org_currency_id,
            'org_currency_code' => $document -> org_currency_code,
            'org_currency_exg_rate' => $document -> org_currency_exg_rate,
            'comp_currency_id' => $document -> comp_currency_id,
            'comp_currency_code' => $document -> comp_currency_code,
            'comp_currency_exg_rate' => $document -> comp_currency_exg_rate,
            'group_currency_id' => $document -> group_currency_id,
            'group_currency_code' => $document -> group_currency_code,
            'group_currency_exg_rate' => $document -> group_currency_exg_rate,
            'reference_service' => $book ?-> service ?-> alias,
            'reference_doc_id' => $document -> id,
            'group_id' => $document -> group_id,
            'company_id' => $document -> company_id,
            'organization_id' => $document -> organization_id,
            'voucherable_type' => $userData['user_type'],
            'voucherable_id' => $userData['user_id'],
            'document_status' => ConstantHelper::APPROVED,
            'approvalLevel' => $document -> approval_level
        ];
        $voucherDetails = [];
        foreach ($postingArray as $entryType => $postDetails) {
            foreach ($postDetails as $post) {
                array_push($voucherDetails, [
                    'ledger_id' => $post['ledger_id'],
                    'ledger_parent_id' => $post['ledger_group_id'],
                    'debit_amt' => $post['debit_amount'],
                    'credit_amt' => $post['credit_amount'],
                    'debit_amt_org' => $post['debit_amount'] * $voucherHeader['org_currency_exg_rate'],
                    'credit_amt_org' => $post['credit_amount'] * $voucherHeader['org_currency_exg_rate'],
                    'debit_amt_comp' => $post['debit_amount'] * $voucherHeader['comp_currency_exg_rate'],
                    'credit_amt_comp' => $post['credit_amount'] * $voucherHeader['comp_currency_exg_rate'],
                    'debit_amt_group' => $post['debit_amount'] * $voucherHeader['group_currency_exg_rate'],
                    'credit_amt_group' => $post['credit_amount'] * $voucherHeader['group_currency_exg_rate'],
                    'entry_type' => $entryType,

                ]);
            }
        }
        return array(
            'status' => true,
            'message' => 'Posting Details found',
            'data' => [
                'voucher_header' => $voucherHeader,
                'voucher_details' => $voucherDetails,
                'document_date' => $document -> document_date,
                'ledgers' => $postingArray,
                'total_debit' => $totalDebitAmount,
                'total_credit' => $totalCreditAmount,
                'book_code' => $book ?-> book_code,
                'document_number' => $document -> document_number,
                'currency_code' => $currency ?-> short_name
            ]
        );
    }

    public static function expenseAdviseVoucherDetails(int $documentId, string $type) : array
    {
        // dd($documentId);
        $document = ExpenseHeader::find($documentId);
        if (!isset($document)) {
            return array(
                'status' => false,
                'message' => 'Document not found',
                'data' => []
            );
        }
        // dd($document->toArray());
        $postingArray = array(
            self::STOCK_ACCOUNT => [],
            self::TAX_ACCOUNT => [],
            self::EXPENSE_ACCOUNT => [],
            self::DISCOUNT_ACCOUNT => [],
            self::GRIR_ACCOUNT => [],
            self::SUPPLIER_ACCOUNT => [],
        );
        //Assign Credit and Debit amount for tally check
        $totalCreditAmount = 0;
        $totalDebitAmount = 0;

        // Vendor Detail
        $vendor = Vendor::find($document -> vendor_id);
        $vendorLedgerId = $vendor -> ledger_id;
        $vendorLedgerGroupId = $vendor -> ledger_group_id;
        $vendorLedger = Ledger::find($vendorLedgerId);
        $vendorLedgerGroup = Group::find($vendorLedgerGroupId);

        //Vendor Ledger account not found
        if (!isset($vendorLedger) || !isset($vendorLedgerGroup)) {
            return array(
                'status' => false,
                'message' => self::ERROR_PREFIX.'Vendor Account not setup',
                'data' => []
            );
        }

        $discountPostingParam = OrganizationBookParameter::where('book_id', $document -> book_id)
        -> where('parameter_name', ServiceParametersHelper::GL_SEPERATE_DISCOUNT_PARAM) -> first();
        if (isset($discountPostingParam)) {
            $discountSeperatePosting = $discountPostingParam -> parameter_value[0] === "yes" ? true : false;
        } else {
            $discountSeperatePosting = false;
        }

        //Status to check if all ledger entries were properly set
        $ledgerErrorStatus = null;
        //COGS SETUP
        foreach ($document -> items as $docItemKey => $docItem) {
            $itemValue = ($docItem -> rate * $docItem -> accepted_qty);
            $itemTotalDiscount = $docItem -> header_discount_amount + $docItem -> discount_amount;
            $itemValueAfterDiscount = $itemValue - $itemTotalDiscount;
            $stockDebitAmount = $discountSeperatePosting ? $itemValue : $itemValueAfterDiscount;

            $stockLedgerDetails = AccountHelper::getStockLedgerGroupAndLedgerId($document -> organization_id, $docItem -> item_id, $document -> book_id);
            // dd($stockLedgerDetails);
            $stockLedgerId = $stockLedgerDetails -> first()['ledger_id'] ?? null;
            $stockLedgerGroupId = $stockLedgerDetails-> first()['ledger_group'] ?? null;
            // dd($stockLedgerDetails -> first()['ledger_id']);
            $stockLedger = Ledger::find($stockLedgerId);
            $stockLedgerGroup = Group::find($stockLedgerGroupId);
            //LEDGER NOT FOUND
            if (!isset($stockLedger) || !isset($stockLedgerGroup)) {
                $ledgerErrorStatus = self::ERROR_PREFIX.'Stock Account not setup';
                break;
            }

            //Check for same ledger and group in SALES ACCOUNT
            $existingstockLedger = array_filter($postingArray[self::STOCK_ACCOUNT], function ($posting) use($stockLedgerId, $stockLedgerGroupId) {
                return $posting['ledger_id'] == $stockLedgerId && $posting['ledger_group_id'] == $stockLedgerGroupId;
            });
            //Ledger found
            if (count($existingstockLedger) > 0) {
                $postingArray[self::STOCK_ACCOUNT][0]['debit_amount'] +=  $stockDebitAmount;
            } else { //Assign a new ledger
                array_push($postingArray[self::STOCK_ACCOUNT], [
                    'ledger_id' => $stockLedgerId,
                    'ledger_group_id' => $stockLedgerGroupId,
                    'ledger_code' => $stockLedger ?-> code,
                    'ledger_name' => $stockLedger ?-> name,
                    'ledger_group_code' => $stockLedgerGroup ?-> name,
                    'credit_amount' => 0,
                    'debit_amount' => $stockDebitAmount
                ]);
            }

            //Stock for SUPPLIER ACCOUNT
            $existingVendorLedger = array_filter($postingArray[self::SUPPLIER_ACCOUNT], function ($posting) use($vendorLedgerId, $vendorLedgerGroupId) {
                return $posting['ledger_id'] == $vendorLedgerId && $posting['ledger_group_id'] === $vendorLedgerGroupId;
            });
            //Ledger found
            if (count($existingVendorLedger) > 0) {
                $postingArray[self::SUPPLIER_ACCOUNT][0]['credit_amount'] += $itemValueAfterDiscount;
            } else { //Assign new ledger
                array_push($postingArray[self::SUPPLIER_ACCOUNT], [
                    'ledger_id' => $vendorLedgerId,
                    'ledger_group_id' => $vendorLedgerGroupId,
                    'ledger_code' => $vendorLedger ?-> code,
                    'ledger_name' => $vendorLedger ?-> name,
                    'ledger_group_code' => $vendorLedgerGroup ?-> name,
                    'credit_amount' => $itemValueAfterDiscount,
                    'debit_amount' => 0
                ]);
            }
        }

        //TAXES ACCOUNT
        $taxes = ExpenseTed::where('expense_header_id', $document -> id) -> where('ted_type', "Tax") -> get();
        foreach ($taxes as $tax) {
            $taxDetail = TaxDetail::find($tax -> ted_id);
            $taxLedgerId = $taxDetail -> ledger_id ?? null; //MAKE IT DYNAMIC
            $taxLedgerGroupId = $taxDetail -> ledger_group_id ?? null; //MAKE IT DYNAMIC
            $taxLedger = Ledger::find($taxLedgerId);
            $taxLedgerGroup = Group::find($taxLedgerGroupId);
            if (!isset($taxLedger) || !isset($taxLedgerGroup)) {
                $ledgerErrorStatus = self::ERROR_PREFIX.'Tax Account not setup';
                break;
            }
            $existingTaxLedger = array_filter($postingArray[self::TAX_ACCOUNT], function ($posting) use($taxLedgerId, $taxLedgerGroupId) {
                return $posting['ledger_id'] == $taxLedgerId && $posting['ledger_group_id'] === $taxLedgerGroupId;
            });
            //Ledger found
            if (count($existingTaxLedger) > 0) {
                $postingArray[self::TAX_ACCOUNT][0]['debit_amount'] += $tax -> ted_amount;
            } else { //Assign a new ledger
                array_push($postingArray[self::TAX_ACCOUNT], [
                    'ledger_id' => $taxLedgerId,
                    'ledger_group_id' => $taxLedgerGroupId,
                    'ledger_code' => $taxLedger ?-> code,
                    'ledger_name' => $taxLedger ?-> name,
                    'ledger_group_code' => $taxLedgerGroup ?-> name,
                    'credit_amount' => 0,
                    'debit_amount' => $tax -> ted_amount,
                ]);
            }
            // dd($tax -> ted_amount);
            //Tax for SUPPLIER ACCOUNT
            $existingVendorLedger = array_filter($postingArray[self::SUPPLIER_ACCOUNT], function ($posting) use($vendorLedgerId, $vendorLedgerGroupId) {
                return $posting['ledger_id'] == $vendorLedgerId && $posting['ledger_group_id'] === $vendorLedgerGroupId;
            });
            //Ledger found
            if (count($existingVendorLedger) > 0) {
                $postingArray[self::SUPPLIER_ACCOUNT][0]['credit_amount'] += $tax -> ted_amount;
            } else { //Assign new ledger
                array_push($postingArray[self::SUPPLIER_ACCOUNT], [
                    'ledger_id' => $vendorLedgerId,
                    'ledger_group_id' => $vendorLedgerGroupId,
                    'ledger_code' => $vendorLedger ?-> code,
                    'ledger_name' => $vendorLedger ?-> name,
                    'ledger_group_code' => $vendorLedgerGroup ?-> name,
                    'credit_amount' => $tax -> ted_amount,
                    'debit_amount' => 0,
                ]);
            }
        }

        //EXPENSES
        $expenses = ExpenseTed::where('expense_header_id', $document -> id) -> where('ted_type', "Expense") -> get();
        foreach ($expenses as $expense) {
            $expenseDetail = ExpenseMaster::find($expense -> ted_id);
            $expenseLedgerId = $expenseDetail ?-> expense_ledger_id; //MAKE IT DYNAMIC - 5
            $expenseLedgerGroupId = $expenseDetail ?-> expense_ledger_group_id; //MAKE IT DYNAMIC - 9
            $expenseLedger = Ledger::find($expenseLedgerId);
            $expenseLedgerGroup = Group::find($expenseLedgerGroupId);
            if (!isset($expenseLedger) || !isset($expenseLedgerGroup)) {
                $ledgerErrorStatus = self::ERROR_PREFIX.'Expense Account not setup';
                break;
            }
            $existingExpenseLedger = array_filter($postingArray[self::EXPENSE_ACCOUNT], function ($posting) use($expenseLedgerId, $expenseLedgerGroupId) {
                return $posting['ledger_id'] == $expenseLedgerId && $posting['ledger_group_id'] === $expenseLedgerGroupId;
            });
            //Ledger found
            if (count($existingExpenseLedger) > 0) {
                $postingArray[self::EXPENSE_ACCOUNT][0]['debit_amount'] += $expense -> ted_amount;
            } else { //Assign a new ledger
                array_push($postingArray[self::EXPENSE_ACCOUNT], [
                    'ledger_id' => $expenseLedgerId,
                    'ledger_group_id' => $expenseLedgerGroupId,
                    'ledger_code' => $expenseLedger ?-> code,
                    'ledger_name' => $expenseLedger ?-> name,
                    'ledger_group_code' => $expenseLedgerGroup ?-> name,
                    'credit_amount' => 0,
                    'debit_amount' => $expense -> ted_amount,
                ]);
            }
            //Expense for SUPPLIER ACCOUNT
            $existingVendorLedger = array_filter($postingArray[self::SUPPLIER_ACCOUNT], function ($posting) use($vendorLedgerId, $vendorLedgerGroupId) {
                return $posting['ledger_id'] == $vendorLedgerId && $posting['ledger_group_id'] === $vendorLedgerGroupId;
            });
            //Ledger found
            if (count($existingVendorLedger) > 0) {
                $postingArray[self::SUPPLIER_ACCOUNT][0]['credit_amount'] += $expense -> ted_amount;
            } else { //Assign new ledger
                array_push($postingArray[self::EXPENSE_ACCOUNT], [
                    'ledger_id' => $vendorLedgerId,
                    'ledger_group_id' => $vendorLedgerGroupId,
                    'ledger_code' => $vendorLedger ?-> code,
                    'ledger_name' => $vendorLedger ?-> name,
                    'ledger_group_code' => $vendorLedgerGroup ?-> name,
                    'credit_amount' => $expense -> ted_amount,
                    'debit_amount' => 0,
                ]);
            }
        }
        //Seperate posting of Discount
        if ($discountSeperatePosting) {
            $discounts = ExpenseTed::where('expense_header_id', $document -> id) -> where('ted_type', "Discount") -> get();
            foreach ($discounts as $discount) {
                $discountDetail = DiscountMaster::find($discount -> ted_id);
                $discountLedgerId = $discountDetail ?-> discount_ledger_id; //MAKE IT DYNAMIC
                $discountLedgerGroupId = $discountDetail ?-> discount_ledger_group_id; //MAKE IT DYNAMIC
                $discountLedger = Ledger::find($discountLedgerId);
                $discountLedgerGroup = Group::find($discountLedgerGroupId);
                if (!isset($discountLedger) || !isset($discountLedgerGroup)) {
                    $ledgerErrorStatus = self::ERROR_PREFIX.'Discount Account not setup';
                    break;
                }
                $existingDiscountLedger = array_filter($postingArray[self::DISCOUNT_ACCOUNT], function ($posting) use($discountLedgerId, $discountLedgerGroupId) {
                    return $posting['ledger_id'] == $discountLedgerId && $posting['ledger_group_id'] === $discountLedgerGroupId;
                });
                //Ledger found
                if (count($existingDiscountLedger) > 0) {
                    $postingArray[self::DISCOUNT_ACCOUNT][0]['credit_amount'] += $discount -> ted_amount;
                } else { //Assign a new ledger
                    array_push($postingArray[self::DISCOUNT_ACCOUNT], [
                        'ledger_id' => $discountLedgerId,
                        'ledger_group_id' => $discountLedgerGroupId,
                        'ledger_code' => $discountLedger ?-> code,
                        'ledger_name' => $discountLedger ?-> name,
                        'ledger_group_code' => $discountLedgerGroup ?-> name,
                        'debit_amount' => 0,
                        'credit_amount' => $discount -> ted_amount,
                    ]);
                }
            }
            // dd($postingArray);
        }

        //Check if All Legders exists and posting is properly set
        if ($ledgerErrorStatus) {
            return array(
                'status' => false,
                'message' => $ledgerErrorStatus,
                'data' => []
            );
        }
        //Check debit and credit tally
        foreach ($postingArray as $postAccount) {
            foreach ($postAccount as $postingValue) {
                $totalCreditAmount += $postingValue['credit_amount'];
                $totalDebitAmount += $postingValue['debit_amount'];
            }
        }
        // dd($totalDebitAmount, $totalCreditAmount);
        //Balance does not match
        if ($totalDebitAmount !== $totalCreditAmount) {
            return array(
                'status' => false,
                'message' => self::ERROR_PREFIX.'Credit Amount does not match Debit Amount',
                'data' => []
            );
        }
        //Get Header Details
        $book = Book::find($document -> book_id);
        $glPostingBookParam = OrganizationBookParameter::where('book_id', $book -> id) -> where('parameter_name', ServiceParametersHelper::GL_POSTING_SERIES_PARAM) -> first();
        if (isset($glPostingBookParam)) {
            $glPostingBookId = $glPostingBookParam -> parameter_value[0];
        } else {
            return array(
                'status' => false,
                'message' => self::ERROR_PREFIX.'Financial Book Code is not specified',
                'data' => []
            );
        }
        $currency = Currency::find($document -> currency_id);
        $userData = Helper::userCheck();
        $voucherHeader = [
            'voucher_no' => $document -> document_number,
            'document_date' => $document -> document_date,
            'book_id' => $glPostingBookId,
            'date' => $document -> document_date,
            'amount' => $totalCreditAmount,
            'currency_id' => $document -> currency_id,
            'currency_code' => $document -> currency_code,
            'org_currency_id' => $document -> org_currency_id,
            'org_currency_code' => $document -> org_currency_code,
            'org_currency_exg_rate' => $document -> org_currency_exg_rate,
            'comp_currency_id' => $document -> comp_currency_id,
            'comp_currency_code' => $document -> comp_currency_code,
            'comp_currency_exg_rate' => $document -> comp_currency_exg_rate,
            'group_currency_id' => $document -> group_currency_id,
            'group_currency_code' => $document -> group_currency_code,
            'group_currency_exg_rate' => $document -> group_currency_exg_rate,
            'reference_service' => $book ?-> service ?-> alias,
            'reference_doc_id' => $document -> id,
            'group_id' => $document -> group_id,
            'company_id' => $document -> company_id,
            'organization_id' => $document -> organization_id,
            'voucherable_type' => $userData['user_type'],
            'voucherable_id' => $userData['user_id'],
            'document_status' => ConstantHelper::APPROVED,
            'approvalLevel' => $document -> approval_level
        ];
        $voucherDetails = [];
        foreach ($postingArray as $entryType => $postDetails) {
            foreach ($postDetails as $post) {
                array_push($voucherDetails, [
                    'ledger_id' => $post['ledger_id'],
                    'ledger_parent_id' => $post['ledger_group_id'],
                    'debit_amt' => $post['debit_amount'],
                    'credit_amt' => $post['credit_amount'],
                    'debit_amt_org' => $post['debit_amount'] * $voucherHeader['org_currency_exg_rate'],
                    'credit_amt_org' => $post['credit_amount'] * $voucherHeader['org_currency_exg_rate'],
                    'debit_amt_comp' => $post['debit_amount'] * $voucherHeader['comp_currency_exg_rate'],
                    'credit_amt_comp' => $post['credit_amount'] * $voucherHeader['comp_currency_exg_rate'],
                    'debit_amt_group' => $post['debit_amount'] * $voucherHeader['group_currency_exg_rate'],
                    'credit_amt_group' => $post['credit_amount'] * $voucherHeader['group_currency_exg_rate'],
                    'entry_type' => $entryType,

                ]);
            }
        }
        return array(
            'status' => true,
            'message' => 'Posting Details found',
            'data' => [
                'voucher_header' => $voucherHeader,
                'voucher_details' => $voucherDetails,
                'document_date' => $document -> document_date,
                'ledgers' => $postingArray,
                'total_debit' => $totalDebitAmount,
                'total_credit' => $totalCreditAmount,
                'book_code' => $book ?-> book_code,
                'document_number' => $document -> document_number,
                'currency_code' => $currency ?-> short_name
            ]
        );
    }

public static function receiptInvoiceVoucherDetails(int $documentId, string $remarks) : array
    {
        $accountSetup = isset(self::SERVICE_POSTING_MAPPING[ConstantHelper::RECEIPTS_SERVICE_ALIAS])? self::SERVICE_POSTING_MAPPING[ConstantHelper::RECEIPTS_SERVICE_ALIAS] : [];
        if (!isset($accountSetup) || count($accountSetup) == 0) {
            return array(
                'status' => false,
                'message' => 'Account Setup not found',
                'data' => []
            );
        }
        $document = PaymentVoucher::find($documentId);
        $vendors = $document->details;
        $vocuherdata=$document;
        if (!isset($document)) {
            return array(
                'status' => false,
                'message' => 'Document not found',
                'data' => []
            );
        }
        //Make array according to setup
        $postingArray = array(
            self::PAYMENT_ACCOUNT => [],
            self::VENDOR_ACCOUNT => [],
        );
        $totalCreditAmount = 0;
        $totalDebitAmount = 0;

        $ledgerErrorStatus = null;

        if(!empty($vocuherdata))
        {
            $BankLedgerId = $vocuherdata->ledger_id;
            $BankLedgerGroupId = $vocuherdata->ledger_group_id;
            $BankLedger = Ledger::find($BankLedgerId);
            $BankLedgerGroup = Group::find($BankLedgerGroupId);

        }

        if (!isset($BankLedger) || !isset($BankLedgerGroup)) {
            return array(
                'status' => false,
                'message' => 'Bank Ledger not setup',
                'data' => []
            );
        }

            array_push($postingArray[self::PAYMENT_ACCOUNT], [
                'ledger_id' => $vocuherdata->bank->ledger_id,
                'ledger_group_id' => $vocuherdata->bank->ledger_id,
                'ledger_code' => $BankLedger ?-> code,
                'ledger_name' => $BankLedger ?-> name,
                'ledger_group_code' => $BankLedgerGroup ?-> name,
                'debit_amount' => $vocuherdata->amount,
                'credit_amount' => 0
            ]);
            foreach ($vendors as $vendor) {

        if(!empty($vendor))
        {
            $VendorLedgerId = $vendor->party->ledger_id;
            $VendorLedgerGroupId = $vendor->party->ledger_group_id;
            $VendorLedger = Ledger::find($VendorLedgerId);
            $VendorLedgerGroup = Group::find($VendorLedgerGroupId);

        }

        if (!isset($VendorLedger) || !isset($VendorLedgerGroup)) {
            return array(
                'status' => false,
                'message' => 'Vendor Ledger not setup',
                'data' => []
            );
        }
            array_push($postingArray[self::VENDOR_ACCOUNT], [
                'ledger_id' => $VendorLedgerId,
                'ledger_group_id' => $VendorLedgerGroupId,
                'ledger_code' => $VendorLedger?->code,
                'ledger_name' => $VendorLedger?->name,
                'ledger_group_code' => $VendorLedgerGroup?->name,
                'credit_amount' => $vendor->currentAmount,
                'debit_amount' => 0,
            ]);
}



        //Check if All Legders exists and posting is properly set
        if ($ledgerErrorStatus) {
            return array(
                'status' => false,
                'message' => $ledgerErrorStatus,
                'data' => []
            );
        }


        //Check debit and credit tally
        foreach ($postingArray as $postAccount) {
            foreach ($postAccount as $postingValue) {

                $totalDebitAmount += $postingValue['debit_amount'];
                $totalCreditAmount += $postingValue['credit_amount'];
            }
        }
        //Balance does not match
        if ($totalDebitAmount !== $totalCreditAmount) {
            return array(
                'status' => false,
                'message' => 'Credit Amount does not match Debit Amount',
                'data' => []
            );
        }
        //Get Header Details
        $book = Book::find($document->book_id);
        $glPostingBookParam = OrganizationBookParameter::where('book_id', $book->id)->where('parameter_name', ServiceParametersHelper::GL_POSTING_SERIES_PARAM)->first();
        if (isset($glPostingBookParam)) {
            $glPostingBookId = $glPostingBookParam -> parameter_value[0];
        } else {
            return array(
                'status' => false,
                'message' => 'Financial Book Code is not specified',
                'data' => []
            );
        }

        $currdata = OrganizationCompany::where('id',$document->company_id)->first();
        $excurrdata = CurrencyExchange::where('from_currency_id',$currdata->currency_id)->first();
        $currency = Currency::find($currdata->currency_id);

        $userData = Helper::userCheck();

        $booksdata = Book::where('book_name','JOURNAL_VOUCHER')->first();
        $numberPatternData = Helper::generateDocumentNumberNew($booksdata->id, $document->document_date);
        if (!isset($numberPatternData)) {
            return response()->json([
                'message' => "Invalid Book",
                'error' => "",
            ], 422);
        }
        $document_number = $numberPatternData['document_number'] ? $numberPatternData['document_number'] : null;

        $voucherHeader = [
            'voucher_no' => $document_number,
            'voucher_name' => $booksdata->book_code,
            'doc_prefix' => $numberPatternData['prefix'],
            'doc_suffix' => $numberPatternData['suffix'],
            'doc_no' => $numberPatternData['doc_no'],
            'doc_reset_pattern' => $numberPatternData['reset_pattern'],
            'document_date' => $document->document_date,
            'book_id' => $booksdata->id,
            'book_type_id' => $booksdata->org_service_id,
            'date' => $document->document_date,
            'amount' => $totalCreditAmount,
            'currency_id' => $currdata->currency_id,
            'currency_code' => $currdata->currency_code,
            'org_currency_id' => $currdata->currency_id,
            'org_currency_code' => $currdata->currency_code,
            'org_currency_exg_rate' => $excurrdata->exchange_rate,
            'comp_currency_id' => $currdata->currency_id, // Missing comma added here
            'comp_currency_code' => $currdata->currency_code,
            'comp_currency_exg_rate' => $excurrdata->exchange_rate,
            'group_currency_id' => $currdata->currency_id,
            'group_currency_code' => $currdata->currency_code,
            'group_currency_exg_rate' => $excurrdata->exchange_rate,
            'reference_service' => $book?->service?->alias,
            'reference_doc_id' => $document->id,
            'group_id' => $document->group_id,
            'company_id' => $document->company_id,
            'organization_id' => $document->organization_id,
            'voucherable_type' => $userData['user_type'],
            'voucherable_id' => $userData['user_id'],
            'approvalStatus' => ConstantHelper::APPROVED,
            'document_status' => ConstantHelper::APPROVED,
            'approvalLevel' => $document->approval_level,
            'remarks'=>$remarks,
        ];

       $voucherDetails = [];
       foreach ($postingArray as $entryType => $postDetails) {
        foreach ($postDetails as $post) {
            array_push($voucherDetails, [
                'ledger_id' => $post['ledger_id'],
                'group_id' => $document->group_id,
                'company_id' => $document->company_id,
                'organization_id'=>$document->organization_id,
                'ledger_parent_id' => $post['ledger_group_id'],
                'debit_amt' => $post['debit_amount'],
                'credit_amt' => $post['credit_amount'],
                'debit_amt_org' => $post['debit_amount'] * $voucherHeader['org_currency_exg_rate'],
                'credit_amt_org' => $post['credit_amount'] * $voucherHeader['org_currency_exg_rate'],
                'debit_amt_comp' => $post['debit_amount'] * $voucherHeader['comp_currency_exg_rate'],
                'credit_amt_comp' => $post['credit_amount'] * $voucherHeader['comp_currency_exg_rate'],
                'debit_amt_group' => $post['debit_amount'] * $voucherHeader['group_currency_exg_rate'],
                'credit_amt_group' => $post['credit_amount'] * $voucherHeader['group_currency_exg_rate'],
                'entry_type' => $entryType,
            ]);
        }
       }
        return array(
            'status' => true,
            'message' => 'Posting Details found',
            'data' => [
                'voucher_header' => $voucherHeader,
                'voucher_details' => $voucherDetails,
                'document_date' => $document->created_at,
                'ledgers' => $postingArray,
                'total_debit' => $totalDebitAmount,
                'total_credit' => $totalCreditAmount,
                'book_code' => $booksdata ?-> book_code,
                'document_number' =>$document_number,
                'currency_code' => $currency ?-> short_name
            ]
        );
    }

public static function receiptVoucherPosting(int $bookId, int $documentId, string $type,string $remarks) : array|bool
    {
        //Check Book
        $book = Book::find($bookId);
        if (!isset($book)) {
            return array(
                'status' => false,
                'message' => 'Book not found',
                'data' => []
            );
        }
        //Check Service
        $service = Service::find($book -> service_id);
        if (!isset($service)) {
            return array(
                'status' => false,
                'message' => 'Service not found',
                'data' => []
            );
        }
        $isFinanceVoucherDefined = ServiceParametersHelper::getFinancialServiceAlias($service -> alias);
        if (!isset($isFinanceVoucherDefined)) {
            return array(
                'status' => false,
                'message' => '',
                'data' => []
            );
        }
        //Check Posting parameters
        $financialPostParam = OrganizationBookParameter::where('book_id', $book->id)->where('parameter_name', ServiceParametersHelper::GL_POSTING_REQUIRED_PARAM)->first();
        if (!isset($financialPostParam)) {
            return array(
                'status' => false,
                'message' => 'GL Posting Parameter not specified',
                'data' => []
            );
        }
        $isPostingRequired = (($financialPostParam->parameter_value[0] ?? '') === 'yes' ? true : false);
        if (!$isPostingRequired) {
            return array(
                'status' => false,
                'message' => '',
                'data' => []
            );
        }

        //Call helpers according to service
        $serviceAlias = $service -> alias;
        if ($serviceAlias === ConstantHelper::RECEIPTS_SERVICE_ALIAS) {
            $entries = self::receiptInvoiceVoucherDetails($documentId, $remarks);
            if (!$entries['status']) {
                return array(
                    'status' => false,
                    'message' => $entries['message'],
                    'data' => []
                );
            }
        } else {
            $entries = array(
                'status' => false,
                'message' => 'No method found',
                'data' => []
            );
        }
        if ($type === 'post')
        {
            $entries['data']['remarks'] = $remarks;
            return self::postVoucher($entries['data']);
        } else {
            return $entries;
        }
    }
    public static function paymentInvoiceVoucherDetails(int $documentId, string $remarks) : array
    {
        $accountSetup = isset(self::SERVICE_POSTING_MAPPING[ConstantHelper::PAYMENTS_SERVICE_ALIAS])? self::SERVICE_POSTING_MAPPING[ConstantHelper::PAYMENTS_SERVICE_ALIAS] : [];
        if (!isset($accountSetup) || count($accountSetup) == 0) {
            return array(
                'status' => false,
                'message' => 'Account Setup not found',
                'data' => []
            );
        }
        $document = PaymentVoucher::find($documentId);
        $vendors = $document->details;
        $vocuherdata=$document;
        if (!isset($document)) {
            return array(
                'status' => false,
                'message' => 'Document not found',
                'data' => []
            );
        }
        //Make array according to setup
        $postingArray = array(
            self::PAYMENT_ACCOUNT => [],
            self::VENDOR_ACCOUNT => [],
        );
        $totalCreditAmount = 0;
        $totalDebitAmount = 0;

        $ledgerErrorStatus = null;

        if(!empty($vocuherdata))
        {
            $BankLedgerId = $vocuherdata->ledger_id;
            $BankLedgerGroupId = $vocuherdata->ledger_group_id;
            $BankLedger = Ledger::find($BankLedgerId);
            $BankLedgerGroup = Group::find($BankLedgerGroupId);

        }

        if (!isset($BankLedger) || !isset($BankLedgerGroup)) {
            return array(
                'status' => false,
                'message' => 'Bank Ledger not setup',
                'data' => []
            );
        }

            array_push($postingArray[self::PAYMENT_ACCOUNT], [
                'ledger_id' => $vocuherdata->bank->ledger_id,
                'ledger_group_id' => $vocuherdata->bank->ledger_id,
                'ledger_code' => $BankLedger ?-> code,
                'ledger_name' => $BankLedger ?-> name,
                'ledger_group_code' => $BankLedgerGroup ?-> name,
                'debit_amount' => 0,
                'credit_amount' => $vocuherdata->amount
            ]);
            foreach ($vendors as $vendor) {

        if(!empty($vendor))
        {
            $VendorLedgerId = $vendor->party->ledger_id;
            $VendorLedgerGroupId = $vendor->party->ledger_group_id;
            $VendorLedger = Ledger::find($VendorLedgerId);
            $VendorLedgerGroup = Group::find($VendorLedgerGroupId);

        }

        if (!isset($VendorLedger) || !isset($VendorLedgerGroup)) {
            return array(
                'status' => false,
                'message' => 'Vendor Ledger not setup',
                'data' => []
            );
        }
            array_push($postingArray[self::VENDOR_ACCOUNT], [
                'ledger_id' => $VendorLedgerId,
                'ledger_group_id' => $VendorLedgerGroupId,
                'ledger_code' => $VendorLedger?->code,
                'ledger_name' => $VendorLedger?->name,
                'ledger_group_code' => $VendorLedgerGroup?->name,
                'credit_amount' => 0,
                'debit_amount' => $vendor->currentAmount,
            ]);
}



        //Check if All Legders exists and posting is properly set
        if ($ledgerErrorStatus) {
            return array(
                'status' => false,
                'message' => $ledgerErrorStatus,
                'data' => []
            );
        }


        //Check debit and credit tally
        foreach ($postingArray as $postAccount) {
            foreach ($postAccount as $postingValue) {

                $totalDebitAmount += $postingValue['debit_amount'];
                $totalCreditAmount += $postingValue['credit_amount'];
            }
        }
        //Balance does not match
        if ($totalDebitAmount !== $totalCreditAmount) {
            return array(
                'status' => false,
                'message' => 'Credit Amount does not match Debit Amount',
                'data' => []
            );
        }
        //Get Header Details
        $book = Book::find($document->book_id);
        $glPostingBookParam = OrganizationBookParameter::where('book_id', $book->id)->where('parameter_name', ServiceParametersHelper::GL_POSTING_SERIES_PARAM)->first();
        if (isset($glPostingBookParam)) {
            $glPostingBookId = $glPostingBookParam -> parameter_value[0];
        } else {
            return array(
                'status' => false,
                'message' => 'Financial Book Code is not specified',
                'data' => []
            );
        }

        $currdata = OrganizationCompany::where('id',$document->company_id)->first();
        $excurrdata = CurrencyExchange::where('from_currency_id',$currdata->currency_id)->first();
        $currency = Currency::find($currdata->currency_id);

        $userData = Helper::userCheck();

        $booksdata = Book::where('book_name','JOURNAL_VOUCHER')->first();
        $numberPatternData = Helper::generateDocumentNumberNew($booksdata->id, $document->document_date);
        if (!isset($numberPatternData)) {
            return response()->json([
                'message' => "Invalid Book",
                'error' => "",
            ], 422);
        }
        $document_number = $numberPatternData['document_number'] ? $numberPatternData['document_number'] : null;

        $voucherHeader = [
            'voucher_no' => $document_number,
            'voucher_name' => $booksdata->book_code,
            'doc_prefix' => $numberPatternData['prefix'],
            'doc_suffix' => $numberPatternData['suffix'],
            'doc_no' => $numberPatternData['doc_no'],
            'doc_reset_pattern' => $numberPatternData['reset_pattern'],
            'document_date' => $document->document_date,
            'book_id' => $booksdata->id,
            'book_type_id' => $booksdata->org_service_id,
            'date' => $document->document_date,
            'amount' => $totalCreditAmount,
            'currency_id' => $currdata->currency_id,
            'currency_code' => $currdata->currency_code,
            'org_currency_id' => $currdata->currency_id,
            'org_currency_code' => $currdata->currency_code,
            'org_currency_exg_rate' => $excurrdata->exchange_rate,
            'comp_currency_id' => $currdata->currency_id, // Missing comma added here
            'comp_currency_code' => $currdata->currency_code,
            'comp_currency_exg_rate' => $excurrdata->exchange_rate,
            'group_currency_id' => $currdata->currency_id,
            'group_currency_code' => $currdata->currency_code,
            'group_currency_exg_rate' => $excurrdata->exchange_rate,
            'reference_service' => $book?->service?->alias,
            'reference_doc_id' => $document->id,
            'group_id' => $document->group_id,
            'company_id' => $document->company_id,
            'organization_id' => $document->organization_id,
            'voucherable_type' => $userData['user_type'],
            'voucherable_id' => $userData['user_id'],
            'approvalStatus' => ConstantHelper::APPROVED,
            'document_status' => ConstantHelper::APPROVED,
            'approvalLevel' => $document->approval_level,
            'remarks'=>$remarks,
        ];

       $voucherDetails = [];
       foreach ($postingArray as $entryType => $postDetails) {
        foreach ($postDetails as $post) {
            array_push($voucherDetails, [
                'ledger_id' => $post['ledger_id'],
                'group_id' => $document->group_id,
                'company_id' => $document->company_id,
                'organization_id'=>$document->organization_id,
                'ledger_parent_id' => $post['ledger_group_id'],
                'debit_amt' => $post['debit_amount'],
                'credit_amt' => $post['credit_amount'],
                'debit_amt_org' => $post['debit_amount'] * $voucherHeader['org_currency_exg_rate'],
                'credit_amt_org' => $post['credit_amount'] * $voucherHeader['org_currency_exg_rate'],
                'debit_amt_comp' => $post['debit_amount'] * $voucherHeader['comp_currency_exg_rate'],
                'credit_amt_comp' => $post['credit_amount'] * $voucherHeader['comp_currency_exg_rate'],
                'debit_amt_group' => $post['debit_amount'] * $voucherHeader['group_currency_exg_rate'],
                'credit_amt_group' => $post['credit_amount'] * $voucherHeader['group_currency_exg_rate'],
                'entry_type' => $entryType,
            ]);
        }
       }
        return array(
            'status' => true,
            'message' => 'Posting Details found',
            'data' => [
                'voucher_header' => $voucherHeader,
                'voucher_details' => $voucherDetails,
                'document_date' => $document->created_at,
                'ledgers' => $postingArray,
                'total_debit' => $totalDebitAmount,
                'total_credit' => $totalCreditAmount,
                'book_code' => $booksdata ?-> book_code,
                'document_number' =>$document_number,
                'currency_code' => $currency ?-> short_name
            ]
        );
    }

    public static function paymentVoucherPosting(int $bookId, int $documentId, string $type,string $remarks) : array|bool
    {
        //Check Book
        $book = Book::find($bookId);
        if (!isset($book)) {
            return array(
                'status' => false,
                'message' => 'Book not found',
                'data' => []
            );
        }
        //Check Service
        $service = Service::find($book -> service_id);
        if (!isset($service)) {
            return array(
                'status' => false,
                'message' => 'Service not found',
                'data' => []
            );
        }
        $isFinanceVoucherDefined = ServiceParametersHelper::getFinancialServiceAlias($service -> alias);
        if (!isset($isFinanceVoucherDefined)) {
            return array(
                'status' => false,
                'message' => '',
                'data' => []
            );
        }
        //Check Posting parameters
        $financialPostParam = OrganizationBookParameter::where('book_id', $book->id)->where('parameter_name', ServiceParametersHelper::GL_POSTING_REQUIRED_PARAM)->first();
        if (!isset($financialPostParam)) {
            return array(
                'status' => false,
                'message' => 'GL Posting Parameter not specified',
                'data' => []
            );
        }
        $isPostingRequired = (($financialPostParam->parameter_value[0] ?? '') === 'yes' ? true : false);
        if (!$isPostingRequired) {
            return array(
                'status' => false,
                'message' => '',
                'data' => []
            );
        }

        //Call helpers according to service
        $serviceAlias = $service -> alias;
        if ($serviceAlias === ConstantHelper::PAYMENTS_SERVICE_ALIAS) {
            $entries = self::paymentInvoiceVoucherDetails($documentId, $remarks);
            if (!$entries['status']) {
                return array(
                    'status' => false,
                    'message' => $entries['message'],
                    'data' => []
                );
            }
        } else {
            $entries = array(
                'status' => false,
                'message' => 'No method found',
                'data' => []
            );
        }
        if ($type === 'post')
        {
            $entries['data']['remarks'] = $remarks;
            return self::postVoucher($entries['data']);
        } else {
            return $entries;
        }
    }

    public static function purchaseReturnVoucherDetails(int $documentId, string $type) : array
    {
        $document = PRHeader::find($documentId);
        if (!isset($document)) {
            return array(
                'status' => false,
                'message' => 'Document not found',
                'data' => []
            );
        }
        // dd($document->toArray());
        $postingArray = array(
            self::STOCK_ACCOUNT => [],
            self::TAX_ACCOUNT => [],
            self::EXPENSE_ACCOUNT => [],
            self::DISCOUNT_ACCOUNT => [],
            self::GRIR_ACCOUNT => [],
            self::SUPPLIER_ACCOUNT => [],
        );
        //Assign Credit and Debit amount for tally check
        $totalCreditAmount = 0;
        $totalDebitAmount = 0;
        
        // Vendor Detail
        $vendor = Vendor::find($document -> vendor_id);
        $vendorLedgerId = $vendor -> ledger_id;
        $vendorLedgerGroupId = $vendor -> ledger_group_id;
        $vendorLedger = Ledger::find($vendorLedgerId);
        $vendorLedgerGroup = Group::find($vendorLedgerGroupId);
        //Vendor Ledger account not found
        if (!isset($vendorLedger) || !isset($vendorLedgerGroup)) {
            return array(
                'status' => false,
                'message' => self::ERROR_PREFIX.'Vendor Account not setup',
                'data' => []
            );
        }

        $discountPostingParam = OrganizationBookParameter::where('book_id', $document -> book_id)
        -> where('parameter_name', ServiceParametersHelper::GL_SEPERATE_DISCOUNT_PARAM) -> first();
        if (isset($discountPostingParam)) {
            $discountSeperatePosting = $discountPostingParam -> parameter_value[0] === "yes" ? true : false;
        } else {
            $discountSeperatePosting = false;
        }


        //Status to check if all ledger entries were properly set
        $ledgerErrorStatus = null;
        //COGS SETUP
        foreach ($document -> items as $docItemKey => $docItem) {
            $itemValue = ($docItem -> rate * $docItem -> accepted_qty);
            $itemTotalDiscount = $docItem -> header_discount_amount + $docItem -> discount_amount;
            $itemValueAfterDiscount = $itemValue - $itemTotalDiscount;
            $stockDebitAmount = $discountSeperatePosting ? $itemValue : $itemValueAfterDiscount;

            $stockLedgerDetails = AccountHelper::getStockLedgerGroupAndLedgerId($document -> organization_id, $docItem -> item_id, $document -> book_id);
            // dd($stockLedgerDetails);
            $stockLedgerId = $stockLedgerDetails -> first()['ledger_id'] ?? null;
            $stockLedgerGroupId = $stockLedgerDetails-> first()['ledger_group'] ?? null;
            // dd($stockLedgerDetails -> first()['ledger_id']);
            $stockLedger = Ledger::find($stockLedgerId);
            $stockLedgerGroup = Group::find($stockLedgerGroupId);
            //LEDGER NOT FOUND
            if (!isset($stockLedger) || !isset($stockLedgerGroup)) {
                $ledgerErrorStatus = self::ERROR_PREFIX.'Stock Account not setup';
                break;
            }
            //Check for same ledger and group in SALES ACCOUNT
            $existingstockLedger = array_filter($postingArray[self::STOCK_ACCOUNT], function ($posting) use($stockLedgerId, $stockLedgerGroupId) {
                return $posting['ledger_id'] == $stockLedgerId && $posting['ledger_group_id'] == $stockLedgerGroupId;
            });
            //Ledger found
            if (count($existingstockLedger) > 0) {
                $postingArray[self::STOCK_ACCOUNT][0]['credit_amount'] +=  $stockDebitAmount;
            } else { //Assign a new ledger
                array_push($postingArray[self::STOCK_ACCOUNT], [
                    'ledger_id' => $stockLedgerId,
                    'ledger_group_id' => $stockLedgerGroupId,
                    'ledger_code' => $stockLedger ?-> code,
                    'ledger_name' => $stockLedger ?-> name,
                    'ledger_group_code' => $stockLedgerGroup ?-> name,
                    'credit_amount' => $stockDebitAmount,
                    'debit_amount' => 0
                ]);
            }
            //Stock for SUPPLIER ACCOUNT
            $existingVendorLedger = array_filter($postingArray[self::SUPPLIER_ACCOUNT], function ($posting) use($vendorLedgerId, $vendorLedgerGroupId) {
                return $posting['ledger_id'] == $vendorLedgerId && $posting['ledger_group_id'] === $vendorLedgerGroupId;
            });
            //Ledger found
            if (count($existingVendorLedger) > 0) {
                $postingArray[self::SUPPLIER_ACCOUNT][0]['debit_amount'] += $itemValueAfterDiscount;
            } else { //Assign new ledger
                array_push($postingArray[self::SUPPLIER_ACCOUNT], [
                    'ledger_id' => $vendorLedgerId,
                    'ledger_group_id' => $vendorLedgerGroupId,
                    'ledger_code' => $vendorLedger ?-> code,
                    'ledger_name' => $vendorLedger ?-> name,
                    'ledger_group_code' => $vendorLedgerGroup ?-> name,
                    'credit_amount' => 0,
                    'debit_amount' => $itemValueAfterDiscount
                ]);
            }
        }
        //TAXES ACCOUNT
        $taxes = PRTed::where('header_id', $document -> id) -> where('ted_type', "Tax") -> get();
        foreach ($taxes as $tax) {
            $taxDetail = TaxDetail::find($tax -> ted_id);
            $taxLedgerId = $taxDetail -> ledger_id ?? null; //MAKE IT DYNAMIC
            $taxLedgerGroupId = $taxDetail -> ledger_group_id ?? null; //MAKE IT DYNAMIC
            $taxLedger = Ledger::find($taxLedgerId);
            $taxLedgerGroup = Group::find($taxLedgerGroupId);
            if (!isset($taxLedger) || !isset($taxLedgerGroup)) {
                $ledgerErrorStatus = self::ERROR_PREFIX.'Tax Account not setup';
                break;
            }
            $existingTaxLedger = array_filter($postingArray[self::TAX_ACCOUNT], function ($posting) use($taxLedgerId, $taxLedgerGroupId) {
                return $posting['ledger_id'] == $taxLedgerId && $posting['ledger_group_id'] === $taxLedgerGroupId;
            });
            //Ledger found
            if (count($existingTaxLedger) > 0) {
                $postingArray[self::TAX_ACCOUNT][0]['credit_amount'] += $tax -> ted_amount;
            } else { //Assign a new ledger
                array_push($postingArray[self::TAX_ACCOUNT], [
                    'ledger_id' => $taxLedgerId,
                    'ledger_group_id' => $taxLedgerGroupId,
                    'ledger_code' => $taxLedger ?-> code,
                    'ledger_name' => $taxLedger ?-> name,
                    'ledger_group_code' => $taxLedgerGroup ?-> name,
                    'credit_amount' => $tax -> ted_amount,
                    'debit_amount' => 0,
                ]);
            }
            // dd($tax -> ted_amount);
            //Tax for SUPPLIER ACCOUNT
            $existingVendorLedger = array_filter($postingArray[self::SUPPLIER_ACCOUNT], function ($posting) use($vendorLedgerId, $vendorLedgerGroupId) {
                return $posting['ledger_id'] == $vendorLedgerId && $posting['ledger_group_id'] === $vendorLedgerGroupId;
            });
            //Ledger found
            if (count($existingVendorLedger) > 0) {
                $postingArray[self::SUPPLIER_ACCOUNT][0]['debit_amount'] += $tax -> ted_amount;
            } else { //Assign new ledger
                array_push($postingArray[self::SUPPLIER_ACCOUNT], [
                    'ledger_id' => $vendorLedgerId,
                    'ledger_group_id' => $vendorLedgerGroupId,
                    'ledger_code' => $vendorLedger ?-> code,
                    'ledger_name' => $vendorLedger ?-> name,
                    'ledger_group_code' => $vendorLedgerGroup ?-> name,
                    'credit_amount' => 0,
                    'debit_amount' => $tax -> ted_amount,
                ]);
            }
        }

        //EXPENSES
        $expenses = PRTed::where('header_id', $document -> id) -> where('ted_type', "Expense") -> get();
        foreach ($expenses as $expense) {
            $expenseDetail = ExpenseMaster::find($expense -> ted_id);
            $expenseLedgerId = $expenseDetail ?-> expense_ledger_id; //MAKE IT DYNAMIC - 5
            $expenseLedgerGroupId = $expenseDetail ?-> expense_ledger_group_id; //MAKE IT DYNAMIC - 9
            $expenseLedger = Ledger::find($expenseLedgerId);
            $expenseLedgerGroup = Group::find($expenseLedgerGroupId);
            if (!isset($expenseLedger) || !isset($expenseLedgerGroup)) {
                $ledgerErrorStatus = self::ERROR_PREFIX.'Expense Account not setup';
                break;
            }
            $existingExpenseLedger = array_filter($postingArray[self::EXPENSE_ACCOUNT], function ($posting) use($expenseLedgerId, $expenseLedgerGroupId) {
                return $posting['ledger_id'] == $expenseLedgerId && $posting['ledger_group_id'] === $expenseLedgerGroupId;
            });
            //Ledger found
            if (count($existingExpenseLedger) > 0) {
                $postingArray[self::EXPENSE_ACCOUNT][0]['credit_amount'] += $expense -> ted_amount;
            } else { //Assign a new ledger
                array_push($postingArray[self::EXPENSE_ACCOUNT], [
                    'ledger_id' => $expenseLedgerId,
                    'ledger_group_id' => $expenseLedgerGroupId,
                    'ledger_code' => $expenseLedger ?-> code,
                    'ledger_name' => $expenseLedger ?-> name,
                    'ledger_group_code' => $expenseLedgerGroup ?-> name,
                    'credit_amount' => $expense -> ted_amount,
                    'debit_amount' => 0,
                ]);
            }
            //Expense for SUPPLIER ACCOUNT
            $existingVendorLedger = array_filter($postingArray[self::SUPPLIER_ACCOUNT], function ($posting) use($vendorLedgerId, $vendorLedgerGroupId) {
                return $posting['ledger_id'] == $vendorLedgerId && $posting['ledger_group_id'] === $vendorLedgerGroupId;
            });
            //Ledger found
            if (count($existingVendorLedger) > 0) {
                $postingArray[self::SUPPLIER_ACCOUNT][0]['debit_amount'] += $expense -> ted_amount;
            } else { //Assign new ledger
                array_push($postingArray[self::EXPENSE_ACCOUNT], [
                    'ledger_id' => $vendorLedgerId,
                    'ledger_group_id' => $vendorLedgerGroupId,
                    'ledger_code' => $vendorLedger ?-> code,
                    'ledger_name' => $vendorLedger ?-> name,
                    'ledger_group_code' => $vendorLedgerGroup ?-> name,
                    'credit_amount' => 0,
                    'debit_amount' => $expense -> ted_amount,
                ]);
            }
        }
        //Seperate posting of Discount
        if ($discountSeperatePosting) {
            $discounts = PRTed::where('header_id', $document -> id) -> where('ted_type', "Discount") -> get();
            foreach ($discounts as $discount) {
                $discountDetail = DiscountMaster::find($discount -> ted_id);
                $discountLedgerId = $discountDetail ?-> discount_ledger_id; //MAKE IT DYNAMIC
                $discountLedgerGroupId = $discountDetail ?-> discount_ledger_group_id; //MAKE IT DYNAMIC
                $discountLedger = Ledger::find($discountLedgerId);
                $discountLedgerGroup = Group::find($discountLedgerGroupId);
                if (!isset($discountLedger) || !isset($discountLedgerGroup)) {
                    $ledgerErrorStatus = self::ERROR_PREFIX.'Discount Account not setup';
                    break;
                }
                $existingDiscountLedger = array_filter($postingArray[self::DISCOUNT_ACCOUNT], function ($posting) use($discountLedgerId, $discountLedgerGroupId) {
                    return $posting['ledger_id'] == $discountLedgerId && $posting['ledger_group_id'] === $discountLedgerGroupId;
                });
                //Ledger found
                if (count($existingDiscountLedger) > 0) {
                    $postingArray[self::DISCOUNT_ACCOUNT][0]['debit_amount'] += $discount -> ted_amount;
                } else { //Assign a new ledger
                    array_push($postingArray[self::DISCOUNT_ACCOUNT], [
                        'ledger_id' => $discountLedgerId,
                        'ledger_group_id' => $discountLedgerGroupId,
                        'ledger_code' => $discountLedger ?-> code,
                        'ledger_name' => $discountLedger ?-> name,
                        'ledger_group_code' => $discountLedgerGroup ?-> name,
                        'debit_amount' => $discount -> ted_amount,
                        'credit_amount' => 0,
                    ]);
                }
            }
        }
        
        //Check if All Legders exists and posting is properly set
        if ($ledgerErrorStatus) {
            return array(
                'status' => false,
                'message' => $ledgerErrorStatus,
                'data' => []
            );
        }
        //Check debit and credit tally
        foreach ($postingArray as $postAccount) {
            foreach ($postAccount as $postingValue) {
                $totalCreditAmount += $postingValue['credit_amount'];
                $totalDebitAmount += $postingValue['debit_amount'];
            }
        }
        //Balance does not match
        if ($totalDebitAmount !== $totalCreditAmount) {
            return array(
                'status' => false,
                'message' => self::ERROR_PREFIX.'Credit Amount does not match Debit Amount',
                'data' => []
            );
        }
        //Get Header Details
        $book = Book::find($document -> book_id);
        $glPostingBookParam = OrganizationBookParameter::where('book_id', $book -> id) -> where('parameter_name', ServiceParametersHelper::GL_POSTING_SERIES_PARAM) -> first();
        // dd($glPostingBookParam);
        if (isset($glPostingBookParam)) {
            $glPostingBookId = $glPostingBookParam -> parameter_value[0];
        } else {
            return array(
                'status' => false,
                'message' => self::ERROR_PREFIX.'Financial Book Code is not specified',
                'data' => []
            );
        }
        $currency = Currency::find($document -> currency_id);
        $userData = Helper::userCheck();
        $voucherHeader = [
            'voucher_no' => $document -> document_number,
            'document_date' => $document -> document_date,
            'book_id' => $glPostingBookId,
            'date' => $document -> document_date,
            'amount' => $totalCreditAmount,
            'currency_id' => $document -> currency_id,
            'currency_code' => $document -> currency_code,
            'org_currency_id' => $document -> org_currency_id,
            'org_currency_code' => $document -> org_currency_code,
            'org_currency_exg_rate' => $document -> org_currency_exg_rate,
            'comp_currency_id' => $document -> comp_currency_id,
            'comp_currency_code' => $document -> comp_currency_code,
            'comp_currency_exg_rate' => $document -> comp_currency_exg_rate,
            'group_currency_id' => $document -> group_currency_id,
            'group_currency_code' => $document -> group_currency_code,
            'group_currency_exg_rate' => $document -> group_currency_exg_rate,
            'reference_service' => $book ?-> service ?-> alias,
            'reference_doc_id' => $document -> id,
            'group_id' => $document -> group_id,
            'company_id' => $document -> company_id,
            'organization_id' => $document -> organization_id,
            'voucherable_type' => $userData['user_type'],
            'voucherable_id' => $userData['user_id'],
            'document_status' => ConstantHelper::APPROVED,
            'approvalLevel' => $document -> approval_level
        ];
        $voucherDetails = [];
        foreach ($postingArray as $entryType => $postDetails) {
            foreach ($postDetails as $post) {
                array_push($voucherDetails, [
                    'ledger_id' => $post['ledger_id'],
                    'ledger_parent_id' => $post['ledger_group_id'],
                    'debit_amt' => $post['debit_amount'],
                    'credit_amt' => $post['credit_amount'],
                    'debit_amt_org' => $post['debit_amount'] * $voucherHeader['org_currency_exg_rate'],
                    'credit_amt_org' => $post['credit_amount'] * $voucherHeader['org_currency_exg_rate'],
                    'debit_amt_comp' => $post['debit_amount'] * $voucherHeader['comp_currency_exg_rate'],
                    'credit_amt_comp' => $post['credit_amount'] * $voucherHeader['comp_currency_exg_rate'],
                    'debit_amt_group' => $post['debit_amount'] * $voucherHeader['group_currency_exg_rate'],
                    'credit_amt_group' => $post['credit_amount'] * $voucherHeader['group_currency_exg_rate'],
                    'entry_type' => $entryType,

                ]);
            }
        }
        return array(
            'status' => true,
            'message' => 'Posting Details found',
            'data' => [
                'voucher_header' => $voucherHeader,
                'voucher_details' => $voucherDetails,
                'document_date' => $document -> document_date,
                'ledgers' => $postingArray,
                'total_debit' => $totalDebitAmount,
                'total_credit' => $totalCreditAmount,
                'book_code' => $book ?-> book_code,
                'document_number' => $document -> document_number,
                'currency_code' => $currency ?-> short_name
            ]
        );
    }

}
