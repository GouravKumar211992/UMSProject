<?php

namespace App\Helpers;
use App\Models\Bom;
use App\Models\Book;
use App\Models\Compliance;
use App\Models\Customer;
use App\Models\ErpAttribute;
use App\Models\ErpInvoiceItem;
use App\Models\ErpItemAttribute;
use App\Models\ErpSaleInvoice;
use App\Models\ErpSaleOrder;
use App\Models\ErpSoItem;
use App\Models\Item;
use App\Models\ItemAttribute;
use App\Models\OrganizationBookParameter;
use Illuminate\Support\Collection;

class SaleModuleHelper  
{ 
    const SALES_RETURN_DEFAULT_TYPE = "sale-returns";
    const SALES_INVOICE_DEFAULT_TYPE = "sale-invoice";
    const SALES_INVOICE_LEASE_TYPE = "lease-invoice";
    public static function getAndReturnInvoiceType(string $type) : string
    {
        $invoiceType = isset($type) && in_array($type, ConstantHelper::SALE_INVOICE_DOC_TYPES) ? $type : ConstantHelper::SI_SERVICE_ALIAS;
        return $invoiceType;
    }
    // public static function getAndReturnInvoiceTypeName(string $type) : string
    // {
    //     if ($type === ConstantHelper::SI_SERVICE_ALIAS) {
    //         return "Sales Invoice";
    //     } else if ($type === ConstantHelper::DELIVERY_CHALLAN_SERVICE_ALIAS) {
    //         return "Delivery Note";
    //     } else if ($type === ConstantHelper::DELIVERY_CHALLAN_CUM_SI_SERVICE_ALIAS) {
    //         return "Invoice CUM Delivery Note";
    //     } else if ($type === ConstantHelper::LEASE_INVOICE_SERVICE_ALIAS) {
    //         return "Lease Invoice";
    //     } else {
    //         return "";
    //     }
    // }
    public static function getAndReturnInvoiceTypeName(string $type) : string
    {
        if ($type === self::SALES_INVOICE_DEFAULT_TYPE) {
            return "Invoice";
        } else if ($type === self::SALES_INVOICE_LEASE_TYPE) {
            return "Lease Invoice";
        } else {
            return "";
        }
    }

    public static function getAndReturnReturnType(string $type) : string
    {
        $returnType = isset($type) && in_array($type, ConstantHelper::SALE_RETURN_DOC_TYPES_FOR_DB) ? $type : ConstantHelper::SR_SERVICE_ALIAS;
        return $returnType;
    }
    public static function getAndReturnReturnTypeName(string $type) : string
    {
        if ($type === ConstantHelper::SR_SERVICE_ALIAS) {
            return "Sales Return";
        } else if ($type === ConstantHelper::DELIVERY_CHALLAN_SERVICE_ALIAS) {
            return "Delivery Note";
        } else if ($type === ConstantHelper::DELIVERY_CHALLAN_CUM_SI_SERVICE_ALIAS) {
            return "Invoice CUM Delivery Note";
        } else if ($type === ConstantHelper::LEASE_INVOICE_SERVICE_ALIAS) {
            return "Lease Invoice";
        } else {
            return "";
        }
    }

    public static function checkTaxApplicability(int $customerId, int $bookId) : bool
    {
        //Book Level Tax
        $bookLevelTaxParam = ServiceParametersHelper::getBookLevelParameterValue(ServiceParametersHelper::TAX_REQUIRED_PARAM, $bookId)['data'];
        if (in_array("no", $bookLevelTaxParam) || count($bookLevelTaxParam) == 0) {
            return false;
        }
        //Customer Level Tax
        $customerLevelTaxParam = Compliance::where('morphable_type', Customer::class) -> where('morphable_id', $customerId) -> first();
        if (!isset($customerLevelTaxParam)) {
            return false;
        }
        if (!$customerLevelTaxParam -> gst_applicable) {
            return false;
        }
        return true;
    }

    public static function item_attributes_array(int $itemId, array $selectedAttributes)
    {
        if (isset($itemId)) {
            $itemAttributes = ErpItemAttribute::where('item_id', $itemId) -> get();
        } else {
            $itemAttributes = [];
        }
        $processedData = [];
        foreach ($itemAttributes as $attribute) {
            $existingAttribute = array_filter($selectedAttributes, function ($selectedAttr) use($attribute) {
                return $selectedAttr['item_attribute_id'] == $attribute -> id;
            });
            // $existingAttribute = ErpSoItemAttribute::where('so_item_id', $this -> getAttribute('id')) -> where('item_attribute_id', $attribute -> id) -> first();
            if (!isset($existingAttribute) || count($existingAttribute) == 0) {
                continue;
            }
            $existingAttribute = array_values($existingAttribute);
            $attributesArray = array();
            $attribute_ids = $attribute -> attribute_id ? json_decode($attribute -> attribute_id) : [];
            $attribute -> group_name = $attribute -> group ?-> name;
            foreach ($attribute_ids as $attributeValue) {
                    $attributeValueData = ErpAttribute::where('id', $attributeValue) -> select('id', 'value') -> where('status', 'active') -> first();
                    if (isset($attributeValueData))
                    {
                        $isSelected = $existingAttribute[0]['value_id'] == $attributeValueData -> id;
                        $attributeValueData -> selected = $isSelected ? true : false;
                        array_push($attributesArray, $attributeValueData);
                    }
                
            }
           $attribute -> values_data = $attributesArray;
           $attribute = $attribute -> only(['id','group_name', 'values_data', 'attribute_group_id']);
           array_push($processedData, ['id' => $attribute['id'], 'group_name' => $attribute['group_name'], 'values_data' => $attributesArray, 'attribute_group_id' => $attribute['attribute_group_id']]);
        }
        $processedData = collect($processedData);
        return $processedData;
    }

    public static function sortByDueDateLogic(Collection $collection, string $dueDateKey = 'due_date')
    {
        // Use the current date if not provided
        $currentDate = date('Y-m-d');

        // Use the current date if not provided
        $currentDate = $currentDate ?? now()->toDateString();

        return $collection->sort(function ($a, $b) use ($currentDate, $dueDateKey) {
            $dateA = $a -> {$dueDateKey};
            $dateB = $b -> {$dueDateKey};

            // Determine if dates are overdue or upcoming
            $isOverdueA = ($dateA < $currentDate);
            $isOverdueB = ($dateB < $currentDate);

            // Priority: Overdue dates first
            if ($isOverdueA && !$isOverdueB) {
                return -1; // $a comes before $b
            } elseif (!$isOverdueA && $isOverdueB) {
                return 1; // $b comes before $a
            }

            // If both are overdue or both are upcoming
            if ($isOverdueA && $isOverdueB) {
                // Overdue: Largest difference first
                return strtotime($dateB) - strtotime($dateA);
            } else {
                // Upcoming: Smallest difference first
                return strtotime($dateA) - strtotime($dateB);
            }
        })->values(); // Re-index the collection
    }

    public static function checkInvoiceDocTypesFromUrlType(string $type) : array
    {
        if ($type === self::SALES_INVOICE_DEFAULT_TYPE){
            return [ConstantHelper::SI_SERVICE_ALIAS, ConstantHelper::DELIVERY_CHALLAN_SERVICE_ALIAS];
        } else if ($type === self::SALES_INVOICE_LEASE_TYPE) {
            return [ConstantHelper::LEASE_INVOICE_SERVICE_ALIAS];
        } else {
            return [];
        }
    }

    public static function getServiceName($bookId)
    {
        $book = Book::find($bookId);
        if (isset($book)) {
            if ($book -> service ?-> alias === ConstantHelper::DELIVERY_CHALLAN_SERVICE_ALIAS) {
                $invoiceToFollowParam = OrganizationBookParameter::where('book_id', $book -> id) -> where('parameter_name', ServiceParametersHelper::INVOICE_TO_FOLLOW_PARAM) -> first();
                if (isset($invoiceToFollowParam) && $invoiceToFollowParam -> parameter_value[0] == 'yes') {
                    return $book -> service -> name;
                } else {
                    return "DN cum Invoice";
                }
            } else {
                return $book -> service -> name;
            }
        } else {
            return "N/A";
        }
    }

    public static function reCalculateExpenses(array $itemDetails, $referenceFromType = ConstantHelper::SO_SERVICE_ALIAS) : array
    {
        //Assign empty expense
        $expensesDetails = [];
        foreach ($itemDetails as $itemDetail) {
            //Loop through all item reference IDs
            foreach ($itemDetail['reference_from'] as $referenceItem) {
                //Find the SO Item and it's header
                if ($referenceFromType == ConstantHelper::SO_SERVICE_ALIAS) {
                    $referenceItemDetail = ErpSoItem::find($referenceItem);
                    $referenceHeaderDetail = ErpSaleOrder::find($referenceItemDetail ?-> sale_order_id);
                } else {
                    $referenceItemDetail = ErpInvoiceItem::find($referenceItem);
                    $referenceHeaderDetail = ErpSaleInvoice::find($referenceItemDetail ?-> sale_invoice_id);
                }
                //Calculate the net rate for expense
                $totalValueAfterDiscount = ($itemDetail['item_qty'] * $itemDetail['rate']) - $itemDetail['header_discount'] - $itemDetail['item_discount'];
                $totalNetRate = $totalValueAfterDiscount / $itemDetail['item_qty'];
                if (isset($referenceHeaderDetail) && $referenceHeaderDetail -> expense_ted) {
                    //Loop through all the expenses stored in header level
                    foreach ($referenceHeaderDetail -> expense_ted as $headerExpense) {
                        //Calculate ted percentage from amount and apply it to item
                        $headerExpensePercentage = ($headerExpense -> ted_amount / $headerExpense -> assessment_amount) * 100;
                        $itemExpense = $totalNetRate * $headerExpensePercentage;
                        //Check if expense already exists in total expense
                        $existingExpenseIndex = null;
                        foreach ($expensesDetails as $expensesDetailIndex => $expensesDetail) {
                            if ($expensesDetail['id'] == $headerExpense -> id) {
                                $existingExpenseIndex = $expensesDetailIndex;
                                break;
                            }
                        }
                        //existing expense found
                        if (isset($existingExpenseIndex)) {
                            $expensesDetails[$existingExpenseIndex]['ted_amount'] += $itemExpense;
                        } else { //Append new Expense
                            array_push($expensesDetail, [
                                'id' => $headerExpense -> id,
                                'ted_amount' => $itemExpense,
                                'ted_name' => $headerExpense -> ted_name,
                                'ted_percentage' => $headerExpense -> ted_percentage
                            ]);
                        }
                    }
                }
            }
        }
        return $expensesDetails;
    }
}