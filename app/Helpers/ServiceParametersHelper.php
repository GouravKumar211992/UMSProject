<?php

namespace App\Helpers;
use App\Models\Book;
use App\Models\NumberPattern;
use App\Models\Organization;
use App\Models\OrganizationBookParameter;
use App\Models\OrganizationService;
use App\Models\OrganizationServiceParameter;
use App\Models\Service;
use App\Models\ServiceParameter;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
/**
 * Helper Class containing all logics related to Parameters Functionality in the project.
 */
class ServiceParametersHelper
{
    /**
     * CONSTANTS - DO NOT REMOVE OR MODIFY ANY KEY OR VALUE
     * Parameters with Applicable/ Possible Values -
     */
    const REFERENCE_FROM_SERVICE_PARAM = 'reference_from_service';
    const REFERENCE_FROM_SERIES_PARAM = 'reference_from_series';
    const BACK_DATE_ALLOW_PARAM = 'back_date_allowed';
    const BACK_DATE_ALLOW_PARAM_VALUES = ['yes', 'no'];
    const FUTURE_DATE_ALLOW_PARAM = 'future_date_allowed';
    const FUTURE_DATE_ALLOW_PARAM_VALUES = ['yes', 'no'];
    const GOODS_SERVICES_PARAM = 'goods_or_services';
    const GOODS_SERVICES_PARAM_VALUES = ['Goods', 'Service'];
    const GL_POSTING_REQUIRED_PARAM = 'gl_posting_required';
    const GL_POSTING_REQUIRED_PARAM_VALUES = ['yes', 'no'];
    const GL_POSTING_SERIES_PARAM = 'gl_posting_series';
    const GL_SEPERATE_DISCOUNT_PARAM = 'gl_seperate_discount_posting';
    const GL_SEPERATE_DISCOUNT_PARAM_VALUE = ['yes', 'no'];
    const POST_ON_ARROVE_PARAM = 'post_on_approval';
    const POST_ON_ARROVE_PARAM_VALUES = ['yes', 'no'];
    const TAX_REQUIRED_PARAM = 'tax_required';
    const TAX_REQUIRED_PARAM_VALUES = ['yes', 'no'];
    const BILL_TO_FOLLOW_PARAM = 'bill_to_follow';
    const BILL_TO_FOLLOW_PARAM_VALUES = ['yes', 'no'];
    const INVOICE_TO_FOLLOW_PARAM = 'invoice_to_follow';
    const INVOICE_TO_FOLLOW_PARAM_VALUES = ['yes', 'no'];
    const BOM_CONSUMPTION_METHOD = 'consumption_method';
    const BOM_CONSUMPTION_METHOD_VALUES = ['manual','norms'];
    const BOM_SECTION_REQUIRED = 'section_required';
    const BOM_SECTION_REQUIRED_VALUES = ['yes', 'no'];
    const BOM_SUB_SECTION_REQUIRED = 'sub_section_required';
    const BOM_SUB_SECTION_REQUIRED_VALUES = ['yes', 'no'];
    const BOM_STATION_REQUIRED = 'station_required';
    const BOM_STATION_REQUIRED_VALUES = ['yes', 'no'];
    const BOM_SUPERCEDE_COST_REQUIRED = 'supercede_cost_required';
    const BOM_SUPERCEDE_COST_REQUIRED_VALUES = ['yes', 'no'];
    const BOM_COMPONENT_WASTE_REQUIRED = 'component_waste_required';
    const BOM_COMPONENT_WASTE_REQUIRED_VALUES = ['yes', 'no'];
    const BOM_COMPONENT_OVERHEAD_REQUIRED = 'component_overhead_required';
    const BOM_COMPONENT_OVERHEAD_REQUIRED_VALUES = ['yes', 'no'];
    const PR_QTY_TYPE_PARAM = 'pr_qty_type';
    const PR_QTY_TYPE_VALUES = ['rejected', 'all'];
    /**
     * Constant Array for all Service Parameters
     */
    const SERVICE_PARAMETERS = [
        self::REFERENCE_FROM_SERVICE_PARAM => 'Reference From', //Applied
        self::REFERENCE_FROM_SERIES_PARAM => 'Reference Series', //Applied
        self::BACK_DATE_ALLOW_PARAM => 'Back Date Allowed?', //Applied
        self::FUTURE_DATE_ALLOW_PARAM => 'Future Date Allowed?', //Applied
        self::GOODS_SERVICES_PARAM => 'Goods/ Services', //Applied
        self::GL_POSTING_REQUIRED_PARAM => 'Financial Posting Required?',
        self::GL_SEPERATE_DISCOUNT_PARAM => 'Seperate Discount Posting?',
        self::GL_POSTING_SERIES_PARAM => 'Voucher Series',
        self::POST_ON_ARROVE_PARAM => 'Post on Approval?',
        self::TAX_REQUIRED_PARAM => 'Tax Required?',
        self::BILL_TO_FOLLOW_PARAM => 'Bill To Follow',
        self::INVOICE_TO_FOLLOW_PARAM => 'Invoice To Follow?',
        self::BOM_CONSUMPTION_METHOD => 'Consumption Calculation Method',
        self::BOM_SECTION_REQUIRED => 'Product Section Required?',
        self::BOM_SUB_SECTION_REQUIRED => 'Product Sub Section Required?',
        self::BOM_STATION_REQUIRED => 'Station Required?',
        self::BOM_SUPERCEDE_COST_REQUIRED => 'Supercede Cost Required?',
        self::BOM_COMPONENT_WASTE_REQUIRED => 'Wastages at component level Required?',
        self::BOM_COMPONENT_OVERHEAD_REQUIRED => 'Overheads at component level Required?',
        self::PR_QTY_TYPE_PARAM => 'PR Qty Type?',
    ];
    const SO_SERVICE_PARAMETERS = [
        [
            "name" => self::REFERENCE_FROM_SERVICE_PARAM, //Name of the parameter
            "applicable_values" => ["0", ConstantHelper::SQ_SERVICE_ALIAS], //All possible values
            "default_value" => ["0", ConstantHelper::SQ_SERVICE_ALIAS], //Default selected value(s)
            'is_multiple' => true, // Whether or not to allow multiple selection
            'service_level_visibility' => true, // Whether or not to show this parameter in UI
        ],
        [
            "name" => self::REFERENCE_FROM_SERIES_PARAM,
            "applicable_values" => [],
            "default_value" => [],
            'is_multiple' => true,
            'service_level_visibility' => false
        ],
        [
            "name" => self::BACK_DATE_ALLOW_PARAM,
            "applicable_values" => self::BACK_DATE_ALLOW_PARAM_VALUES,
            "default_value" => ['yes'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ],
        [
            "name" => self::FUTURE_DATE_ALLOW_PARAM,
            "applicable_values" => self::FUTURE_DATE_ALLOW_PARAM_VALUES,
            "default_value" => ['yes'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ],
        [
            "name" => self::GOODS_SERVICES_PARAM,
            "applicable_values" => self::GOODS_SERVICES_PARAM_VALUES,
            "default_value" => ['Goods'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ],
        [
            "name" => self::TAX_REQUIRED_PARAM,
            "applicable_values" => self::TAX_REQUIRED_PARAM_VALUES,
            "default_value" => ['yes'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ]
    ];
    const SR_SERVICE_PARAMETERS = [
        [
            "name" => self::REFERENCE_FROM_SERVICE_PARAM, //Name of the parameter
            "applicable_values" => ["0", ConstantHelper::SI_SERVICE_ALIAS], //All possible values
            "default_value" => ["0", ConstantHelper::SI_SERVICE_ALIAS], //Default selected value(s)
            'is_multiple' => true, // Whether or not to allow multiple selection
            'service_level_visibility' => true, // Whether or not to show this parameter in UI
        ],
        [
            "name" => self::REFERENCE_FROM_SERIES_PARAM,
            "applicable_values" => [],
            "default_value" => [],
            'is_multiple' => true,
            'service_level_visibility' => false
        ],
        [
            "name" => self::BACK_DATE_ALLOW_PARAM,
            "applicable_values" => self::BACK_DATE_ALLOW_PARAM_VALUES,
            "default_value" => ['yes'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ],
        [
            "name" => self::FUTURE_DATE_ALLOW_PARAM,
            "applicable_values" => self::FUTURE_DATE_ALLOW_PARAM_VALUES,
            "default_value" => ['yes'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ],
        [
            "name" => self::GOODS_SERVICES_PARAM,
            "applicable_values" => self::GOODS_SERVICES_PARAM_VALUES,
            "default_value" => ['Goods'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ],
        [
            "name" => self::TAX_REQUIRED_PARAM,
            "applicable_values" => self::TAX_REQUIRED_PARAM_VALUES,
            "default_value" => ['yes'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ]
    ];
    const SQ_SERVICE_PARAMETERS = [
        [
            "name" => self::REFERENCE_FROM_SERVICE_PARAM, //Name of the parameter
            "applicable_values" => ["0"], //All possible values
            "default_value" => ["0"], //Default selected value(s)
            'is_multiple' => true, // Whether or not to allow multiple selection
            'service_level_visibility' => true
        ],
        [
            "name" => self::REFERENCE_FROM_SERIES_PARAM,
            "applicable_values" => [],
            "default_value" => [],
            'is_multiple' => true,
            'service_level_visibility' => false
        ],
        [
            "name" => self::BACK_DATE_ALLOW_PARAM,
            "applicable_values" => self::BACK_DATE_ALLOW_PARAM_VALUES,
            "default_value" => ['yes'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ],
        [
            "name" => self::FUTURE_DATE_ALLOW_PARAM,
            "applicable_values" => self::FUTURE_DATE_ALLOW_PARAM_VALUES,
            "default_value" => ['yes'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ],
        [
            "name" => self::GOODS_SERVICES_PARAM,
            "applicable_values" => self::GOODS_SERVICES_PARAM_VALUES,
            "default_value" => ['Goods'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ],
        [
            "name" => self::TAX_REQUIRED_PARAM,
            "applicable_values" => self::TAX_REQUIRED_PARAM_VALUES,
            "default_value" => ['yes'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ]
    ];
    const DN_SERVICE_PARAMETERS = [
        [
            "name" => self::REFERENCE_FROM_SERVICE_PARAM, //Name of the parameter
            "applicable_values" => ["0", ConstantHelper::SO_SERVICE_ALIAS], //All possible values
            "default_value" => ["0", ConstantHelper::SO_SERVICE_ALIAS], //Default selected value(s)
            'is_multiple' => true, // Whether or not to allow multiple selection
            'service_level_visibility' => true
        ],
        [
            "name" => self::REFERENCE_FROM_SERIES_PARAM,
            "applicable_values" => [],
            "default_value" => [],
            'is_multiple' => true,
            'is_visible' => true,
            'service_level_visibility' => false
        ],
        [
            "name" => self::INVOICE_TO_FOLLOW_PARAM,
            "applicable_values" => self::INVOICE_TO_FOLLOW_PARAM_VALUES,
            "default_value" => ['no'],
            'is_multiple' => false,
            'is_visible' => true,
            'service_level_visibility' => true
        ],
        [
            "name" => self::BACK_DATE_ALLOW_PARAM,
            "applicable_values" => self::BACK_DATE_ALLOW_PARAM_VALUES,
            "default_value" => ['yes'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ],
        [
            "name" => self::FUTURE_DATE_ALLOW_PARAM,
            "applicable_values" => self::FUTURE_DATE_ALLOW_PARAM_VALUES,
            "default_value" => ['yes'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ],
        [
            "name" => self::GOODS_SERVICES_PARAM,
            "applicable_values" => self::GOODS_SERVICES_PARAM_VALUES,
            "default_value" => ['Goods'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ],
        [
            "name" => self::GL_POSTING_REQUIRED_PARAM,
            "applicable_values" => self::GL_POSTING_REQUIRED_PARAM_VALUES,
            "default_value" => ['no'],
            'is_multiple' => false,
            'service_level_visibility' => true,
            'type' => self::GL_PARAMETERS
        ],
        [
            "name" => self::GL_POSTING_SERIES_PARAM,
            "applicable_values" => [],
            "default_value" => [],
            'is_multiple' => true,
            'service_level_visibility' => false,
            'type' => self::GL_PARAMETERS
        ],
        [
            "name" => self::GL_SEPERATE_DISCOUNT_PARAM,
            "applicable_values" => self::GL_SEPERATE_DISCOUNT_PARAM_VALUE,
            "default_value" => ['no'],
            'is_multiple' => false,
            'service_level_visibility' => true,
            'type' => self::GL_PARAMETERS
        ],
        [
            "name" => self::POST_ON_ARROVE_PARAM,
            "applicable_values" => self::POST_ON_ARROVE_PARAM_VALUES,
            "default_value" => ['no'],
            'is_multiple' => false,
            'service_level_visibility' => true,
            'type' => self::GL_PARAMETERS
        ],
        [
            "name" => self::TAX_REQUIRED_PARAM,
            "applicable_values" => self::TAX_REQUIRED_PARAM_VALUES,
            "default_value" => ['yes'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ]
    ];
    const SINV_SERVICE_PARAMETERS = [
        [
            "name" => self::REFERENCE_FROM_SERVICE_PARAM, //Name of the parameter
            "applicable_values" => ["0", ConstantHelper::DELIVERY_CHALLAN_SERVICE_ALIAS], //All possible values
            "default_value" => ["0", ConstantHelper::DELIVERY_CHALLAN_SERVICE_ALIAS], //Default selected value(s)
            'is_multiple' => true, // Whether or not to allow multiple selection
            'service_level_visibility' => true
        ],
        [
            "name" => self::REFERENCE_FROM_SERIES_PARAM,
            "applicable_values" => [],
            "default_value" => [],
            'is_multiple' => true,
            'service_level_visibility' => false
        ],
        [
            "name" => self::BACK_DATE_ALLOW_PARAM,
            "applicable_values" => self::BACK_DATE_ALLOW_PARAM_VALUES,
            "default_value" => ['yes'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ],
        [
            "name" => self::FUTURE_DATE_ALLOW_PARAM,
            "applicable_values" => self::FUTURE_DATE_ALLOW_PARAM_VALUES,
            "default_value" => ['yes'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ],
        [
            "name" => self::GOODS_SERVICES_PARAM,
            "applicable_values" => self::GOODS_SERVICES_PARAM_VALUES,
            "default_value" => ['Goods'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ],
        [
            "name" => self::GL_POSTING_REQUIRED_PARAM,
            "applicable_values" => self::GL_POSTING_REQUIRED_PARAM_VALUES,
            "default_value" => ['no'],
            'is_multiple' => false,
            'service_level_visibility' => true,
            'type' => self::GL_PARAMETERS
        ],
        [
            "name" => self::GL_POSTING_SERIES_PARAM,
            "applicable_values" => [],
            "default_value" => [],
            'is_multiple' => true,
            'service_level_visibility' => false,
            'type' => self::GL_PARAMETERS
        ],
        [
            "name" => self::GL_SEPERATE_DISCOUNT_PARAM,
            "applicable_values" => self::GL_SEPERATE_DISCOUNT_PARAM_VALUE,
            "default_value" => ['no'],
            'is_multiple' => false,
            'service_level_visibility' => true,
            'type' => self::GL_PARAMETERS
        ],
        [
            "name" => self::POST_ON_ARROVE_PARAM,
            "applicable_values" => self::POST_ON_ARROVE_PARAM_VALUES,
            "default_value" => ['no'],
            'is_multiple' => false,
            'service_level_visibility' => true,
            'type' => self::GL_PARAMETERS
        ],
        [
            "name" => self::TAX_REQUIRED_PARAM,
            "applicable_values" => self::TAX_REQUIRED_PARAM_VALUES,
            "default_value" => ['yes'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ]
    ];
    const LEASE_INV_SERVICE_PARAMETERS = [
        [
            "name" => self::REFERENCE_FROM_SERVICE_PARAM, //Name of the parameter
            "applicable_values" => [ConstantHelper::LAND_LEASE], //All possible values
            "default_value" => [ConstantHelper::LAND_LEASE], //Default selected value(s)
            'is_multiple' => true, // Whether or not to allow multiple selection
            'service_level_visibility' => true
        ],
        [
            "name" => self::REFERENCE_FROM_SERIES_PARAM,
            "applicable_values" => [],
            "default_value" => [],
            'is_multiple' => true,
            'service_level_visibility' => false
        ],
        [
            "name" => self::BACK_DATE_ALLOW_PARAM,
            "applicable_values" => self::BACK_DATE_ALLOW_PARAM_VALUES,
            "default_value" => ['yes'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ],
        [
            "name" => self::FUTURE_DATE_ALLOW_PARAM,
            "applicable_values" => self::FUTURE_DATE_ALLOW_PARAM_VALUES,
            "default_value" => ['yes'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ],
        [
            "name" => self::GOODS_SERVICES_PARAM,
            "applicable_values" => self::GOODS_SERVICES_PARAM_VALUES,
            "default_value" => ['Service'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ],
        [
            "name" => self::GL_POSTING_REQUIRED_PARAM,
            "applicable_values" => self::GL_POSTING_REQUIRED_PARAM_VALUES,
            "default_value" => ['no'],
            'is_multiple' => false,
            'service_level_visibility' => true,
            'type' => self::GL_PARAMETERS
        ],
        [
            "name" => self::GL_POSTING_SERIES_PARAM,
            "applicable_values" => [],
            "default_value" => [],
            'is_multiple' => true,
            'service_level_visibility' => false,
            'type' => self::GL_PARAMETERS
        ],
        [
            "name" => self::GL_SEPERATE_DISCOUNT_PARAM,
            "applicable_values" => self::GL_SEPERATE_DISCOUNT_PARAM_VALUE,
            "default_value" => ['no'],
            'is_multiple' => false,
            'service_level_visibility' => true,
            'type' => self::GL_PARAMETERS
        ],
        [
            "name" => self::POST_ON_ARROVE_PARAM,
            "applicable_values" => self::POST_ON_ARROVE_PARAM_VALUES,
            "default_value" => ['no'],
            'is_multiple' => false,
            'service_level_visibility' => true,
            'type' => self::GL_PARAMETERS
        ],
        [
            "name" => self::TAX_REQUIRED_PARAM,
            "applicable_values" => self::TAX_REQUIRED_PARAM_VALUES,
            "default_value" => ['yes'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ]
    ];
    const LOAN_SERVICE_PARAMETERS = [
        [
            "name" => self::GL_POSTING_REQUIRED_PARAM,
            "applicable_values" => self::GL_POSTING_REQUIRED_PARAM_VALUES,
            "default_value" => ['no'],
            'is_multiple' => false,
            'service_level_visibility' => true,
            'type' => self::GL_PARAMETERS
        ],
        [
            "name" => self::GL_POSTING_SERIES_PARAM,
            "applicable_values" => [],
            "default_value" => [],
            'is_multiple' => true,
            'service_level_visibility' => false,
            'type' => self::GL_PARAMETERS
        ],
        [
            "name" => self::GL_SEPERATE_DISCOUNT_PARAM,
            "applicable_values" => self::GL_SEPERATE_DISCOUNT_PARAM_VALUE,
            "default_value" => ['no'],
            'is_multiple' => false,
            'service_level_visibility' => true,
            'type' => self::GL_PARAMETERS
        ],
        [
            "name" => self::POST_ON_ARROVE_PARAM,
            "applicable_values" => self::POST_ON_ARROVE_PARAM_VALUES,
            "default_value" => ['no'],
            'is_multiple' => false,
            'service_level_visibility' => true,
            'type' => self::GL_PARAMETERS
        ]
    ];
    const LOAN_RECOVERY_SERVICE_PARAMETERS = [
        [
            "name" => self::GL_POSTING_REQUIRED_PARAM,
            "applicable_values" => self::GL_POSTING_REQUIRED_PARAM_VALUES,
            "default_value" => ['no'],
            'is_multiple' => false,
            'service_level_visibility' => true,
            'type' => self::GL_PARAMETERS
        ],
        [
            "name" => self::GL_POSTING_SERIES_PARAM,
            "applicable_values" => [],
            "default_value" => [],
            'is_multiple' => true,
            'service_level_visibility' => false,
            'type' => self::GL_PARAMETERS
        ],
        [
            "name" => self::GL_SEPERATE_DISCOUNT_PARAM,
            "applicable_values" => self::GL_SEPERATE_DISCOUNT_PARAM_VALUE,
            "default_value" => ['no'],
            'is_multiple' => false,
            'service_level_visibility' => true,
            'type' => self::GL_PARAMETERS
        ],
        [
            "name" => self::POST_ON_ARROVE_PARAM,
            "applicable_values" => self::POST_ON_ARROVE_PARAM_VALUES,
            "default_value" => ['no'],
            'is_multiple' => false,
            'service_level_visibility' => true,
            'type' => self::GL_PARAMETERS
        ]
    ];
    const LOAN_SETTLEMENT_SERVICE_PARAMETERS = [
        [
            "name" => self::GL_POSTING_REQUIRED_PARAM,
            "applicable_values" => self::GL_POSTING_REQUIRED_PARAM_VALUES,
            "default_value" => ['no'],
            'is_multiple' => false,
            'service_level_visibility' => true,
            'type' => self::GL_PARAMETERS
        ],
        [
            "name" => self::GL_POSTING_SERIES_PARAM,
            "applicable_values" => [],
            "default_value" => [],
            'is_multiple' => true,
            'service_level_visibility' => false,
            'type' => self::GL_PARAMETERS
        ],
        [
            "name" => self::GL_SEPERATE_DISCOUNT_PARAM,
            "applicable_values" => self::GL_SEPERATE_DISCOUNT_PARAM_VALUE,
            "default_value" => ['no'],
            'is_multiple' => false,
            'service_level_visibility' => true,
            'type' => self::GL_PARAMETERS
        ],
        [
            "name" => self::POST_ON_ARROVE_PARAM,
            "applicable_values" => self::POST_ON_ARROVE_PARAM_VALUES,
            "default_value" => ['no'],
            'is_multiple' => false,
            'service_level_visibility' => true,
            'type' => self::GL_PARAMETERS
        ]
    ];



    const DIS_SERVICE_PARAMETERS = [
        [
            "name" => self::GL_POSTING_REQUIRED_PARAM,
            "applicable_values" => self::GL_POSTING_REQUIRED_PARAM_VALUES,
            "default_value" => ['no'],
            'is_multiple' => false,
            'service_level_visibility' => true,
            'type' => self::GL_PARAMETERS
        ],
        [
            "name" => self::GL_POSTING_SERIES_PARAM,
            "applicable_values" => [],
            "default_value" => [],
            'is_multiple' => true,
            'service_level_visibility' => false,
            'type' => self::GL_PARAMETERS
        ],
        [
            "name" => self::GL_SEPERATE_DISCOUNT_PARAM,
            "applicable_values" => self::GL_SEPERATE_DISCOUNT_PARAM_VALUE,
            "default_value" => ['no'],
            'is_multiple' => false,
            'service_level_visibility' => true,
            'type' => self::GL_PARAMETERS
        ],
        [
            "name" => self::POST_ON_ARROVE_PARAM,
            "applicable_values" => self::POST_ON_ARROVE_PARAM_VALUES,
            "default_value" => ['no'],
            'is_multiple' => false,
            'service_level_visibility' => true,
            'type' => self::GL_PARAMETERS
        ]
    ];
    const DN_CUM_INVOICE_SERVICE_PARAMETERS = [
        [
            "name" => self::REFERENCE_FROM_SERVICE_PARAM, //Name of the parameter
            "applicable_values" => ["0", ConstantHelper::SO_SERVICE_ALIAS, ConstantHelper::DELIVERY_CHALLAN_SERVICE_ALIAS, ConstantHelper::SI_SERVICE_ALIAS], //All possible values
            "default_value" => ["0", ConstantHelper::SO_SERVICE_ALIAS], //Default selected value(s)
            'is_multiple' => true, // Whether or not to allow multiple selection
            'service_level_visibility' => true
        ],
        [
            "name" => self::REFERENCE_FROM_SERIES_PARAM,
            "applicable_values" => [],
            "default_value" => [],
            'is_multiple' => true,
            'service_level_visibility' => false
        ],
        [
            "name" => self::BACK_DATE_ALLOW_PARAM,
            "applicable_values" => self::BACK_DATE_ALLOW_PARAM_VALUES,
            "default_value" => ['yes'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ],
        [
            "name" => self::FUTURE_DATE_ALLOW_PARAM,
            "applicable_values" => self::FUTURE_DATE_ALLOW_PARAM_VALUES,
            "default_value" => ['yes'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ],
        [
            "name" => self::GOODS_SERVICES_PARAM,
            "applicable_values" => self::GOODS_SERVICES_PARAM_VALUES,
            "default_value" => ['Goods'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ],
        [
            "name" => self::GL_POSTING_REQUIRED_PARAM,
            "applicable_values" => self::GL_POSTING_REQUIRED_PARAM_VALUES,
            "default_value" => ['no'],
            'is_multiple' => false,
            'service_level_visibility' => true,
            'type' => self::GL_PARAMETERS
        ],
        [
            "name" => self::GL_POSTING_SERIES_PARAM,
            "applicable_values" => [],
            "default_value" => [],
            'is_multiple' => true,
            'service_level_visibility' => false,
            'type' => self::GL_PARAMETERS
        ],
        [
            "name" => self::POST_ON_ARROVE_PARAM,
            "applicable_values" => self::POST_ON_ARROVE_PARAM_VALUES,
            "default_value" => ['no'],
            'is_multiple' => false,
            'service_level_visibility' => true,
            'type' => self::GL_PARAMETERS
        ],
        [
            "name" => self::GL_SEPERATE_DISCOUNT_PARAM,
            "applicable_values" => self::GL_SEPERATE_DISCOUNT_PARAM_VALUE,
            "default_value" => ['no'],
            'is_multiple' => false,
            'service_level_visibility' => true,
            'type' => self::GL_PARAMETERS
        ],
        [
            "name" => self::TAX_REQUIRED_PARAM,
            "applicable_values" => self::TAX_REQUIRED_PARAM_VALUES,
            "default_value" => ['yes'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ]
    ];

    /*BOM PO PI*/
    const PI_SERVICE_PARAMETERS = [
        [
            "name" => self::REFERENCE_FROM_SERVICE_PARAM, //Name of the parameter
            "applicable_values" => ["0", ConstantHelper::SO_SERVICE_ALIAS], //All possible values
            "default_value" => ["0", ConstantHelper::SO_SERVICE_ALIAS], //Default selected value(s)
            'is_multiple' => true, // Whether or not to allow multiple selection
            'service_level_visibility' => true, // Whether or not to show this parameter in UI
        ],
        [
            "name" => self::REFERENCE_FROM_SERIES_PARAM,
            "applicable_values" => [],
            "default_value" => [],
            'is_multiple' => true,
            'service_level_visibility' => false
        ],
        [
            "name" => self::BACK_DATE_ALLOW_PARAM,
            "applicable_values" => self::BACK_DATE_ALLOW_PARAM_VALUES,
            "default_value" => ['yes'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ],
        [
            "name" => self::FUTURE_DATE_ALLOW_PARAM,
            "applicable_values" => self::FUTURE_DATE_ALLOW_PARAM_VALUES,
            "default_value" => ['yes'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ]
    ];
    const BOM_SERVICE_PARAMETERS = [
        [
            "name" => self::REFERENCE_FROM_SERVICE_PARAM,
            "applicable_values" => ["0", ConstantHelper::COMMERCIAL_BOM_SERVICE_ALIAS],
            "default_value" => ["0", ConstantHelper::COMMERCIAL_BOM_SERVICE_ALIAS],
            'is_multiple' => true,
            'service_level_visibility' => true,
        ],
        [
            "name" => self::REFERENCE_FROM_SERIES_PARAM,
            "applicable_values" => [],
            "default_value" => [],
            'is_multiple' => true,
            'service_level_visibility' => false
        ],
        [
            "name" => self::BACK_DATE_ALLOW_PARAM,
            "applicable_values" => self::BACK_DATE_ALLOW_PARAM_VALUES,
            "default_value" => ['yes'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ],
        [
            "name" => self::FUTURE_DATE_ALLOW_PARAM,
            "applicable_values" => self::FUTURE_DATE_ALLOW_PARAM_VALUES,
            "default_value" => ['yes'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ],
        [
            "name" => self::BOM_CONSUMPTION_METHOD,
            "applicable_values" => self::BOM_CONSUMPTION_METHOD_VALUES,
            "default_value" => ['manual'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ],
        [
            "name" => self::BOM_SECTION_REQUIRED,
            "applicable_values" => self::BOM_SECTION_REQUIRED_VALUES,
            "default_value" => ['manual'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ],
        [
            "name" => self::BOM_SUB_SECTION_REQUIRED,
            "applicable_values" => self::BOM_SUB_SECTION_REQUIRED_VALUES,
            "default_value" => ['manual'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ],
        [
            "name" => self::BOM_STATION_REQUIRED,
            "applicable_values" => self::BOM_STATION_REQUIRED_VALUES,
            "default_value" => ['manual'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ],
        [
            "name" => self::BOM_SUPERCEDE_COST_REQUIRED,
            "applicable_values" => self::BOM_SUPERCEDE_COST_REQUIRED_VALUES,
            "default_value" => ['manual'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ],
        [
            "name" => self::BOM_COMPONENT_WASTE_REQUIRED,
            "applicable_values" => self::BOM_COMPONENT_WASTE_REQUIRED_VALUES,
            "default_value" => ['manual'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ],
        [
            "name" => self::BOM_COMPONENT_OVERHEAD_REQUIRED,
            "applicable_values" => self::BOM_COMPONENT_OVERHEAD_REQUIRED_VALUES,
            "default_value" => ['manual'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ]
    ];

    const COMMON_SERVICE_PARAMETERS = [
        [
            "name" => self::BACK_DATE_ALLOW_PARAM,
            "applicable_values" => self::BACK_DATE_ALLOW_PARAM_VALUES,
            "default_value" => ['yes'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ],
        [
            "name" => self::FUTURE_DATE_ALLOW_PARAM,
            "applicable_values" => self::FUTURE_DATE_ALLOW_PARAM_VALUES,
            "default_value" => ['yes'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ]
    ];

    const PV_SERVICE_PARAMETERS = [
        [
            "name" => self::BACK_DATE_ALLOW_PARAM,
            "applicable_values" => self::BACK_DATE_ALLOW_PARAM_VALUES,
            "default_value" => ['yes'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ],
        [
            "name" => self::FUTURE_DATE_ALLOW_PARAM,
            "applicable_values" => self::FUTURE_DATE_ALLOW_PARAM_VALUES,
            "default_value" => ['yes'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ],
        [
            "name" => self::GL_POSTING_REQUIRED_PARAM,
            "applicable_values" => self::GL_POSTING_REQUIRED_PARAM_VALUES,
            "default_value" => ['no'],
            'is_multiple' => false,
            'service_level_visibility' => true,
            'type' => self::GL_PARAMETERS
        ],
        [
            "name" => self::GL_POSTING_SERIES_PARAM,
            "applicable_values" => [],
            "default_value" => [],
            'is_multiple' => true,
            'service_level_visibility' => false,
            'type' => self::GL_PARAMETERS
        ],
        [
            "name" => self::GL_SEPERATE_DISCOUNT_PARAM,
            "applicable_values" => self::GL_SEPERATE_DISCOUNT_PARAM_VALUE,
            "default_value" => ['no'],
            'is_multiple' => false,
            'service_level_visibility' => true,
            'type' => self::GL_PARAMETERS
        ],
        [
            "name" => self::POST_ON_ARROVE_PARAM,
            "applicable_values" => self::POST_ON_ARROVE_PARAM_VALUES,
            "default_value" => ['no'],
            'is_multiple' => false,
            'service_level_visibility' => true,
            'type' => self::GL_PARAMETERS
        ]
    ];
    const PO_SERVICE_PARAMETERS = [
        [
            "name" => self::REFERENCE_FROM_SERVICE_PARAM, //Name of the parameter
            "applicable_values" => ["0", ConstantHelper::PI_SERVICE_ALIAS], //All possible values
            "default_value" => ["0", ConstantHelper::PI_SERVICE_ALIAS], //Default selected value(s)
            'is_multiple' => true, // Whether or not to allow multiple selection
            'service_level_visibility' => true, // Whether or not to show this parameter in UI
        ],
        [
            "name" => self::REFERENCE_FROM_SERIES_PARAM,
            "applicable_values" => [],
            "default_value" => [],
            'is_multiple' => true,
            'service_level_visibility' => false
        ],
        [
            "name" => self::BACK_DATE_ALLOW_PARAM,
            "applicable_values" => self::BACK_DATE_ALLOW_PARAM_VALUES,
            "default_value" => ['yes'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ],
        [
            "name" => self::FUTURE_DATE_ALLOW_PARAM,
            "applicable_values" => self::FUTURE_DATE_ALLOW_PARAM_VALUES,
            "default_value" => ['yes'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ],
        [
            "name" => self::TAX_REQUIRED_PARAM,
            "applicable_values" => self::TAX_REQUIRED_PARAM_VALUES,
            "default_value" => ['yes'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ]
    ];

    const SUPPLIER_INVOICE_SERVICE_PARAMETERS = [
        [
            "name" => self::REFERENCE_FROM_SERVICE_PARAM, //Name of the parameter
            "applicable_values" => ["0", ConstantHelper::PO_SERVICE_ALIAS], //All possible values
            "default_value" => ["0", ConstantHelper::PO_SERVICE_ALIAS], //Default selected value(s)
            'is_multiple' => true, // Whether or not to allow multiple selection
            'service_level_visibility' => true, // Whether or not to show this parameter in UI
        ],
        [
            "name" => self::REFERENCE_FROM_SERIES_PARAM,
            "applicable_values" => [],
            "default_value" => [],
            'is_multiple' => true,
            'service_level_visibility' => false
        ],
        [
            "name" => self::BACK_DATE_ALLOW_PARAM,
            "applicable_values" => self::BACK_DATE_ALLOW_PARAM_VALUES,
            "default_value" => ['yes'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ],
        [
            "name" => self::FUTURE_DATE_ALLOW_PARAM,
            "applicable_values" => self::FUTURE_DATE_ALLOW_PARAM_VALUES,
            "default_value" => ['yes'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ],
        [
            "name" => self::TAX_REQUIRED_PARAM,
            "applicable_values" => self::TAX_REQUIRED_PARAM_VALUES,
            "default_value" => ['yes'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ]
    ];
    /*BOM PO PI*/
    const MRN_SERVICE_PARAMETERS = [
        [
            "name" => self::REFERENCE_FROM_SERVICE_PARAM, //Name of the parameter
            "applicable_values" => ["0", ConstantHelper::PO_SERVICE_ALIAS], //All possible values
            "default_value" => ["0", ConstantHelper::PO_SERVICE_ALIAS], //Default selected value(s)
            'is_multiple' => true, // Whether or not to allow multiple selection
            'service_level_visibility' => true, // Whether or not to show this parameter in UI
        ],
        [
            "name" => self::REFERENCE_FROM_SERIES_PARAM,
            "applicable_values" => [],
            "default_value" => [],
            'is_multiple' => true,
            'service_level_visibility' => false
        ],
        [
            "name" => self::BACK_DATE_ALLOW_PARAM,
            "applicable_values" => self::BACK_DATE_ALLOW_PARAM_VALUES,
            "default_value" => ['no'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ],
        [
            "name" => self::FUTURE_DATE_ALLOW_PARAM,
            "applicable_values" => self::FUTURE_DATE_ALLOW_PARAM_VALUES,
            "default_value" => ['yes'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ],
        [
            "name" => self::GL_POSTING_REQUIRED_PARAM,
            "applicable_values" => self::GL_POSTING_REQUIRED_PARAM_VALUES,
            "default_value" => ['no'],
            'is_multiple' => false,
            'service_level_visibility' => true,
            'type' => self::GL_PARAMETERS
        ],
        [
            "name" => self::GL_POSTING_SERIES_PARAM,
            "applicable_values" => [],
            "default_value" => [],
            'is_multiple' => true,
            'service_level_visibility' => false,
            'type' => self::GL_PARAMETERS
        ],
        [
            "name" => self::POST_ON_ARROVE_PARAM,
            "applicable_values" => self::POST_ON_ARROVE_PARAM_VALUES,
            "default_value" => ['no'],
            'is_multiple' => false,
            'service_level_visibility' => true,
            'type' => self::GL_PARAMETERS
        ],
        [
            "name" => self::GL_SEPERATE_DISCOUNT_PARAM,
            "applicable_values" => self::GL_SEPERATE_DISCOUNT_PARAM_VALUE,
            "default_value" => ['no'],
            'is_multiple' => false,
            'service_level_visibility' => true,
            'type' => self::GL_PARAMETERS
        ],
        [
            "name" => self::TAX_REQUIRED_PARAM,
            "applicable_values" => self::TAX_REQUIRED_PARAM_VALUES,
            "default_value" => ['yes'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ],
        [
            "name" => self::BILL_TO_FOLLOW_PARAM,
            "applicable_values" => self::BILL_TO_FOLLOW_PARAM_VALUES,
            "default_value" => ['yes'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ]
    ];
    const PB_SERVICE_PARAMETERS = [
        [
            "name" => self::REFERENCE_FROM_SERVICE_PARAM, //Name of the parameter
            "applicable_values" => ["0", ConstantHelper::MRN_SERVICE_ALIAS], //All possible values
            "default_value" => ["0", ConstantHelper::MRN_SERVICE_ALIAS], //Default selected value(s)
            'is_multiple' => true, // Whether or not to allow multiple selection
            'service_level_visibility' => true
        ],
        [
            "name" => self::REFERENCE_FROM_SERIES_PARAM,
            "applicable_values" => [],
            "default_value" => [],
            'is_multiple' => true,
            'service_level_visibility' => false
        ],
        [
            "name" => self::BACK_DATE_ALLOW_PARAM,
            "applicable_values" => self::BACK_DATE_ALLOW_PARAM_VALUES,
            "default_value" => ['yes'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ],
        [
            "name" => self::FUTURE_DATE_ALLOW_PARAM,
            "applicable_values" => self::FUTURE_DATE_ALLOW_PARAM_VALUES,
            "default_value" => ['yes'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ],
        [
            "name" => self::GL_POSTING_REQUIRED_PARAM,
            "applicable_values" => self::GL_POSTING_REQUIRED_PARAM_VALUES,
            "default_value" => ['no'],
            'is_multiple' => false,
            'service_level_visibility' => true,
            'type' => self::GL_PARAMETERS
        ],
        [
            "name" => self::GL_POSTING_SERIES_PARAM,
            "applicable_values" => [],
            "default_value" => [],
            'is_multiple' => true,
            'service_level_visibility' => false,
            'type' => self::GL_PARAMETERS
        ],
        [
            "name" => self::POST_ON_ARROVE_PARAM,
            "applicable_values" => self::POST_ON_ARROVE_PARAM_VALUES,
            "default_value" => ['no'],
            'is_multiple' => false,
            'service_level_visibility' => true,
            'type' => self::GL_PARAMETERS
        ],
        [
            "name" => self::GL_SEPERATE_DISCOUNT_PARAM,
            "applicable_values" => self::GL_SEPERATE_DISCOUNT_PARAM_VALUE,
            "default_value" => ['no'],
            'is_multiple' => false,
            'service_level_visibility' => true,
            'type' => self::GL_PARAMETERS
        ],
        [
            "name" => self::TAX_REQUIRED_PARAM,
            "applicable_values" => self::TAX_REQUIRED_PARAM_VALUES,
            "default_value" => ['yes'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ]
    ];
    const EXPENSE_ADVISE_SERVICE_PARAMETERS = [
        [
            "name" => self::REFERENCE_FROM_SERVICE_PARAM, //Name of the parameter
            "applicable_values" => ["0", ConstantHelper::SO_SERVICE_ALIAS, ConstantHelper::PO_SERVICE_ALIAS], //All possible values
            "default_value" => ["0", ConstantHelper::SO_SERVICE_ALIAS], //Default selected value(s)
            'is_multiple' => true, // Whether or not to allow multiple selection
            'service_level_visibility' => true
        ],
        [
            "name" => self::REFERENCE_FROM_SERIES_PARAM,
            "applicable_values" => [],
            "default_value" => [],
            'is_multiple' => true,
            'service_level_visibility' => false
        ],
        [
            "name" => self::BACK_DATE_ALLOW_PARAM,
            "applicable_values" => self::BACK_DATE_ALLOW_PARAM_VALUES,
            "default_value" => ['yes'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ],
        [
            "name" => self::FUTURE_DATE_ALLOW_PARAM,
            "applicable_values" => self::FUTURE_DATE_ALLOW_PARAM_VALUES,
            "default_value" => ['yes'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ],
        [
            "name" => self::GL_POSTING_REQUIRED_PARAM,
            "applicable_values" => self::GL_POSTING_REQUIRED_PARAM_VALUES,
            "default_value" => ['no'],
            'is_multiple' => false,
            'service_level_visibility' => true,
            'type' => self::GL_PARAMETERS
        ],
        [
            "name" => self::GL_POSTING_SERIES_PARAM,
            "applicable_values" => [],
            "default_value" => [],
            'is_multiple' => true,
            'service_level_visibility' => false,
            'type' => self::GL_PARAMETERS
        ],
        [
            "name" => self::POST_ON_ARROVE_PARAM,
            "applicable_values" => self::POST_ON_ARROVE_PARAM_VALUES,
            "default_value" => ['no'],
            'is_multiple' => false,
            'service_level_visibility' => true,
            'type' => self::GL_PARAMETERS
        ],
        [
            "name" => self::GL_SEPERATE_DISCOUNT_PARAM,
            "applicable_values" => self::GL_SEPERATE_DISCOUNT_PARAM_VALUE,
            "default_value" => ['no'],
            'is_multiple' => false,
            'service_level_visibility' => true,
            'type' => self::GL_PARAMETERS
        ],
        [
            "name" => self::TAX_REQUIRED_PARAM,
            "applicable_values" => self::TAX_REQUIRED_PARAM_VALUES,
            "default_value" => ['yes'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ]
    ];

    const PURCHASE_RETURN_SERVICE_PARAMETERS = [
        [
            "name" => self::REFERENCE_FROM_SERVICE_PARAM, //Name of the parameter
            "applicable_values" => ["0", ConstantHelper::MRN_SERVICE_ALIAS], //All possible values
            "default_value" => ["0", ConstantHelper::MRN_SERVICE_ALIAS], //Default selected value(s)
            'is_multiple' => true, // Whether or not to allow multiple selection
            'service_level_visibility' => true
        ],
        [
            "name" => self::REFERENCE_FROM_SERIES_PARAM,
            "applicable_values" => [],
            "default_value" => [],
            'is_multiple' => true,
            'service_level_visibility' => false
        ],
        [
            "name" => self::BACK_DATE_ALLOW_PARAM,
            "applicable_values" => self::BACK_DATE_ALLOW_PARAM_VALUES,
            "default_value" => ['no'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ],
        [
            "name" => self::FUTURE_DATE_ALLOW_PARAM,
            "applicable_values" => self::FUTURE_DATE_ALLOW_PARAM_VALUES,
            "default_value" => ['yes'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ],
        [
            "name" => self::GL_POSTING_REQUIRED_PARAM,
            "applicable_values" => self::GL_POSTING_REQUIRED_PARAM_VALUES,
            "default_value" => ['no'],
            'is_multiple' => false,
            'service_level_visibility' => true,
            'type' => self::GL_PARAMETERS
        ],
        [
            "name" => self::GL_POSTING_SERIES_PARAM,
            "applicable_values" => [],
            "default_value" => [],
            'is_multiple' => true,
            'service_level_visibility' => false,
            'type' => self::GL_PARAMETERS
        ],
        [
            "name" => self::POST_ON_ARROVE_PARAM,
            "applicable_values" => self::POST_ON_ARROVE_PARAM_VALUES,
            "default_value" => ['no'],
            'is_multiple' => false,
            'service_level_visibility' => true,
            'type' => self::GL_PARAMETERS
        ],
        [
            "name" => self::GL_SEPERATE_DISCOUNT_PARAM,
            "applicable_values" => self::GL_SEPERATE_DISCOUNT_PARAM_VALUE,
            "default_value" => ['no'],
            'is_multiple' => false,
            'service_level_visibility' => true,
            'type' => self::GL_PARAMETERS
        ],
        [
            "name" => self::TAX_REQUIRED_PARAM,
            "applicable_values" => self::TAX_REQUIRED_PARAM_VALUES,
            "default_value" => ['yes'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ],
        [
            "name" => self::PR_QTY_TYPE_PARAM,
            "applicable_values" => self::PR_QTY_TYPE_VALUES,
            "default_value" => ['rejected'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ]
    ];

    const MATERIAL_ISSUE_SERVICE_PARAMETERS = [
        [
            "name" => self::REFERENCE_FROM_SERVICE_PARAM, //Name of the parameter
            "applicable_values" => ["0"], //All possible values
            "default_value" => ["0"], //Default selected value(s)
            'is_multiple' => true, // Whether or not to allow multiple selection
            'service_level_visibility' => true, // Whether or not to show this parameter in UI
        ],
        [
            "name" => self::REFERENCE_FROM_SERIES_PARAM,
            "applicable_values" => [],
            "default_value" => [],
            'is_multiple' => true,
            'service_level_visibility' => false
        ],
        [
            "name" => self::BACK_DATE_ALLOW_PARAM,
            "applicable_values" => self::BACK_DATE_ALLOW_PARAM_VALUES,
            "default_value" => ['no'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ],
        [
            "name" => self::FUTURE_DATE_ALLOW_PARAM,
            "applicable_values" => self::FUTURE_DATE_ALLOW_PARAM_VALUES,
            "default_value" => ['yes'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ],
        [
            "name" => self::TAX_REQUIRED_PARAM,
            "applicable_values" => self::TAX_REQUIRED_PARAM_VALUES,
            "default_value" => ['yes'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ]
    ];

    const STOCK_ADJUSTMENT_SERVICE_PARAMETERS = [
        [
            "name" => self::REFERENCE_FROM_SERVICE_PARAM, //Name of the parameter
            "applicable_values" => ["0"], //All possible values
            "default_value" => ["0"], //Default selected value(s)
            'is_multiple' => true, // Whether or not to allow multiple selection
            'service_level_visibility' => true, // Whether or not to show this parameter in UI
        ],
        [
            "name" => self::REFERENCE_FROM_SERIES_PARAM,
            "applicable_values" => [],
            "default_value" => [],
            'is_multiple' => true,
            'service_level_visibility' => false
        ],
        [
            "name" => self::BACK_DATE_ALLOW_PARAM,
            "applicable_values" => self::BACK_DATE_ALLOW_PARAM_VALUES,
            "default_value" => ['no'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ],
        [
            "name" => self::FUTURE_DATE_ALLOW_PARAM,
            "applicable_values" => self::FUTURE_DATE_ALLOW_PARAM_VALUES,
            "default_value" => ['yes'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ],
        [
            "name" => self::TAX_REQUIRED_PARAM,
            "applicable_values" => self::TAX_REQUIRED_PARAM_VALUES,
            "default_value" => ['yes'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ]
    ];

    const PHYSICAL_STOCK_TAKE_SERVICE_PARAMETERS = [
        [
            "name" => self::REFERENCE_FROM_SERVICE_PARAM, //Name of the parameter
            "applicable_values" => ["0"], //All possible values
            "default_value" => ["0"], //Default selected value(s)
            'is_multiple' => true, // Whether or not to allow multiple selection
            'service_level_visibility' => true, // Whether or not to show this parameter in UI
        ],
        [
            "name" => self::REFERENCE_FROM_SERIES_PARAM,
            "applicable_values" => [],
            "default_value" => [],
            'is_multiple' => true,
            'service_level_visibility' => false
        ],
        [
            "name" => self::BACK_DATE_ALLOW_PARAM,
            "applicable_values" => self::BACK_DATE_ALLOW_PARAM_VALUES,
            "default_value" => ['no'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ],
        [
            "name" => self::FUTURE_DATE_ALLOW_PARAM,
            "applicable_values" => self::FUTURE_DATE_ALLOW_PARAM_VALUES,
            "default_value" => ['yes'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ],
        [
            "name" => self::TAX_REQUIRED_PARAM,
            "applicable_values" => self::TAX_REQUIRED_PARAM_VALUES,
            "default_value" => ['yes'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ]
    ];

    const COMMERCIAL_BOM_SERVICE_PARAMETERS = [
        [
            "name" => self::BACK_DATE_ALLOW_PARAM,
            "applicable_values" => self::BACK_DATE_ALLOW_PARAM_VALUES,
            "default_value" => ['yes'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ],
        [
            "name" => self::FUTURE_DATE_ALLOW_PARAM,
            "applicable_values" => self::FUTURE_DATE_ALLOW_PARAM_VALUES,
            "default_value" => ['yes'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ],
        [
            "name" => self::BOM_CONSUMPTION_METHOD,
            "applicable_values" => self::BOM_CONSUMPTION_METHOD_VALUES,
            "default_value" => ['manual'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ],
        [
            "name" => self::BOM_SECTION_REQUIRED,
            "applicable_values" => self::BOM_SECTION_REQUIRED_VALUES,
            "default_value" => ['manual'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ],
        [
            "name" => self::BOM_SUB_SECTION_REQUIRED,
            "applicable_values" => self::BOM_SUB_SECTION_REQUIRED_VALUES,
            "default_value" => ['manual'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ],
        [
            "name" => self::BOM_STATION_REQUIRED,
            "applicable_values" => self::BOM_STATION_REQUIRED_VALUES,
            "default_value" => ['manual'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ],
        [
            "name" => self::BOM_SUPERCEDE_COST_REQUIRED,
            "applicable_values" => self::BOM_SUPERCEDE_COST_REQUIRED_VALUES,
            "default_value" => ['manual'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ],
        [
            "name" => self::BOM_COMPONENT_WASTE_REQUIRED,
            "applicable_values" => self::BOM_COMPONENT_WASTE_REQUIRED_VALUES,
            "default_value" => ['manual'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ],
        [
            "name" => self::BOM_COMPONENT_OVERHEAD_REQUIRED,
            "applicable_values" => self::BOM_COMPONENT_OVERHEAD_REQUIRED_VALUES,
            "default_value" => ['manual'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ]
    ];

    const PRODUCTION_WORK_ORDER_SERVICE_PARAMETERS = [
        [
            "name" => self::REFERENCE_FROM_SERVICE_PARAM, //Name of the parameter
            "applicable_values" => ["0"], //All possible values
            "default_value" => ["0"], //Default selected value(s)
            'is_multiple' => true, // Whether or not to allow multiple selection
            'service_level_visibility' => true, // Whether or not to show this parameter in UI
        ],
        [
            "name" => self::REFERENCE_FROM_SERIES_PARAM,
            "applicable_values" => [],
            "default_value" => [],
            'is_multiple' => true,
            'service_level_visibility' => false
        ],
        [
            "name" => self::BACK_DATE_ALLOW_PARAM,
            "applicable_values" => self::BACK_DATE_ALLOW_PARAM_VALUES,
            "default_value" => ['yes'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ],
        [
            "name" => self::FUTURE_DATE_ALLOW_PARAM,
            "applicable_values" => self::FUTURE_DATE_ALLOW_PARAM_VALUES,
            "default_value" => ['yes'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ],
        [
            "name" => self::TAX_REQUIRED_PARAM,
            "applicable_values" => self::TAX_REQUIRED_PARAM_VALUES,
            "default_value" => ['yes'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ]
    ];

    const JOB_ORDER_SERVICE_PARAMETERS = [
        [
            "name" => self::REFERENCE_FROM_SERVICE_PARAM, //Name of the parameter
            "applicable_values" => ["0"], //All possible values
            "default_value" => ["0"], //Default selected value(s)
            'is_multiple' => true, // Whether or not to allow multiple selection
            'service_level_visibility' => true, // Whether or not to show this parameter in UI
        ],
        [
            "name" => self::REFERENCE_FROM_SERIES_PARAM,
            "applicable_values" => [],
            "default_value" => [],
            'is_multiple' => true,
            'service_level_visibility' => false
        ],
        [
            "name" => self::BACK_DATE_ALLOW_PARAM,
            "applicable_values" => self::BACK_DATE_ALLOW_PARAM_VALUES,
            "default_value" => ['yes'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ],
        [
            "name" => self::FUTURE_DATE_ALLOW_PARAM,
            "applicable_values" => self::FUTURE_DATE_ALLOW_PARAM_VALUES,
            "default_value" => ['yes'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ],
        [
            "name" => self::TAX_REQUIRED_PARAM,
            "applicable_values" => self::TAX_REQUIRED_PARAM_VALUES,
            "default_value" => ['yes'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ]
    ];

    const MATERIAL_REQUEST_SERVICE_PARAMETERS = [
        [
            "name" => self::REFERENCE_FROM_SERVICE_PARAM, //Name of the parameter
            "applicable_values" => ["0"], //All possible values
            "default_value" => ["0"], //Default selected value(s)
            'is_multiple' => true, // Whether or not to allow multiple selection
            'service_level_visibility' => true, // Whether or not to show this parameter in UI
        ],
        [
            "name" => self::REFERENCE_FROM_SERIES_PARAM,
            "applicable_values" => [],
            "default_value" => [],
            'is_multiple' => true,
            'service_level_visibility' => false
        ],
        [
            "name" => self::BACK_DATE_ALLOW_PARAM,
            "applicable_values" => self::BACK_DATE_ALLOW_PARAM_VALUES,
            "default_value" => ['yes'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ],
        [
            "name" => self::FUTURE_DATE_ALLOW_PARAM,
            "applicable_values" => self::FUTURE_DATE_ALLOW_PARAM_VALUES,
            "default_value" => ['yes'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ]
    ];

    const PRODUCTION_SLIP_SERVICE_PARAMETERS = [
        [
            "name" => self::REFERENCE_FROM_SERVICE_PARAM, //Name of the parameter
            "applicable_values" => ["0"], //All possible values
            "default_value" => ["0"], //Default selected value(s)
            'is_multiple' => true, // Whether or not to allow multiple selection
            'service_level_visibility' => true, // Whether or not to show this parameter in UI
        ],
        [
            "name" => self::REFERENCE_FROM_SERIES_PARAM,
            "applicable_values" => [],
            "default_value" => [],
            'is_multiple' => true,
            'service_level_visibility' => false
        ],
        [
            "name" => self::BACK_DATE_ALLOW_PARAM,
            "applicable_values" => self::BACK_DATE_ALLOW_PARAM_VALUES,
            "default_value" => ['no'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ],
        [
            "name" => self::FUTURE_DATE_ALLOW_PARAM,
            "applicable_values" => self::FUTURE_DATE_ALLOW_PARAM_VALUES,
            "default_value" => ['yes'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ],
        [
            "name" => self::TAX_REQUIRED_PARAM,
            "applicable_values" => self::TAX_REQUIRED_PARAM_VALUES,
            "default_value" => ['yes'],
            'is_multiple' => false,
            'service_level_visibility' => true
        ]
    ];
    /*mrn PB EXPENSE_ADVISE*/
    const APPLICABLE_SERVICE_PARAMETERS = [
        ConstantHelper::SO_SERVICE_ALIAS => self::SO_SERVICE_PARAMETERS,
        ConstantHelper::SQ_SERVICE_ALIAS => self::SQ_SERVICE_PARAMETERS,
        ConstantHelper::SR_SERVICE_ALIAS => self::SR_SERVICE_PARAMETERS,

        ConstantHelper::DELIVERY_CHALLAN_SERVICE_ALIAS => self::DN_SERVICE_PARAMETERS,
        ConstantHelper::SI_SERVICE_ALIAS => self::SINV_SERVICE_PARAMETERS,
        ConstantHelper::LEASE_INVOICE_SERVICE_ALIAS => self::LEASE_INV_SERVICE_PARAMETERS,
        ConstantHelper::DELIVERY_CHALLAN_CUM_SI_SERVICE_ALIAS => self::DN_CUM_INVOICE_SERVICE_PARAMETERS,
        ConstantHelper::PI_SERVICE_ALIAS => self::PI_SERVICE_PARAMETERS,
        ConstantHelper::BOM_SERVICE_ALIAS => self::BOM_SERVICE_PARAMETERS,
        ConstantHelper::PAYMENT_VOUCHER_RECEIPT => self::PV_SERVICE_PARAMETERS,
        ConstantHelper::PAYMENTS_SERVICE_ALIAS => self::PV_SERVICE_PARAMETERS,
        ConstantHelper::RECEIPTS_SERVICE_ALIAS => self::PV_SERVICE_PARAMETERS,
        ConstantHelper::PO_SERVICE_ALIAS => self::PO_SERVICE_PARAMETERS,
        ConstantHelper::SUPPLIER_INVOICE_SERVICE_ALIAS => self::SUPPLIER_INVOICE_SERVICE_PARAMETERS,
        ConstantHelper::MRN_SERVICE_ALIAS => self::MRN_SERVICE_PARAMETERS,
        ConstantHelper::PB_SERVICE_ALIAS => self::PB_SERVICE_PARAMETERS,
        ConstantHelper::EXPENSE_ADVISE_SERVICE_ALIAS => self::EXPENSE_ADVISE_SERVICE_PARAMETERS,
        ConstantHelper::PURCHASE_RETURN_SERVICE_ALIAS => self::PURCHASE_RETURN_SERVICE_PARAMETERS,
        ConstantHelper::MATERIAL_REQUEST_SERVICE_ALIAS => self::MATERIAL_REQUEST_SERVICE_PARAMETERS,
        ConstantHelper::MATERIAL_ISSUE_SERVICE_ALIAS => self::MATERIAL_ISSUE_SERVICE_PARAMETERS,
        ConstantHelper::STOCK_ADJUSTMENT_SERVICE_ALIAS => self::STOCK_ADJUSTMENT_SERVICE_PARAMETERS,
        ConstantHelper::PHYSICAL_STOCK_TAKE_SERVICE_ALIAS => self::PHYSICAL_STOCK_TAKE_SERVICE_PARAMETERS,
        ConstantHelper::COMMERCIAL_BOM_SERVICE_ALIAS => self::COMMERCIAL_BOM_SERVICE_PARAMETERS,
        ConstantHelper::PRODUCTION_WORK_ORDER_SERVICE_ALIAS => self::PRODUCTION_WORK_ORDER_SERVICE_PARAMETERS,
        ConstantHelper::JOB_ORDER_SERVICE_ALIAS => self::JOB_ORDER_SERVICE_PARAMETERS,
        ConstantHelper::PRODUCTION_SLIP_SERVICE_ALIAS => self::PRODUCTION_SLIP_SERVICE_PARAMETERS,
        ConstantHelper::HOMELOAN => self::LOAN_SERVICE_PARAMETERS,
        ConstantHelper::TERMLOAN => self::LOAN_SERVICE_PARAMETERS,
        ConstantHelper::VEHICLELOAN => self::LOAN_SERVICE_PARAMETERS,
        ConstantHelper::LOAN_DISBURSEMENT => self::DIS_SERVICE_PARAMETERS,
        ConstantHelper::LOAN_RECOVERY => self::LOAN_RECOVERY_SERVICE_PARAMETERS,
        ConstantHelper::LOAN_SETTLEMENT => self::LOAN_SETTLEMENT_SERVICE_PARAMETERS,
        ConstantHelper::LAND_LEASE=>self::COMMON_SERVICE_PARAMETERS,
        ConstantHelper::PURCHASE_VOUCHER=>self::COMMON_SERVICE_PARAMETERS,
        ConstantHelper::SALES_VOUCHER=>self::COMMON_SERVICE_PARAMETERS,
        ConstantHelper::RECEIPT_VOUCHER=>self::COMMON_SERVICE_PARAMETERS,
        ConstantHelper::PAYMENT_VOUCHER=>self::COMMON_SERVICE_PARAMETERS,
        ConstantHelper::DEBIT_Note=>self::COMMON_SERVICE_PARAMETERS,
        ConstantHelper::CREDIT_Note=>self::COMMON_SERVICE_PARAMETERS,
        ConstantHelper::JOURNAL_VOUCHER=>self::COMMON_SERVICE_PARAMETERS,
        ConstantHelper::CONTRA_VOUCHER=>self::COMMON_SERVICE_PARAMETERS,
    ];
    /* Parameter Types*/
    const COMMON_PARAMETERS = 'co';
    const GL_PARAMETERS = 'gl';
    const PARAMETER_TYPES = [self::COMMON_PARAMETERS, self::GL_PARAMETERS];

    /* Function to get book level parameters for configuration*/
    public static function getBookLevelParameterValue(string $parameterName, int $bookId) : array
    {
        //Get raw book parameters from database
        $bookParameter = OrganizationBookParameter::where('book_id', $bookId) -> where('parameter_name', $parameterName)
        -> where('status', ConstantHelper::ACTIVE) -> first();
        $parameters = [];
        if (isset($bookParameter)) {
            //REFERENCE FROM CASE
            if ($parameterName === self::REFERENCE_FROM_SERVICE_PARAM) {
                $services = Service::whereIn('id', $bookParameter -> parameter_value)->get();
                foreach ($services as $service) {
                    array_push($parameters, $service -> alias);
                }
                //Assign a Default D in values for Direct Doc creation
                if (in_array(0, $bookParameter -> parameter_value)) {
                    array_push($parameters, 'd');
                }
                return [
                    'status' => true,
                    'message' => 'Parameter found',
                    'data' => $parameters
                ];
            } else if ($parameterName === self::REFERENCE_FROM_SERIES_PARAM) {
                $books = Book::withDefaultGroupCompanyOrg() -> whereIn('id', $bookParameter -> parameter_value) -> get();
                foreach ($books as $service) {
                    array_push($parameters, $service -> book_code);
                }
                return [
                    'status' => true,
                    'message' => 'Parameter found',
                    'data' => $parameters
                ];
            }else {
                return [
                    'status' => true,
                    'message' => 'Parameter found',
                    'data' => $bookParameter -> parameter_value
                ];
            }
        //Parameters not Found
        } else {
            return [
                'status' => false,
                'message' => 'Parameter not found',
                'data' => []
            ];
        }
    }

    /* Function to get book ids applicable for pulling in */
    public static function getBookCodesForReferenceFromParam(int $bookId) : array
    {
        $bookParameter = OrganizationBookParameter::where('book_id', $bookId) -> where('parameter_name', self::REFERENCE_FROM_SERIES_PARAM) -> where('status', ConstantHelper::ACTIVE) -> first();
        if (isset($bookParameter)) {
            $books = Book::select('id') -> whereIn('id', $bookParameter -> parameter_value) -> get() -> pluck('id') -> toArray();
            return $books;
        } else {
            return [];
        }
    }

    /* Function to get service level parameters with their default and appliacable values converted in a useable format */
    public static function getDefinedServiceLevelParameters(string $serviceAlias) : array
    {
        $applicableParameters = isset(self::APPLICABLE_SERVICE_PARAMETERS[$serviceAlias]) ? self::APPLICABLE_SERVICE_PARAMETERS[$serviceAlias] : [];
        $service = Service::where('alias', $serviceAlias) -> first();
        //Loop through parameters and modify values
        foreach ($applicableParameters as &$parameter) {
            $parameter['type'] = isset($parameter['type']) ? $parameter['type'] : self::COMMON_PARAMETERS; //Assign type (GL or common)
            if ($parameter['name'] === self::REFERENCE_FROM_SERVICE_PARAM) {
                //REFERENCE PARAMETER CASE (Get other services)
                $serviceAliases = $parameter['applicable_values'];
                $services = Service::select('id', 'alias', 'name') -> whereIn('alias', $serviceAliases) -> get();
                $formattedValues = [];
                //Assign a Direct Option
                if (in_array("0", $serviceAliases)) {
                    array_push($formattedValues, [
                        'label' => 'Direct',
                        'value' => "0"
                    ]);
                }
                foreach ($services as $serviceVal) {
                    array_push($formattedValues, [
                        'label' => $serviceVal -> name,
                        'value' => $serviceVal -> id
                    ]);
                }
                $parameter['applicable_values'] = $formattedValues;
            } else {
                $formattedValues = [];
                foreach ($parameter['applicable_values'] as $applicableVal) {
                    array_push($formattedValues, [
                        'label' => ucfirst($applicableVal),
                        'value' => $applicableVal
                    ]);
                }
                $parameter['applicable_values'] = $formattedValues;
            }
            //Assign a key for storing in Database
            $parameter['applicable_values_database'] = count($formattedValues) > 0 ? array_column($formattedValues, 'value') : [];
            //Assign Default parameter from database only if exists
            if (isset($service)) {
                $serviceLevelParam = ServiceParameter::where('service_id', $service -> id) -> where('name', $parameter['name']) -> first();
                if (isset($serviceLevelParam)) {
                    //Modify the default value for REFERENCE FROM Param only
                    if ($parameter['name'] === self::REFERENCE_FROM_SERVICE_PARAM) {
                        $formattedDefaultValue = [];
                        $dFServices = Service::select('id', 'alias', 'name') -> whereIn('id', $serviceLevelParam -> default_value) -> get();
                        if (in_array(0, $serviceLevelParam -> default_value)) {
                            array_push($formattedDefaultValue, "0");
                        }
                        foreach ($dFServices as $dFService) {
                            array_push($formattedDefaultValue, $dFService -> id);
                        }
                        $parameter['default_value'] = $formattedDefaultValue;
                    } else {
                        $parameter['default_value'] = $serviceLevelParam -> default_value;
                    }
                } else {
                    //Modify the default value for REFERENCE FROM Param only
                    if ($parameter['name'] === self::REFERENCE_FROM_SERVICE_PARAM) {
                        $formattedDefaultValue = [];
                        $dFServices = Service::select('id', 'alias', 'name') -> whereIn('alias', $parameter['default_value']) -> get();
                        if (in_array(0, $parameter['default_value'])) {
                            array_push($formattedDefaultValue, "0");
                        }
                        foreach ($dFServices as $dFService) {
                            array_push($formattedDefaultValue, $dFService -> id);
                        }
                        $parameter['default_value'] = $formattedDefaultValue;
                    }
                }
            }
        }
        return $applicableParameters;
    }

    /*
    Script Function to sync the service parameters at Organization level
    NOTE - Use within a Transaction
    */
    public static function enableServiceParametersForOrganization(int $serviceId, int $organizationId) : array
    {
        $service = Service::find($serviceId);
        if (!isset($service)) {
            return [
                'status' => false,
                'message' => 'Service Not Found'
            ];
        }
        //Get Service Parameters from Database
        $serviceParameters = $service -> parameters;
        $organization = Organization::find($organizationId);
        if (!isset($organization)) {
            return [
                'status' => false,
                'message' => 'Organization Not Found'
            ];
        }
        //Create or Update Organization Service Parameter
        foreach ($serviceParameters as $serviceParam) {
            $existingService = OrganizationServiceParameter::where([
                ['group_id', $organization -> group_id],
                ['service_id', $service -> id],
                ['service_param_id', $serviceParam -> id],
                ['parameter_name', $serviceParam -> name]
            ]) -> first();
            //Create
            if (!isset($existingService)) {
                OrganizationServiceParameter::create([
                    'group_id' => $organization -> group_id,
                    'company_id' => null, // Need to change later
                    'organization_id' => null, // Need to change later
                    'service_id' => $service -> id,
                    'service_param_id' => $serviceParam -> id,
                    'parameter_name' => $serviceParam -> name,
                    'parameter_value' => $serviceParam -> default_value,
                    'type' => $serviceParam -> type,
                    'status' => ConstantHelper::ACTIVE,
                ]);
            } else { // Update only parameter value and type
                $existingService -> parameter_value = $serviceParam -> default_value;
                $existingService -> type = $serviceParam -> type;
                $existingService -> save();
            }
        }
        //Retrieve organization service if exists else create it
        $orgService = OrganizationService::where('group_id', $organization -> group_id) -> where('service_id', $serviceId) -> first();
        if (!isset($orgService)) {
            $orgService = OrganizationService::create([
                'organization_id' => null,
                'company_id' => null,
                'group_id' => $organization -> group_id,
                'service_id' => $serviceId,
                'name' => $service -> name,
                'alias' => $service -> alias
            ]);
        }
        //Check for any existing book in Group/ Organization
        $existingBook = Book::where([
            ['group_id', $organization -> group_id],
            ['org_service_id', $orgService -> id]
        ]) -> first();
        if (!isset($existingBook)) {
            //Assign a default Book with parameters and manual doc creation
            $book = Book::create([
                'org_service_id' => $orgService -> id,
                'service_id' => $orgService ?-> service ?-> id,
                'book_code' => strtoupper($service -> alias), // CHECK AGAIN
                'book_name' => $service -> name, // CHECK AGAIN
                'status' => ConstantHelper::ACTIVE,
                'group_id' => $organization -> group_id,
                'company_id' => null,
                'organization_id' => null
            ]);
            NumberPattern::create([
                'book_id' => $book -> id,
                'company_id' => $organization -> company_id,
                'organization_id' => $organization -> id,
                'series_numbering' => ConstantHelper::DOC_NO_TYPE_MANUAL,
                'reset_pattern' => ConstantHelper::DOC_RESET_PATTERN_NEVER,
                'prefix' => null,
                'starting_no' => null,
                'suffix' => null,
                'current_no' => 0
            ]);
             //Create Book Level Parmeters also
            $orgServiceParams = OrganizationServiceParameter::where('group_id', $organization -> group_id) -> where('service_id', $service -> id) -> get();
            foreach ($orgServiceParams as $orgServiceParam) {
                if ($orgServiceParam -> parameter_name === self::REFERENCE_FROM_SERVICE_PARAM) {
                    $serviceIds = Service::select('id', 'alias', 'name') -> whereIn('id', $orgServiceParam -> parameter_value) -> get() -> pluck('id') -> toArray();
                    $defaultVal = $serviceIds;
                    if (in_array("0", $orgServiceParam -> parameter_value)) {
                        array_push($defaultVal, "0");
                    }
                } else if ($orgServiceParam -> parameter_name === self::REFERENCE_FROM_SERIES_PARAM) {
                    //Get Service Ids for getting referenced books
                    $serviceIds = $orgServiceParams -> firstWhere('parameter_name', self::REFERENCE_FROM_SERVICE_PARAM);
                    if (isset($serviceIds)) {
                        $serviceIds = $serviceIds -> parameter_value;
                    } else {
                        $serviceIds = [];
                    }
                    //Special Conditions for INVOICE, DELIVERY NOTE AND INVOICE CUM DELIVERY NOTE (More can be added here)
                    $defaultVal = self::getAvailableReferenceSeries($orgServiceParam -> service_id, $serviceIds, 0, true);
                } else {
                    $defaultVal = $orgServiceParam -> parameter_value;
                }
                OrganizationBookParameter::create([
                    'book_id' => $book -> id,
                    'group_id' => $organization->group_id,
                    'company_id' => null,
                    'organization_id' => null,
                    'org_service_id' => $orgService -> id,
                    'service_param_id' => $orgServiceParam -> service_param_id,
                    'parameter_name' =>  $orgServiceParam -> parameter_name,
                    'parameter_value' => $defaultVal,
                    'type' => $orgServiceParam -> type,
                    'status' => ConstantHelper::ACTIVE,
                ]);
            }
        } else {
            //Update all existing books with new parameters (if addded)
            $books = Book::withDefaultGroupCompanyOrg() -> where('org_service_id', $orgService -> id) -> get();
            foreach ($books as $book) {
                $referenceFrom = $serviceParameters -> firstWhere('name', self::REFERENCE_FROM_SERVICE_PARAM) ?-> default_value;
                foreach ($serviceParameters as $serviceParam) {
                    $existingBookParam = OrganizationBookParameter::where('book_id', $book -> id) -> where('parameter_name', $serviceParam -> name) -> first();
                    if (!isset($existingBookParam))
                    {
                        $defaultValue = $serviceParam -> default_value;
                        if (isset($referenceFrom))
                        {
                            if ($serviceParam -> name === self::REFERENCE_FROM_SERIES_PARAM)
                            {
                                foreach ($referenceFrom as $ref) {
                                    if ($ref != 0) {
                                        $service = Service::find($ref);
                                        $referencedBook = Book::where('group_id', $organization -> group_id) -> where(DB::raw('UPPER(book_code)'), strtoupper($service ?-> alias)) -> first();
                                        if (isset($referencedBook)) {
                                            array_push($defaultValue, $referencedBook -> id);
                                        }
                                    }
                                }
                            }
                        }
                        OrganizationBookParameter::create([
                            'group_id' => $organization -> group_id,
                            'company_id' => null,
                            'organization_id' => null,
                            'book_id' => $book -> id,
                            'org_service_id' => $orgService -> id,
                            'service_param_id' => $serviceParam -> id,
                            'parameter_name' => $serviceParam -> name,
                            'parameter_value' => $defaultValue,
                            'type' => $serviceParam -> type,
                            'status' => ConstantHelper::ACTIVE,
                        ]);
                    }
                }
            }
        }
        return [
            'status' => true,
            'message' => 'Organization Service Parameters Synced'
        ];
    }

    /*Return the series/ book available for pulling -> Only those series which have not been referenced in any book parameter will come*/
    public static function getAvailableReferenceSeries(int $sourceServiceId, array $serviceIds, int $editBookId = 0, bool $pluck = false) : EloquentCollection|array
    {
        //Get all bookIds according to service
        $bookIds =  Book::withDefaultGroupCompanyOrg() -> whereHas('org_service', function ($serviceQuery) use($serviceIds) {
            $serviceQuery -> whereIn('service_id', $serviceIds);
        }) -> get() -> pluck('id') -> toArray();
        $sourceService = Service::find($sourceServiceId);
        $nonReferencedBookIds = [];
        $invoiceServices = [
            // ConstantHelper::DELIVERY_CHALLAN_SERVICE_ALIAS,
            // ConstantHelper::SI_SERVICE_ALIAS,
            // ConstantHelper::DELIVERY_CHALLAN_CUM_SI_SERVICE_ALIAS
        ];
        //Check each book id for it's reference
        foreach ($bookIds as $bookId) {
            $sourceServiceIds = [$sourceServiceId];
            if (isset($sourceService)) {
                //Condition for invoice services
                // if (in_array($sourceService -> alias, $invoiceServices))
                // {
                //     $serviceIds = Service::whereIn('alias', $invoiceServices) -> get() -> pluck('id') -> toArray();
                //     foreach ($serviceIds as $serviceId) {
                //         array_push($sourceServiceIds, $serviceId);
                //     }
                // }
                $isReferenced = OrganizationBookParameter::whereHas('org_service', function ($orgServiceQuery) use($sourceServiceIds) {
                    $orgServiceQuery -> whereIn('service_id', $sourceServiceIds);
                }) -> where('parameter_name', ServiceParametersHelper::REFERENCE_FROM_SERIES_PARAM)
                -> when($editBookId, function ($editQuery) use($editBookId) {
                    $editQuery -> where('book_id', '!=', $editBookId);
                }) -> where('org_service_id', $sourceService -> id) -> whereJsonContains('parameter_value', (string)$bookId) -> first();
                if (!isset($isReferenced)) {
                    //Check for sales invoice
                    if ($sourceService -> alias === ConstantHelper::SI_SERVICE_ALIAS) {
                        $invoiceToFollowParam = OrganizationBookParameter::where('book_id', $bookId)
                        -> where('parameter_name', ServiceParametersHelper::INVOICE_TO_FOLLOW_PARAM)-> first();
                        if (isset($invoiceToFollowParam) && ($invoiceToFollowParam ?-> parameter_value[0] == 'yes')) {
                            array_push($nonReferencedBookIds, $bookId);
                        }
                    } else if ($sourceService -> alias === ConstantHelper::PB_SERVICE_ALIAS) {
                        $billToFollowParam = OrganizationBookParameter::where('book_id', $bookId)
                        -> where('parameter_name', ServiceParametersHelper::BILL_TO_FOLLOW_PARAM)-> first();
                        if (isset($billToFollowParam) && ($billToFollowParam ?-> parameter_value[0] == 'yes')) {
                            array_push($nonReferencedBookIds, $bookId);
                        }
                    } else {
                        array_push($nonReferencedBookIds, $bookId);
                    }
                }
            }
        }
        //return all the non referenced books
        $books =  Book::withDefaultGroupCompanyOrg() -> whereIn('id', $nonReferencedBookIds);
        if ($pluck) {
            $books = $books -> get() -> pluck('id') -> toArray();
        } else {
            $books = $books -> get();
        }
        return $books;
    }

    public static function getFinancialServiceAlias(string $serviceAlias) : string|null
    {
        if (isset(ConstantHelper::OPERATION_FINANCIAL_SERVICES_MAPPING[$serviceAlias])) {
            return ConstantHelper::OPERATION_FINANCIAL_SERVICES_MAPPING[$serviceAlias];
        } else {
            return null;
        }
    }
    public static function getFinancialService(string $serviceAlias) : string|null
    {
        $financialServiceAlias = self::getFinancialServiceAlias($serviceAlias);
        if (isset($financialServiceAlias)) {
            $financialService = Service::where('alias', $financialServiceAlias) -> first();
            if (isset($financialService)) {
                return $financialService -> name . " - " . $financialService -> alias;
            } else {
                return $financialServiceAlias;
            }
        } else {
            return null;
        }
    }
}
