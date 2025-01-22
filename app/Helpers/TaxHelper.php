<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use App\Models\Hsn;

class TaxHelper
{
    public static function calculateTax(int $hsnId, float $price, int $fromCountry, int $fromState, int $upToCountry, int $upToState, string $transactionType): array
    {
        $taxDetails = [];

        $hsn = Hsn::where('id', $hsnId)
            ->where('status', 'active')
            ->first();

        if (!$hsn) {
            throw new \Exception('Active HSN code not found.');
        }

        $placeOfSupply = self::determinePlaceOfSupply($fromCountry, $fromState, $upToCountry, $upToState);

        $taxPatterns = $hsn->taxPatterns()
            ->where('from_price', '<=', $price)
            ->where('upto_price', '>=', $price)
            ->get();
            
        if ($taxPatterns->isEmpty()) {
            return $taxDetails; 
        }

        foreach ($taxPatterns as $taxPattern) {
            $taxGroup = $taxPattern->taxGroup;

            if ($taxGroup) {
                $taxes = $taxGroup->taxDetails()
                    ->where('status', 'active')
                    ->get();

                foreach ($taxes as $taxDetail) {
                    if ($taxDetail->place_of_supply === $placeOfSupply) {
                        $matches = ($transactionType === 'purchase') 
                            ? $taxDetail->is_purchase 
                            : $taxDetail->is_sale;

                        if ($matches) {
                            $taxDetails[] = [
                                'id' => $taxDetail->id,
                                'applicability_type' => $taxGroup->applicability_type,
                                'tax_group' => $taxGroup->tax_group,
                                'tax_percentage' => $taxDetail->tax_percentage,
                                'tax_type' => $taxDetail->tax_type,
                                'tax_id' => $taxDetail ->id,
                                'tax_code' => $taxDetail->tax_code,
                            ];
                        }
                    }
                }
            }
        }

        return $taxDetails;
    }

    private static function determinePlaceOfSupply(int $fromCountry, int $fromState, int $upToCountry, int $upToState): string
    {
        if ($fromCountry === $upToCountry) {
            return ($fromState === $upToState) ? 'Intrastate' : 'Interstate';
        }
        return 'Overseas';
    }
}
