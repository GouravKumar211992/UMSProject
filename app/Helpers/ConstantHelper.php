<?php

namespace App\Helpers;

use App\Models\Legal;

class ConstantHelper
{
    // Vendor Status
    const ACTIVE = 'active';
    const INACTIVE = 'inactive';
    const PENDING = 'pending';
    const WON = 'won';
    const LOST = 'lost';
    const CREDIT = 'CR';
    const DEBIT = 'DR';

    const ERP_CUSTOMER_STATUS = [
        self::ACTIVE,
        self::INACTIVE,
    ];
    

    const STATUS = [
        self::ACTIVE,
        self::INACTIVE,
    ];

    const USER_STATUS = [
        self::ACTIVE,
        self::INACTIVE,
        self::DRAFT,
    ];
    // Document Status
    const REVOKE = 'revoke';
    const DRAFT = 'draft';
    const SUBMITTED = 'submitted';
    const APPROVAL_NOT_REQUIRED = 'approval_not_required';
    const APPROVAL = 'approval';
    const PARTIALLY_APPROVED = 'partially_approved';
    // const REVOKE = 'revoke';

    const ASSIGNED = 'assigned';
    const APPROVED = 'approved';
    const REJECTED = 'rejected';
    const APPRAISAL = 'appraisal';
    const ASSESSMENT = 'assessment';
    const LEASE = 'Lease Rent';
    const SECURITY_DEPOSIT = 'Security Deposit';
    const LEASE_SERVICE_TYPE = [self::LEASE, self::SECURITY_DEPOSIT];
    const ASSESSED = 'Assessed';
    const SANCTIONED = 'sanctioned';
    const POST = 'post';
    const PROCESSING_FEE = 'processingfee';
    const REQUEST = 'Requested';
    const PROCESSFEEINCOMEACC = 'Processing Fee Income Account';
    const POSTED = 'posted';
    const DOCUMENT_STATUS = [self::DRAFT, self::SUBMITTED, self::APPROVAL_NOT_REQUIRED, self::PARTIALLY_APPROVED, self::APPROVED, self::REJECTED];

    const DOCUMENT_STATUS_CSS = [self::DRAFT => 'text-info', self::SUBMITTED => 'text-primary', self::APPROVAL_NOT_REQUIRED => 'text-success', self::PARTIALLY_APPROVED => 'text-warning', self::APPROVED => 'text-success', self::REJECTED => 'text-danger', self::POSTED => 'text-primary-new'];

    const DOCUMENT_STATUS_CSS_WO_TEXT = [self::DRAFT => 'info', self::SUBMITTED => 'primary', self::APPROVAL_NOT_REQUIRED => 'success', self::PARTIALLY_APPROVED => 'warning', self::APPROVED => 'success', self::REJECTED => 'danger', self::POSTED => 'info'];

    const DOCUMENT_STATUS_CSS_LIST = [self::DRAFT => 'badge-light-info', self::SUBMITTED => 'badge-light-primary', self::APPROVAL_NOT_REQUIRED => 'badge-light-success', self::PARTIALLY_APPROVED => 'badge-light-warning', self::APPROVED => 'badge-light-success', self::REJECTED => 'badge-light-danger',self::POSTED => 'badge-light-info'];
    // Error Message
    const DUPLICATE_DOCUMENT_NUMBER = "Document number already exists.";

    // Titles
    const MR = 'Mr.';
    const MRS = 'Mrs.';
    const MS = 'Ms.';
    const MISS = 'Miss.';
    const DR = 'Dr.';

    const TITLES = [
        self::MR,
        self::MRS,
        self::MS,
        self::MISS,
        self::DR,
    ];

    // Vendor Types
    const ORGANISATION = 'Organisation';
    const INDIVIDUAL = 'Individual';

    const VENDOR_TYPES = [
        self::ORGANISATION,
        self::INDIVIDUAL,
    ];

    const CUSTOMER_TYPES = [
        self::ORGANISATION,
        self::INDIVIDUAL,
    ];

    const CRM_CUSTOMER_TYPES = [
        'New',
        'Existing',
    ];

    // Yes/No Options
    const YES = 'Yes';
    const NO = 'No';

    const STOP_OPTIONS = [
        self::YES,
        self::NO,
    ];

    // Category Types
    const PRODUCT = 'Product';
    const SERVICE = 'Service';
    const SUPPLY = 'Supply';
    const CUSTOMER = 'Customer';
    const VENDOR = 'Vendor';

    const CATEGORY_TYPES = [
        self::PRODUCT,
        self::CUSTOMER,
        self::VENDOR,
    ];

    const SHIPPING = 'shipping';
    const BILLING = 'billing';
    const BOTH = 'both';
    const DEFAULT = 'default';

    const ADDRESS_TYPES = [
        self::SHIPPING,
        self::BILLING,
        self::BOTH,
    ];

    // MSME Types
    const MICRO = 'Micro';
    const SMALL = 'Small';
    const MEDIUM = 'Medium';

    const MSME_TYPES = [
        self::MICRO,
        self::SMALL,
        self::MEDIUM,
    ];

    const GST_REGISTERED = 'Registered';
    const GST_NON_REGISTERED = 'Non-Registered';

    const GST_APPLICABLE = [
        self::GST_REGISTERED,
        self::GST_NON_REGISTERED,
    ];

    const GOODS = 'Goods';
    const ITEM_TYPES = [
        self::GOODS,
        self::SERVICE,
    ];

    const OPEN = 'Open';
    const CLOSE = 'Close';

    const PURCHASE_ORDER_STATUS = [
        self::OPEN,
        self::CLOSE,
    ];

    const PERCENTAGE = 'percentage';
    const FIXED = 'fixed';

    const DISCOUNT_TYPES = [
        self::PERCENTAGE,
        self::FIXED,
    ];

    public const DEFAULT_PURCHASE = 'Purchase';
    public const DEFAULT_SELLING = 'Selling';

    const DEDUCTION = 'deduction';
    const COLLECTION = 'collection';

    const TAX_APPLICATION_TYPE = [
        self::DEDUCTION,
        self::COLLECTION,
    ];

    // Tax Types
    const SGST = 'SGST';
    const CGST = 'CGST';
    const IGST = 'IGST';
    const TDS = 'TDS';
    const TCS = 'TCS';
    const VAT = 'VAT';

    const TAX_TYPES = [
        self::SGST,
        self::CGST,
        self::IGST,
        self::TDS,
        self::TCS,
        self::VAT,
    ];

    // Place of Supply Types
    const INTRASTATE = 'Intrastate';
    const INTERSTATE = 'Interstate';
    const OVERSEAS = 'Overseas';

    const PLACE_OF_SUPPLY_TYPES = [
        self::INTRASTATE,
        self::INTERSTATE,
        self::OVERSEAS,
    ];

    const HSN = 'Hsn';
    const SAC = 'Sac';

    const HSN_CODE_TYPE = [
        self::HSN,
        self::SAC,
    ];

    public const TRIGGER_TYPES = [
        'advance',
        'on delivery',
        'post delivery',
    ];

    const SHARING_POLICY_GLOBAL = 'global';
    public const SHARING_POLICY_COMPANY = 'company';
    public const SHARING_POLICY_LOCAL = 'local';
    public const SHARING_POLICY_HYBRID = 'hybrid';

    public const SHARING_POLICY = [
        self::SHARING_POLICY_GLOBAL => 'Global',
        self::SHARING_POLICY_COMPANY => 'Company',
        self::SHARING_POLICY_LOCAL => 'Local',
        self::SHARING_POLICY_HYBRID => 'Hybrid',
    ];
    //Service Labels
    const SERVICE_LABEL = [self::SO_SERVICE_ALIAS => "Sales Order", self::SI_SERVICE_ALIAS => "Tax Invoice", self::SQ_SERVICE_ALIAS => "Sales Quotation", self::SR_SERVICE_ALIAS => "Sales Return",self::DELIVERY_CHALLAN_SERVICE_ALIAS => "Delivery Note", self::BOM_SERVICE_ALIAS => "Bill Of Material" , self::PO_SERVICE_ALIAS => "Purchase Order" , self::SUPPLIER_INVOICE_SERVICE_ALIAS => "Supplier Invoice" , self::PI_SERVICE_ALIAS => "Purchase Indent" , self:: MRN_SERVICE_ALIAS => "MRN" , self::EXPENSE_SERVICE_ALIAS => "Expense" , self::PB_SERVICE_ALIAS => "Purchase Bills"];
    //Service Alias
    const BOM_SERVICE_ALIAS = 'bom';
    const PO_SERVICE_ALIAS = 'po';
    const SUPPLIER_INVOICE_SERVICE_ALIAS = 'supplier-invoice';
    const PI_SERVICE_ALIAS = 'purchase-indent';
    const MRN_SERVICE_ALIAS = 'mrn';
    const EXPENSE_SERVICE_ALIAS = 'expense';
    const PURCHASE_RETURN_SERVICE_ALIAS = 'purchase-return';
    const MATERIAL_REQUEST_SERVICE_ALIAS = 'material-request';
    const MATERIAL_ISSUE_SERVICE_ALIAS = 'material-issue';
    const STOCK_ADJUSTMENT_SERVICE_ALIAS = 'stock-adjustment';
    const PHYSICAL_STOCK_TAKE_SERVICE_ALIAS = 'physical-stock-take';
    const COMMERCIAL_BOM_SERVICE_ALIAS = 'commercial-bom';
    const PRODUCTION_WORK_ORDER_SERVICE_ALIAS = 'production-work-order';
    const JOB_ORDER_SERVICE_ALIAS = 'job-order';
    const PRODUCTION_SLIP_SERVICE_ALIAS = 'production-slip';


    const PB_SERVICE_ALIAS = 'pb';
    const SO_SERVICE_ALIAS = 'so';
    const SQ_SERVICE_ALIAS = 'sq';
    const SI_SERVICE_ALIAS = 'si';
    const SR_SERVICE_ALIAS = 'sr';
    const LEASE_INVOICE_SERVICE_ALIAS = 'lease-invoice';
    const DELIVERY_CHALLAN_SERVICE_ALIAS = "dnote";
    const DELIVERY_CHALLAN_CUM_SI_SERVICE_ALIAS = "sinvdnote";
    const EXPENSE_ADVISE_SERVICE_ALIAS = 'expense-advice';
    const PURCHASE_VOUCHER = 'pv';
    const SALES_VOUCHER = 'sv';
    const RECEIPT_VOUCHER = 'receipt-voucher';
    const PAYMENT_VOUCHER = 'payment-voucher';
    const DEBIT_Note = 'dn';
    const CREDIT_Note = 'cn';
    const JOURNAL_VOUCHER = 'jv';
    const CONTRA_VOUCHER = 'cv';
    const PAYMENT_VOUCHER_RECEIPT = 'receipt-payment-voucher';
    const PAYMENTS_SERVICE_ALIAS = 'payments';
    const RECEIPTS_SERVICE_ALIAS = 'receipts';

    const LAND_PARCEL = 'land-parcel';
    const LEGAL_FILE = 'legal-file';

    const FILE_TRACKING = 'file-tracking';
    const LOAN_GRANT_FILE = 'loan-grant-file';
    const PROJECT_FILES = 'project-files';
    const POLICY_FILES = 'policy-files';
    const AUDIT_COMPLIANCE_FILES = 'audit-compliance-files';
    const TECHNICAL_FILES = 'technical-files';
    const RESEARCH_FILES = 'research-files';



    // const HOME_LOAN  = 'home-loan';
    const HOMELOAN = 'home-loan';
    const TERMLOAN = 'term-loan';
    const VEHICLELOAN = 'vehicle-loan';

    const LAND_PLOT = 'land-plot';
    const LAND_LEASE = 'land-lease';

    const LOAN_RECOVERY = 'loan-recovery';

    const LOAN_SETTLEMENT = 'loan-settlement';
    const LOAN_DISBURSEMENT = 'loan-disbursement';
    const LEGAL = 'legal';

    const FIXEDASSET = 'fixed-asset';

    //Operation and Financial Services mapping
    const OPERATION_FINANCIAL_SERVICES_MAPPING = [
        self::HOMELOAN => self::HOMELOAN,
        self::LOAN_RECOVERY => self::LOAN_RECOVERY,
        self::LOAN_SETTLEMENT => self::LOAN_SETTLEMENT,
        self::LOAN_DISBURSEMENT => self::LOAN_DISBURSEMENT,
        self::SI_SERVICE_ALIAS => self::SALES_VOUCHER,
        self::DELIVERY_CHALLAN_SERVICE_ALIAS => self::SALES_VOUCHER,
        self::DELIVERY_CHALLAN_CUM_SI_SERVICE_ALIAS => self::SALES_VOUCHER,
        self::MRN_SERVICE_ALIAS => self::PURCHASE_VOUCHER,
        self::PB_SERVICE_ALIAS => self::PURCHASE_VOUCHER,
        self::EXPENSE_ADVISE_SERVICE_ALIAS => self::PURCHASE_VOUCHER,
        self::PURCHASE_RETURN_SERVICE_ALIAS => self::PURCHASE_VOUCHER,
        self::RECEIPT_VOUCHER=>self::RECEIPT_VOUCHER,
        self::PAYMENT_VOUCHER_RECEIPT=>self::PAYMENT_VOUCHER_RECEIPT,
        self::LEASE_INVOICE_SERVICE_ALIAS=>self::SALES_VOUCHER,
        self::PAYMENTS_SERVICE_ALIAS=>self::PAYMENTS_SERVICE_ALIAS,
        self::RECEIPTS_SERVICE_ALIAS=>self::RECEIPTS_SERVICE_ALIAS,
        
    ];

    //Service Alias Models Mapping
    const SERVICE_ALIAS_MODELS = [
        self::BOM_SERVICE_ALIAS => 'Bom',
        self::PO_SERVICE_ALIAS => 'PurchaseOrder',
        self::SUPPLIER_INVOICE_SERVICE_ALIAS => 'PurchaseOrder',
        self::PI_SERVICE_ALIAS => 'PurchaseIndent',
        self::MRN_SERVICE_ALIAS => 'MrnHeader',
        self::EXPENSE_ADVISE_SERVICE_ALIAS => 'ExpenseHeader',
        self::PURCHASE_RETURN_SERVICE_ALIAS => 'PRHeader',
        self::MATERIAL_REQUEST_SERVICE_ALIAS => 'PurchaseIndent',
        self::MATERIAL_ISSUE_SERVICE_ALIAS => 'MrnHeader',
        self::STOCK_ADJUSTMENT_SERVICE_ALIAS => 'MrnHeader',
        self::PHYSICAL_STOCK_TAKE_SERVICE_ALIAS => 'MrnHeader',
        self::COMMERCIAL_BOM_SERVICE_ALIAS => 'Bom',
        self::PRODUCTION_SLIP_SERVICE_ALIAS => 'MrnHeader',
        self::PRODUCTION_WORK_ORDER_SERVICE_ALIAS => 'PurchaseOrder',
        self::JOB_ORDER_SERVICE_ALIAS => 'PurchaseOrder',
        self::PB_SERVICE_ALIAS => 'PbHeader',
        self::SO_SERVICE_ALIAS => 'ErpSaleOrder',
        self::SQ_SERVICE_ALIAS => 'ErpSaleOrder',
        self::SI_SERVICE_ALIAS => 'ErpSaleInvoice',
        self::SR_SERVICE_ALIAS => 'ErpSaleReturn',
        self::LEASE_INVOICE_SERVICE_ALIAS => 'ErpSaleInvoice',
        self::DELIVERY_CHALLAN_SERVICE_ALIAS => 'ErpSaleInvoice',
        self::DELIVERY_CHALLAN_CUM_SI_SERVICE_ALIAS => 'ErpSaleInvoice',
        self::PURCHASE_VOUCHER => 'Voucher',
        self::SALES_VOUCHER => 'Voucher',
        self::RECEIPT_VOUCHER => 'Voucher',
        self::PAYMENT_VOUCHER => 'Voucher',
        self::CREDIT_Note => 'Voucher',
        self::DEBIT_Note => 'Voucher',
        self::JOURNAL_VOUCHER => 'Voucher',
        self::CONTRA_VOUCHER => 'Voucher',
        self::PAYMENT_VOUCHER_RECEIPT => 'PaymentVoucher',
        self::PAYMENTS_SERVICE_ALIAS => 'PaymentVoucher',
        self::RECEIPTS_SERVICE_ALIAS => 'PaymentVoucher',
        self::LEGAL_FILE => 'FileTracking',
        self::LOAN_GRANT_FILE => 'FileTracking',
        self::PROJECT_FILES => 'FileTracking',
        self::POLICY_FILES => 'FileTracking',
        self::AUDIT_COMPLIANCE_FILES => 'FileTracking',
        self::TECHNICAL_FILES => 'FileTracking',
        self::RESEARCH_FILES => 'FileTracking',
        self::FILE_TRACKING => 'FileTracking',

        self::HOMELOAN => 'HomeLoan',
        self::TERMLOAN => 'HomeLoan',
        self::VEHICLELOAN => 'HomeLoan',

        self::LAND_PARCEL => 'LandParcel',
        self::LAND_PLOT => 'LandPlot',
        self::LAND_LEASE => 'LandLease',
        self::LOAN_RECOVERY => 'RecoveryLoan',
        self::LOAN_SETTLEMENT => 'LoanSettlement',
        self::LOAN_DISBURSEMENT => 'LoanDisbursement',
        self::LEGAL => 'Legal',
        self::FIXEDASSET => 'FixedAssetRegistration'


    ];
    const SALE_INVOICE_DOC_TYPES = [self::SI_SERVICE_ALIAS, self::LEASE_INVOICE_SERVICE_ALIAS, self::DELIVERY_CHALLAN_SERVICE_ALIAS, self::DELIVERY_CHALLAN_CUM_SI_SERVICE_ALIAS];
    const SALE_RETURN_DOC_TYPES = [self::SR_SERVICE_ALIAS];
    const SALE_INVOICE_DOC_TYPES_FOR_DB = [self::SI_SERVICE_ALIAS, 'dn', 'sidn'];
    const SALE_RETURN_DOC_TYPES_FOR_DB = [self::SR_SERVICE_ALIAS, 'dn', 'srdn'];
    const DOC_NO_TYPE_AUTO = "Auto";
    const DOC_NO_TYPE_MANUAL = "Manually";
    const DOC_NO_TYPES = [self::DOC_NO_TYPE_AUTO, self::DOC_NO_TYPE_MANUAL];

    const DOC_RESET_PATTERN_NEVER = "Never";
    const DOC_RESET_PATTERN_YEARLY = "Yearly";
    const DOC_RESET_PATTERN_QUARTERLY = "Quarterly";
    const DOC_RESET_PATTERN_MONTHLY = "Monthly";
    const DOC_RESET_PATTERNS = [self::DOC_RESET_PATTERN_NEVER, self::DOC_RESET_PATTERN_YEARLY, self::DOC_RESET_PATTERN_QUARTERLY, self::DOC_RESET_PATTERN_MONTHLY];
    const NON_STOCK = 'non-stock';
    const STOCK = 'stock';
    const IS_SERVICE = [self::STOCK, self::NON_STOCK];

    const PAGE_LENGTH_10 = 10;
    const PAGE_LENGTH_20 = 20;
    const PAGE_LENGTH_50 = 50;
    const PAGE_LENGTH_100 = 100;
    const PAGE_LENGTH_2000 = 2000;
    const PAGE_LENGTH_1000 = 1000;
    const PAGE_LENGTH_10000 = 10000;

    const PAGE_LENGTHS = [
        self::PAGE_LENGTH_10,
        self::PAGE_LENGTH_20,
        self::PAGE_LENGTH_50,
        self::PAGE_LENGTH_100,
    ];

    const STOCKK = 'Stock';
    const SHOP_FLOOR = 'Shop floor';
    const ADMINISTRATION = 'Administration';
    const OTHER = 'Other';

    const ERP_STORE_LOCATION_TYPES = [
        self::STOCKK,
        self::SHOP_FLOOR,
        self::ADMINISTRATION,
        self::OTHER,
    ];

    const CRM_TOKEN = '$2y$12$t.wJEsgL6We96B9LK28ujuJ78xnhDRYynUYHu6DlUJ13m7D5lWv8y';
}
