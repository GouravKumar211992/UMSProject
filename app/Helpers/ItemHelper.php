<?php

namespace App\Helpers;
use App\Models\AlternateUOM;
use App\Models\Bom;
use App\Models\Item;
use App\Helpers\CurrencyHelper;

class ItemHelper  
{ 
    /* array : $itemAttributes should be in the form -> [['attribute_id' => 1, 'attribute_value' => 10]] */
    public static function checkItemBomExists(int $itemId, array $itemAttributes, $bomType = 'bom') : array|null
    {
        $subType = null;
        $item = Item::find($itemId);
        //Item not found
        if (!isset($item)) {
            return array(
                'status' => 'item_not_found',
                'bom_id' => null,
                'message' => 'Item not found',
                'sub_type' => $subType
            );
        }
        //Check Item Sub Type
        $subType = self::getItemSubType($item->id);
        $subTypeStatus = false;
        if(in_array($subType, ['Finished Goods', 'WIP/Semi Finished'])) {
            $subTypeStatus = true;
        }
        
        if (!$subTypeStatus) {
            return array(
                'status' => 'bom_not_required',
                'bom_id' => null,
                'message' => 'BOM not required',
                'sub_type' => $subType
            );
        }
        //If Item is SEMI FINISHED OR FINISHED PRODUCT -> Check item level Bom
        $matchedBomId = null;
        $itemBoms = Bom::withDefaultGroupCompanyOrg() -> where('item_id', $item -> id) 
        ->whereIn('document_status', [ConstantHelper::APPROVED, ConstantHelper::APPROVAL_NOT_REQUIRED])
        ->where('type', $bomType)
        ->get();
        if (!isset($itemBoms) || count($itemBoms) == 0) {
            return array(
                'status' => 'bom_not_exists',
                'bom_id' => null,
                'message' => 'BOM does not exist',
                'sub_type' => $subType
            );
        }
        $matchedBomId = $itemBoms[0]->id ?? null;
        //Check if all atributes are selected
        $actualItemAttributes = $item -> itemAttributes;
        $attributes = array();
        foreach($actualItemAttributes as $currentAttribute) {
            if($currentAttribute?->required_bom) {
                array_push($attributes, $currentAttribute);
            }
        }
        //Compare all BOM with required BOM attribute values 
        if(count($attributes) > 0) {
            $matchedBomId = null;
            foreach ($itemBoms as $bom) {
                $attributeBomCreated = false;
                foreach ($bom -> bomAttributes as $attribute) {
                    $reqBomAttribute = array_filter($attributes, function ($reqAttribute) use($attribute) {
                        return $reqAttribute -> id == $attribute -> item_attribute_id;
                    });
                    if ($reqBomAttribute && count($reqBomAttribute) > 0) {
                        $matchingAttribute = array_filter($itemAttributes, function ($itemAttribute) use($attribute) {
                            return $itemAttribute['attribute_value'] == $attribute -> attribute_value && $itemAttribute['attribute_id'] == $attribute -> item_attribute_id;
                        });
                        if ($matchingAttribute && count($matchingAttribute) > 0) {
                            $attributeBomCreated = true;
                        } else {
                            $attributeBomCreated = false;
                            break;
                        }
                    }
                }
                if ($attributeBomCreated) {
                    $matchedBomId = $bom -> id;
                    break;
                }
            }
        }
        return array(
            'status' => $matchedBomId ? 'bom_exists' : 'bom_not_exists',
            'bom_id' => $matchedBomId,
            'message' => $matchedBomId ? 'Bom exist' : 'BOM does not exist',
            'sub_type' => $subType
        );
    }

    # Return item sub type name
    public static function getItemSubType($itemId = null)
    {
        $item = Item::find($itemId);
        $subTypes = $item->subTypes;
        $name = null;

        $subType = collect($item?->subTypes)->whereIn('name',['Finished Goods'])->first();
        if($subType) {
            $name = $subType?->name;
        }

        if(!$name) {
            $subType = collect($item?->subTypes)->whereIn('name',['WIP/Semi Finished'])->first();
            if($subType) {
                $name = $subType?->name;
            }
        }

        if(!$name) {
            $subType = collect($item?->subTypes)->whereIn('name',['Raw Material'])->first();
            if($subType) {
                $name = $subType?->name;
            }
        }

        if(!$name) {
            $subType = collect($item?->subTypes)->whereIn('name',['Asset'])->first();
            if($subType) {
                $name = $subType?->name;
            }
        }

        if(!$name) {
            $subType = collect($item?->subTypes)->whereIn('name',['Expense'])->first();
            if($subType) {
                $name = $subType?->name;
            }
        }

        if(!$name) {
            $subType = collect($item?->subTypes)->whereIn('name',['Traded Item'])->first();
            if($subType) {
                $name = $subType?->name;
            }
        }
        
        return $name;
    } 

    # get item uom by item id   param :- item_id and uom_type [purchase, selling] return uomId
    public static function getItemUom($itemId, $uomType)
    {
        $item = Item::find($itemId);
        if (!$item) {
            return null; // Item not found
        }
        $altUom = $item?->uom_id;
        if($item?->alternateUOMs->count()) {
            if($uomType == 'purchase') {
                $altUom = $item->alternateUOMs()->where('is_purchasing',1)->first();
                $altUom = $altUom->id ?? null;
            }
            if($uomType == 'selling') {
                $altUom = $item->alternateUOMs()->where('is_selling',1)->first();
                $altUom = $altUom->id ?? null;
            }
        }    
        return $altUom;    
    }

    # Get item rate by it, uom and attribute
    public static function getItemCostPrice($itemId, $attributes, $uomId, $currencyId, $transactionDate, $vendorId = null)
    {
        $costPrice = 0;
        $uomConversion = 0;
        $item = Item::find($itemId);
        if($vendorId) {
           $vendorItem = $item->approvedVendors()
                        ->where('vendor_id', $vendorId)
                        ->where('uom_id', $uomId)
                        ->first();
            if($vendorItem) {
                $costPrice = floatval($vendorItem?->cost_price ?? 0);
            }
        }

        if(!$costPrice) {
            $altUom = $item->alternateUOMs()
                    ->where('uom_id', $uomId)
                    ->first();
            if($altUom) {
                $uomConversion = $altUom?->conversion_to_inventory;
                if(isset($altUom->cost_price) && $altUom->cost_price) {
                    $costPrice =  floatval($altUom->cost_price);
                }
            }
        }

        if(!$costPrice) {
            if($uomId == $item->uom_id) {
                $costPrice = floatval($item?->cost_price);
            } else {
                if($uomConversion) {
                    $costPrice = floatval($item?->cost_price * $uomConversion);
                }
            }
        }
        
        $exchangeRate = CurrencyHelper::getCurrencyExchangeRates($currencyId, $transactionDate);
        if($exchangeRate['status'] == TRUE) {
            if($exchangeRate['data']['org_currency_id'] != $currencyId) {
                $costPrice = floatval($costPrice / floatval($exchangeRate['data']['org_currency_exg_rate']));
            }
        } else {
            $costPrice = 0;
        }
        return round($costPrice, 2);
    }

    # Get item rate by it, uom and attribute
    public static function getItemSalePrice($itemId, $attributes, $uomId, $currencyId, $transactionDate, $customerId = null)
    {
        $costPrice = 0;
        $uomConversion = 0;
        $item = Item::find($itemId);
        if($customerId) {
            $customerItem = $item->approvedCustomers()
                        ->where('customer_id', $customerId)
                        ->where('uom_id', $uomId)
                        ->first();
            if($customerItem) {
                $costPrice = floatval($customerItem?->sell_price ?? 0);
            }
        }

        if(!$costPrice) {
            $altUom = $item->alternateUOMs()
                    ->where('uom_id', $uomId)
                    ->first();
            if($altUom) {
                $uomConversion = $altUom?->conversion_to_inventory;
                if(isset($altUom->sell_price) && $altUom->sell_price) {
                    $costPrice =  floatval($altUom->sell_price);
                }
            }
        }

        if(!$costPrice) {
            if($uomId == $item->uom_id) {
                $costPrice = floatval($item?->sell_price);
            } else {
                if($uomConversion) {
                    $costPrice = floatval($item?->sell_price * $uomConversion);
                }
            }
        }
        
        $exchangeRate = CurrencyHelper::getCurrencyExchangeRates($currencyId, $transactionDate);
        if($exchangeRate['status'] == TRUE) {
            if($exchangeRate['data']['org_currency_id'] != $currencyId) {
                $costPrice = floatval($costPrice / floatval($exchangeRate['data']['org_currency_exg_rate']));
            }
        } else {
            $costPrice = 0;
        }
        return round($costPrice, 2);
    }

    public static function convertToBaseUom(int $itemId, int $altUomId, float $altQty) : float
    {
        $baseUomQty = 0;
        $item = Item::find($itemId);
        if (isset($item)) {
            $baseUomId = $item -> uom_id;
            //Same UOM
            if ($altUomId === $baseUomId) {
                $baseUomQty = $altQty;
            } else {
                $conversion = AlternateUOM::where('item_id', $itemId) -> where('uom_id', $altUomId) -> first();
                if (isset($conversion)) {
                    $baseUomQty = round($altQty * $conversion -> conversion_to_inventory, 2);
                }
            }
        }
        return $baseUomQty;
    }

    public static function convertToAltUom(int $itemId, int $altUomId, float $baseQty) : float
    {
        $altUomQty = 0;
        $item = Item::find($itemId);
        if (isset($item)) {
            $baseUomId = $item -> uom_id;
            //Same UOM
            if ($altUomId === $baseUomId) {
                $altUomQty = $baseQty;
            } else {
                $conversion = AlternateUOM::where('item_id', $itemId) -> where('uom_id', $altUomId) -> first();
                if (isset($conversion)) {
                    $altUomQty = round($baseQty / $conversion -> conversion_to_inventory, 2);
                }
            }
        }
        return $altUomQty;
    }
}