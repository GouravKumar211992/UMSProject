<?php

namespace App\Imports;

use App\Models\Item;
use App\Models\ItemAttribute;
use App\Models\ItemSpecification;
use App\Models\AlternateUOM;
use App\Models\AttributeGroup;
use App\Models\Attribute;
use App\Models\ProductSpecification;
use App\Models\ProductSpecificationDetail;
use App\Models\UploadItemMaster; 
use App\Models\Category;
use App\Models\Unit;
use App\Models\Hsn;
use App\Models\SubType;
use App\Helpers\Helper; 
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;
use Exception;

class ItemImport implements ToModel, WithHeadingRow
{
    private function generateItemCode($subType, $categoryInitials, $subCategoryInitials, $itemInitials, $organizationId)
    {
        $baseCode = $subType . $categoryInitials . $subCategoryInitials . $itemInitials;
        $lastSimilarItem = Item::where('item_code', 'like', "{$baseCode}%")
            ->where('organization_id', $organizationId)
            ->orderBy('item_code', 'desc')
            ->first();

        $nextSuffix = '001';
        if ($lastSimilarItem) {
            $lastSuffix = intval(substr($lastSimilarItem->item_code, -3));
            $nextSuffix = str_pad($lastSuffix + 1, 3, '0', STR_PAD_LEFT);
        }

        return $baseCode . $nextSuffix;
    }
    // public function model(array $row)
    // {
    //     try {
           
    //         $user = Helper::getAuthenticatedUser();
    //         $organization = $user->organization;
    //         $itemName = trim($row['item_name']);
    //         $category = $this->getCategory($row['category']);
    //         $subCategory = $this->getSubCategory($row['sub_category']);
    //         $hsnCodeId = $this->getHSNCode($row['hsnsac']);
    //         $uomId = $this->getUomId($row['inventory_uom']);
    //         $subType = $row['sub_type'] ?? null;
    //         $categoryInitials = $category->cat_initials;  
            
    //         $subCategoryInitials = $subCategory->sub_cat_initials; 
    //         $itemInitials = strtoupper(substr($itemName, 0, 3));  
    //         $itemCode = $this->generateItemCode($subType, $categoryInitials, $subCategoryInitials, $itemInitials, $organization->id);
    //         $item = Item::create([
    //             'type' => ($row['type'] === 'G') ? 'Goods' : (($row['type'] === 'S') ? 'Service' : 'Goods'),
    //             'category_id' => $category->id,
    //             'subcategory_id' => $subCategory->id,
    //             'item_name' => $itemName,
    //             'item_code'=> $itemCode,
    //             'hsn_id' => $hsnCodeId,
    //             'uom_id' => $uomId,
    //             'min_stocking_level' => !empty($row['min_stocking_level']) ? $row['min_stocking_level'] : null,
    //             'max_stocking_level' => !empty($row['max_stocking_level']) ? $row['max_stocking_level'] : null,
    //             'reorder_level' => !empty($row['reorder_level']) ? $row['reorder_level'] : null,
    //             'min_order_qty' => !empty($row['min_order_qty']) ? $row['min_order_qty'] : null,
    //             'lead_days' => !empty($row['lead_days']) ? $row['lead_days'] : null,
    //             'safety_days' => !empty($row['safety_days']) ? $row['safety_days'] : null,
    //             'shelf_life_days' => !empty($row['shelf_life_days']) ? $row['shelf_life_days'] : null,
    //             'status' => $this->getItemStatus($row['status'] ?? 'Draft'),
    //             'group_id' => $organization->group_id,
    //             'company_id' => $organization->company_id,
    //             'organization_id' => $organization->id
    //         ]);
    
    //         if (isset($row['sub_type']) && $row['sub_type']) {
    //             $subTypeId = $this->getSubTypeId($row['sub_type']);
                
    //             if ($subTypeId) {
    //                 $item->subTypes()->attach($subTypeId);
    //                 Log::info("SubType attached to item: {$subTypeId}");
    //             }
    //         } else {
    //             Log::warning("Sub-Type not found for row: ", $row);
    //         }
    
    //         $this->handleAttributes($item, $row);
    //          $this->handleSpecifications($item, $row);
    //         $this->handleAlternateUoms($item, $row);
    
    //         return $item;
    //     } catch (Exception $e) {
    //         Log::error("Error importing item: " . $e->getMessage(), ['error' => $e]);
    //         throw new Exception("Error importing item: " . $e->getMessage());
    //     }
    // }
    public function model(array $row)
    {
        $user = Helper::getAuthenticatedUser();
        $organization = $user->organization;
        $itemName = trim($row['item_name']);
        $category = $this->getCategory($row['category']);
        $subCategory = $this->getSubCategory($row['sub_category']);
        $hsnCodeId = $this->getHSNCode($row['hsnsac']);
        $uomId = $this->getUomId($row['inventory_uom']);
        $subType = $row['sub_type'] ?? null;
        $categoryInitials = $category->cat_initials;
        $subCategoryInitials = $subCategory->sub_cat_initials;
        $itemInitials = strtoupper(substr($itemName, 0, 3));
        $itemCode = $this->generateItemCode($subType, $categoryInitials, $subCategoryInitials, $itemInitials, $organization->id);
    
        try {
            $attributes = [];
            for ($i = 1; $i <= 10; $i++) {
                if (isset($row["attribute_{$i}_name"]) && isset($row["attribute_{$i}_value"])) {
                    $attributeName = $row["attribute_{$i}_name"];
                    $attributeValue = $row["attribute_{$i}_value"];
                    $requiredBom = $row["required_bom_{$i}"] ?? null; 
                    $allChecked = $row["all_checked_{$i}"] ?? null;
                    if ($attributeName && $attributeValue) {
                        $attributes[] = [
                            'name' => $attributeName,
                            'value' => $attributeValue,
                           'required_bom' => $requiredBom,
                           'all_checked' => $allChecked,  
                            
                        ];
                    
                    }
                }
            }
    
            $specifications = [];
            $specificationGroupName = $row['product_specification_group'] ?? 'Specification';  

            $specifications[] = [
                'group_name' => $specificationGroupName,  
                'specifications' => []  
            ];
    
            for ($i = 1; $i <= 10; $i++) {
                if (isset($row["specification_{$i}_name"]) && isset($row["specification_{$i}_value"])) {
                    $specifications[0]['specifications'][] = [  
                        'name' => $row["specification_{$i}_name"],
                        'value' => $row["specification_{$i}_value"]
                    ];
                }
            }

            $alternateUoms = [];
            for ($i = 1; $i <= 5; $i++) {
                if (isset($row["alternate_uom_{$i}"]) && isset($row["alternate_uom_{$i}_conversion"])) {
                    $alternateUoms[] = [
                        'uom' => $row["alternate_uom_{$i}"],
                        'conversion' => $row["alternate_uom_{$i}_conversion"],
                        'cost_price' => $row["alternate_uom_{$i}_cost_price"] ?? null,
                        'default' => $row["alternate_uom_{$i}_default"] ?? null,
                    ];
                }
            }
    
            $uploadedItem = UploadItemMaster::create([
                'item_name' => $itemName,
                'item_code' => $itemCode,
                'category_id' => $category->id,
                'subcategory_id' => $subCategory->id,
                'hsn_id' => $hsnCodeId,
                'uom_id' => $uomId,
                'type' => ($row['type'] === 'G') ? 'Goods' : (($row['type'] === 'S') ? 'Service' : 'Goods'),
                'status' => $this->getItemStatus($row['status'] ?? 'Draft'),
                'group_id' => $organization->group_id,
                'company_id' => $organization->company_id,
                'organization_id' => $organization->id,
                'sub_type' => $row['sub_type'] ?? null,
                'remarks' => "Processing item upload",
                'batch_no' => $row['batch_no'] ?? null,
                'attributes' => json_encode($attributes),
                'specifications' => json_encode($specifications),
                'alternate_uoms' => json_encode($alternateUoms),
            ]);
    
            Log::info("Stored item in upload_item_masters: {$itemName}");

            $this->processItemFromUpload($uploadedItem);
    
            $uploadedItem->update([
                'status' => 'Processed',
                'remarks' => 'Successfully created item in items table',
            ]);
    
            Log::info("Item successfully moved from upload to items table: {$uploadedItem->item_name}");
    
            return $uploadedItem;
    
        } catch (Exception $e) {
            Log::error("Error importing item: " . $e->getMessage(), [
                'error' => $e,
                'row' => $row 
            ]);
    
            if (isset($uploadedItem)) {
                $uploadedItem->update([
                    'status' => 'Failed',
                    'remarks' => "Error importing item: " . $e->getMessage(),
                ]);
            }
    
            throw new Exception("Error importing item: " . $e->getMessage());
        }
    }
    

    

private function processItemFromUpload(UploadItemMaster $uploadedItem)
{
    try {
        $item = Item::create([
            'type' => $uploadedItem->type,
            'category_id' => $uploadedItem->category_id,
            'subcategory_id' => $uploadedItem->subcategory_id,
            'item_name' => $uploadedItem->item_name,
            'item_code' => $uploadedItem->item_code,
            'hsn_id' => $uploadedItem->hsn_id,
            'uom_id' => $uploadedItem->uom_id,
            'status' => $uploadedItem->status,
            'group_id' => $uploadedItem->group_id,
            'company_id' => $uploadedItem->company_id,
            'organization_id' => $uploadedItem->organization_id,
            'sub_type' => $uploadedItem->sub_type,
            'remarks' => $uploadedItem->remarks,
            'batch_no' => $uploadedItem->batch_no,
        ]);
        $this->handleAttributes($item, json_decode($uploadedItem->attributes, true));
        $this->handleSpecifications($item, json_decode($uploadedItem->specifications, true));
        $this->handleAlternateUoms($item, json_decode($uploadedItem->alternate_uoms, true));


    } catch (Exception $e) {
        $uploadedItem->update([
            'status' => 'Failed',
            'remarks' => "Error creating item: " . $e->getMessage(),
        ]);

        Log::error("Error creating item from upload: " . $e->getMessage(), ['error' => $e]);
        throw new Exception("Error creating item from upload: " . $e->getMessage());
    }
}

    
private function handleAttributes($item, $attributes)
{
    if ($attributes) {
        foreach ($attributes as $attribute) {
            try {
                $attributeGroup = $this->getAttributeGroup($attribute['name']);
                $this->createItemAttribute($item, $attribute, $attributeGroup);
            } catch (Exception $e) {
                Log::error("Failed to handle attribute: " . $e->getMessage());
            }
        }
    }
}
private function createItemAttribute($item, $attribute, $attributeGroup)
{

    $attributeValues = explode(',', $attribute['value']);
    foreach ($attributeValues as $value) {
        try {
            $attributeValue = $this->getAttribute($value, $attributeGroup);
            ItemAttribute::create([
                'item_id' => $item->id,
                'attribute_group_id' => $attributeGroup->id,
                'attribute_id' => $attributeValue->id,
                'required_bom' => $attribute['required_bom'] ?? 0,
                'all_checked' => $attribute['all_checked'] ?? 0,
            ]);
        } catch (Exception $e) {
            Log::error("Failed to create item attribute: " . $e->getMessage());
        }
    }
}
    
private function getAttributeGroup($attributeName)
{
    $attributeGroup = AttributeGroup::where('name', $attributeName)->first();
    if (!$attributeGroup) {
        throw new Exception("AttributeGroup not found for attribute: {$attributeName}");
    }
    return $attributeGroup;
}
private function getAttribute($attributeName, $attributeGroup)
{
    $attributeValues = explode(',', $attributeName);
    foreach ($attributeValues as $value) {
        $value = trim($value);
        $attribute = Attribute::where('value', $value)
            ->where('attribute_group_id', $attributeGroup->id)
            ->first();

        if (!$attribute) {
            Log::error("Attribute not found: {$value} in group {$attributeGroup->id}");
            throw new Exception("Attribute not found: {$value} in group {$attributeGroup->id}");
        }
    }
    return $attribute;
}


    private function getCategory($categoryName)
    {
      
        $category = Category::where('name', $categoryName)->first(['id', 'cat_initials']);
        if (!$category) {
            throw new Exception("Category not found: {$categoryName}");
        }
        return $category;
    }

    private function getHSNCode($hsnCode)
    {
        $hsn = Hsn::where('code', $hsnCode)->first();
        if (!$hsn) {
            throw new Exception("HSN Code not found: {$hsnCode}");
        }
        return $hsn->id;
    }

    private function getSubCategory($subCategoryName)
    {
        $subCategory = Category::where('name', $subCategoryName)->first(['id', 'sub_cat_initials']); 
        if (!$subCategory) {
            throw new Exception("SubCategory not found: {$subCategoryName}");
        }
        return $subCategory; 
    }

    private function getUomId($uomName)
    {
        $uom = Unit::where('name', $uomName)->first();
        if (!$uom) {
            throw new Exception("UOM not found: {$uomName}");
        }
        return $uom->id;
    }

    private function getItemStatus($status)
    {
        return $status === 'submitted' ? 'Active' : 'Draft';
    }

    protected function getSubTypeId($subTypeCode)
    {
        $subTypeMapping = [
            'FG' => 'Finished Goods',
            'SF' => 'WIP/Semi Finished',
            'RM' => 'Raw Material',
            'TI' => 'Traded Item',
            'A'  => 'Asset',
            'E'  => 'Expense'
        ];

        if (isset($subTypeMapping[$subTypeCode])) {
            $subTypeName = $subTypeMapping[$subTypeCode];
            $subType = SubType::where('name', $subTypeName)->first();
            if (!$subType) {
                throw new Exception("SubType not found: {$subTypeName}");
            }
            return $subType->id;
        }

        throw new Exception("Invalid SubType code: {$subTypeCode}");
    }

    private function handleSpecifications($item, $specifications)
    {
        if ($specifications) {
            foreach ($specifications as $specGroup) {
                if (isset($specGroup['specifications']) && is_array($specGroup['specifications'])) {
                    foreach ($specGroup['specifications'] as $spec) {
                        try {
                            $this->createItemSpecification($item, $spec, $specGroup['group_name']);
                        } catch (Exception $e) {
                            Log::error("Failed to handle specification: " . $e->getMessage());
                        }
                    }
                } else {
                    Log::warning("No specifications found for group: " . $specGroup['group_name']);
                }
            }
        }
    }

    private function createItemSpecification($item, $spec, $groupName)
    {
        try {
            $productSpecification = $this->getProductSpecificationByName($spec['name']);
            
            if ($productSpecification) {
                $productSpecificationGroup = $this->getProductSpecificationGroupByName($groupName);
                ItemSpecification::create([
                    'item_id' => $item->id,
                    'specification_id' => $productSpecification->id,
                    'specification_name' => $productSpecification->name,
                    'value' => $spec['value'],
                    'group_id' => $productSpecificationGroup ? $productSpecificationGroup->id : null,
                ]);
            } else {
                Log::warning("ProductSpecification not found for name: {$spec['name']}");
            }
        } catch (Exception $e) {
            Log::error("Failed to create item specification: " . $e->getMessage());
        }
    }
    
    private function getProductSpecificationGroupByName($groupName)
    {
        return ProductSpecification::where('name', $groupName)->first();
    }

    
    private function getProductSpecificationByName($specName)
    {
        return ProductSpecificationDetail::where('name', $specName)->first();
    }

    
    private function handleAlternateUoms($item, $alternateUoms)
    {
        if ($alternateUoms) {
            foreach ($alternateUoms as $uomData) {
                try {
                    $this->createAlternateUom($item, $uomData);
                } catch (Exception $e) {
                    Log::error("Failed to handle alternate UOM: " . $e->getMessage());
                }
            }
        }
    }
    private function createAlternateUom($item, $uomData)
    {
        $uom = $this->getUomId($uomData['uom']);
        if ($uom) {
            AlternateUOM::create([
                'item_id' => $item->id,
                'uom_id' => $uom,
                'conversion_to_inventory' => $uomData['conversion'],
                'cost_price' => $uomData['cost_price'] ?? null,
                'is_selling' => $uomData['default'] === 'S',
                'is_purchasing' => $uomData['default'] === 'P',
            ]);
        } else {
            Log::warning("UOM not found: {$uomData['uom']}");
        }
    }    
    
    
}