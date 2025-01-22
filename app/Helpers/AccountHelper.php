<?php
namespace App\Helpers;
use App\Models\LandParcel;
use App\Models\StockAccount;
use App\Models\SalesAccount;
use App\Models\CogsAccount;
use App\Models\GrAccount;
use App\Models\Organization;
use App\Models\Item;
use App\Models\Book;
use App\Models\Customer;
use Illuminate\Support\Facades\Log;

class AccountHelper
{
    public static function getStockLedgerGroupAndLedgerId($organizationId = null, $itemId = null, $bookId = null)
    {
        $query = StockAccount::query();
        if ($organizationId !== null) {
            $organization = Organization::find($organizationId);
            if ($organization && $organization->group_id !== null) {
                $query->where('group_id', $organization->group_id);
                $stockAccounts = $query->get();
                if ($stockAccounts->isEmpty()) {
                    return ['message' => 'Record not found for the given Group ID.'];
                }
            } else {
                return ['message' => 'Organization not found or does not have a group ID.'];
            }
        } else {
            return ['message' => 'Organization ID is required to proceed.'];
        }
    
        if ($organization && $organization->company_id !== null) {
            $query->where(function ($query) use ($organization) {
                $query->where('company_id', $organization->company_id)
                      ->orWhereNull('company_id');
            });
            $stockAccounts = $query->get();
            if ($stockAccounts->isEmpty()) {
                return ['message' => 'Record not found for the given Company ID.'];
            }
        } else {
            return ['message' => 'Organization does not have a valid Company ID.'];
        }
    
        if ($organization && $organization->id !== null) {
            $query->where(function ($query) use ($organization) {
                $query->where('organization_id', $organization->id)
                      ->orWhereNull('organization_id');
            });
            $stockAccounts = $query->get();
            if ($stockAccounts->isEmpty()) {
                return ['message' => 'Record not found for the given Organization ID.'];
            }
        } else {
            return ['message' => 'Organization ID is required to filter stock accounts.'];
        }
    
        if ($itemId !== null) {
            $itemIds = is_array($itemId) ? $itemId : [$itemId];
            $items = Item::whereIn('id', $itemIds)->get();
            
            if ($items->isEmpty()) {
                return ['message' => 'Record not found for the given item IDs.'];
            }

            foreach ($items as $item) {
                if ($item->category_id !== null) {
                    $query->where(function ($query) use ($item) {
                        $query->where('category_id', $item->category_id)
                              ->orWhereNull('category_id');
                    });
                    $stockAccounts = $query->get();
                    if ($stockAccounts->isEmpty()) {
                        return ['message' => 'Record not found for the given category ID.'];
                    }
                }
    
                if ($item->subcategory_id !== null) {
                    $query->where(function ($query) use ($item) {
                        $query->where('sub_category_id', $item->subcategory_id)
                              ->orWhereNull('sub_category_id');
                    });
                    $stockAccounts = $query->get();
                    if ($stockAccounts->isEmpty()) {
                        return ['message' => 'Record not found for the given subcategory ID.'];
                    }
                }
            }
            $query->where(function ($query) use ($itemIds) {
                foreach ($itemIds as $id) {
                    $query->orWhereRaw("JSON_CONTAINS(item_id, ?)", [json_encode([$id])])
                          ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(item_id, '$[*]')) LIKE ?", ['%' . $id . '%'])
                          ->orWhereNull('item_id');
                }
            });
    
            $stockAccounts = $query->get();
            if ($stockAccounts->isEmpty()) {
                return ['message' => 'Record not found for the given item IDs in stock accounts.'];
            }
        }
        if ($bookId !== null) {
            $bookIds = is_array($bookId) ? $bookId : [$bookId];
            $query->where(function ($query) use ($bookIds) {
                foreach ($bookIds as $id) {
                    $query->orWhereRaw("JSON_CONTAINS(book_id, ?)", [json_encode([$id])])
                        ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(book_id, '$[*]')) LIKE ?", ['%' . $id . '%'])
                        ->orWhereNull('book_id');
                }
            });

            $stockAccounts = $query->get();
            if ($stockAccounts->isEmpty()) {
                return ['message' => 'Record not found for the given Book ID.'];
            }
        }
    
        $stockAccounts = $query->get();
        if ($stockAccounts->isEmpty()) {
            return ['message' => 'Record not found with the applied filters.'];
        }
    
        return $stockAccounts->map(function ($stockAccount) {
            return [
                'ledger_group' => $stockAccount->ledgerGroup ? $stockAccount->ledgerGroup->id : null,
                'ledger_id' => $stockAccount->ledger ? $stockAccount->ledger->id : null,
            ];
        });
    }
    public static function getLedgerGroupAndLedgerIdForSalesAccount($organizationId = null, $customerId = null, $itemId = null, $bookId = null)
    {
        $query = SalesAccount::query();
    
        if ($organizationId !== null) {
            $organization = Organization::find($organizationId);
            if ($organization && $organization->group_id !== null) {
                $query->where('group_id', $organization->group_id);
                $salesAccounts = $query->get();
                if ($salesAccounts->isEmpty()) {
                    return ['message' => 'Record not found for the given Group ID.'];
                }
            } else {
                return ['message' => 'Organization not found or does not have a group ID.'];
            }
        } else {
            return ['message' => 'Organization ID is required to proceed.'];
        }
    
        if ($organization && $organization->company_id !== null) {
            $query->where(function ($query) use ($organization) {
                $query->where('company_id', $organization->company_id)
                      ->orWhereNull('company_id');
            });
            $salesAccounts = $query->get();
            if ($salesAccounts->isEmpty()) {
                return ['message' => 'Record not found for the given Company ID.'];
            }
        } else {
            return ['message' => 'Organization does not have a valid Company ID.'];
        }
    
        if ($organization && $organization->id !== null) {
            $query->where(function ($query) use ($organization) {
                $query->where('organization_id', $organization->id)
                      ->orWhereNull('organization_id');
            });
            $salesAccounts = $query->get();
            if ($salesAccounts->isEmpty()) {
                return ['message' => 'Record not found for the given Organization ID.'];
            }
        } else {
            return ['message' => 'Organization ID is required to filter sales accounts.'];
        }
    
        if ($customerId !== null) {
            $customer = Customer::find($customerId);
            
            if ($customer) {
                if ($customer->category_id !== null) {
                    $query->where(function ($query) use ($customer) {
                        $query->where('customer_category_id', $customer->category_id)
                              ->orWhereNull('customer_category_id');
                    });
                    $salesAccounts = $query->get();
                    if ($salesAccounts->isEmpty()) {
                        return ['message' => 'Record not found for the given customer category ID.'];
                    }
                }
    
                if ($customer->subcategory_id !== null) {
                    $query->where(function ($query) use ($customer) {
                        $query->where('customer_sub_category_id', $customer->subcategory_id)
                              ->orWhereNull('customer_sub_category_id');
                    });
                    $salesAccounts = $query->get();
                    if ($salesAccounts->isEmpty()) {
                        return ['message' => 'Record not found for the given customer subcategory ID.'];
                    }
                }
                $query->where(function ($query) use ($customerId) {
                    $query->orWhereRaw("JSON_CONTAINS(customer_id, ?)", [json_encode([$customerId])])
                          ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(customer_id, '$[*]')) LIKE ?", ['%' . $customerId . '%'])
                          ->orWhereNull('customer_id');
                });
        
                $salesAccounts = $query->get();
                if ($salesAccounts->isEmpty()) {
                    return ['message' => 'Record not found for the given customer in sales accounts.'];
                }
            } else {
                return ['message' => 'Customer not found.'];
            }
        }
    
        if ($itemId !== null) {
            $itemIds = is_array($itemId) ? $itemId : [$itemId];
            $items = Item::whereIn('id', $itemIds)->get();
            
            if ($items->isEmpty()) {
                return ['message' => 'Record not found for the given item IDs.'];
            }
    
            foreach ($items as $item) {
                if ($item->category_id !== null) {
                    $query->where(function ($query) use ($item) {
                        $query->where('item_category_id', $item->category_id)
                              ->orWhereNull('item_category_id');
                    });
                    $salesAccounts = $query->get();
                    if ($salesAccounts->isEmpty()) {
                        return ['message' => 'Record not found for the given item category ID.'];
                    }
                }
    
                if ($item->subcategory_id !== null) {
                    $query->where(function ($query) use ($item) {
                        $query->where('item_sub_category_id', $item->subcategory_id)
                              ->orWhereNull('item_sub_category_id');
                    });
                    $salesAccounts = $query->get();
                    if ($salesAccounts->isEmpty()) {
                        return ['message' => 'Record not found for the given item subcategory ID.'];
                    }
                }
                $query->where(function ($query) use ($itemIds) {
                    foreach ($itemIds as $id) {
                        $query->orWhereRaw("JSON_CONTAINS(item_id, ?)", [json_encode([$id])])
                              ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(item_id, '$[*]')) LIKE ?", ['%' . $id . '%'])
                              ->orWhereNull('item_id');
                    }
                });
        
                $salesAccounts = $query->get();
                if ($salesAccounts->isEmpty()) {
                    return ['message' => 'Record not found for the given item IDs in sales accounts.'];
                }
            }
        }
    
        if ($bookId !== null) {
            $bookIds = is_array($bookId) ? $bookId : [$bookId];
            $query->where(function ($query) use ($bookIds) {
                foreach ($bookIds as $id) {
                    $query->orWhereRaw("JSON_CONTAINS(book_id, ?)", [json_encode([$id])])
                          ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(book_id, '$[*]')) LIKE ?", ['%' . $id . '%'])
                          ->orWhereNull('book_id');
                }
            });
        
            $salesAccounts = $query->get();
            if ($salesAccounts->isEmpty()) {
                return ['message' => 'Record not found for the given Book ID.'];
            }
        }
        

        $salesAccounts = $query->get();
        if ($salesAccounts->isEmpty()) {
            return ['message' => 'Record not found with the applied filters.'];
        }
    
        return $salesAccounts->map(function ($salesAccount) {
            return [
                'ledger_group' => $salesAccount->ledgerGroup ? $salesAccount->ledgerGroup->id : null,
                'ledger_id' => $salesAccount->ledger ? $salesAccount->ledger->id : null,
            ];
        });
    }

    public static function getLedgerGroupAndLedgerIdForLeaseRevenue(int $landParcelId, string $itemType)
    {
        $ledgerId = null;
        $ledgerGroupId = null;
        $landParcel = LandParcel::find($landParcelId);
        $serviceItems = (json_decode($landParcel -> service_item, true));
        if ($landParcel) {
            foreach ($serviceItems as $serviceItem) {
                if (($serviceItem["'servicetype'"] == $itemType)) {
                    $ledgerId = $serviceItem["'ledger_id'"];
                    $ledgerGroupId = $serviceItem["'ledger_group_id'"];
                    break;
                }
            }
        }
        return [
            'ledger_id' => $ledgerId,
            'ledger_group_id' => $ledgerGroupId
        ];
    }

    public static function getCogsLedgerGroupAndLedgerId($organizationId = null, $itemId = null, $bookId = null)
    {
        $query = CogsAccount::query();
        if ($organizationId !== null) {
            $organization = Organization::find($organizationId);
            if ($organization && $organization->group_id !== null) {
                $query->where('group_id', $organization->group_id);
                $cogsAccounts = $query->get();
                if ($cogsAccounts->isEmpty()) {
                    return ['message' => 'Record not found for the given Group ID.'];
                }
            } else {
                return ['message' => 'Organization not found or does not have a group ID.'];
            }
        } else {
            return ['message' => 'Organization ID is required to proceed.'];
        }

        if ($organization && $organization->company_id !== null) {
            $query->where(function ($query) use ($organization) {
                $query->where('company_id', $organization->company_id)
                    ->orWhereNull('company_id');
            });
            $cogsAccounts = $query->get();
            if ($cogsAccounts->isEmpty()) {
                return ['message' => 'Record not found for the given Company ID.'];
            }
        } else {
            return ['message' => 'Organization does not have a valid Company ID.'];
        }

        if ($organization && $organization->id !== null) {
            $query->where(function ($query) use ($organization) {
                $query->where('organization_id', $organization->id)
                    ->orWhereNull('organization_id');
            });
            $cogsAccounts = $query->get();
            if ($cogsAccounts->isEmpty()) {
                return ['message' => 'Record not found for the given Organization ID.'];
            }
        } else {
            return ['message' => 'Organization ID is required to filter cogs accounts.'];
        }
        if ($itemId !== null) {
            $itemIds = is_array($itemId) ? $itemId : [$itemId];
            $items = Item::whereIn('id', $itemIds)->get();

            if ($items->isEmpty()) {
                return ['message' => 'Record not found for the given item IDs.'];
            }

            foreach ($items as $item) {
                if ($item->category_id !== null) {
                    $query->where(function ($query) use ($item) {
                        $query->where('category_id', $item->category_id)
                            ->orWhereNull('category_id');
                    });
                    $cogsAccounts = $query->get();
                    if ($cogsAccounts->isEmpty()) {
                        return ['message' => 'Record not found for the given category ID.'];
                    }
                }

                if ($item->subcategory_id !== null) {
                    $query->where(function ($query) use ($item) {
                        $query->where('sub_category_id', $item->subcategory_id)
                            ->orWhereNull('sub_category_id');
                    });
                    $cogsAccounts = $query->get();
                    if ($cogsAccounts->isEmpty()) {
                        return ['message' => 'Record not found for the given subcategory ID.'];
                    }
                }
            }
            $query->where(function ($query) use ($itemIds) {
                foreach ($itemIds as $id) {
                    $query->orWhereRaw("JSON_CONTAINS(item_id, ?)", [json_encode([$id])])
                        ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(item_id, '$[*]')) LIKE ?", ['%' . $id . '%'])
                        ->orWhereNull('item_id');
                }
            });

            $cogsAccounts = $query->get();
            if ($cogsAccounts->isEmpty()) {
                return ['message' => 'Record not found for the given item IDs in cogs accounts.'];
            }
        }
        if ($bookId !== null) {
            $bookIds = is_array($bookId) ? $bookId : [$bookId];
            $query->where(function ($query) use ($bookIds) {
                foreach ($bookIds as $id) {
                    $query->orWhereRaw("JSON_CONTAINS(book_id, ?)", [json_encode([$id])])
                          ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(book_id, '$[*]')) LIKE ?", ['%' . $id . '%'])
                          ->orWhereNull('book_id');
                }
            });
    
            $cogsAccounts = $query->get();
            if ($cogsAccounts->isEmpty()) {
                return ['message' => 'Record not found for the given Book ID.'];
            }
        }

        $cogsAccounts = $query->get();
        if ($cogsAccounts->isEmpty()) {
            return ['message' => 'Record not found with the applied filters.'];
        }
        return $cogsAccounts->map(function ($cogsAccount) {
            return [
                'ledger_group' => $cogsAccount->ledgerGroup ? $cogsAccount->ledgerGroup->id : null,
                'ledger_id' => $cogsAccount->ledger ? $cogsAccount->ledger->id : null,
            ];
        });
    }
    public static function getGrLedgerGroupAndLedgerId($organizationId = null, $itemId = null, $bookId = null)
    {
        $query = GrAccount::query();
        if ($organizationId !== null) {
            $organization = Organization::find($organizationId);
            if ($organization && $organization->group_id !== null) {
                $query->where('group_id', $organization->group_id);
                $grAccounts = $query->get();
                if ($grAccounts->isEmpty()) {
                    return ['message' => 'Record not found for the given Group ID.'];
                }
            } else {
                return ['message' => 'Organization not found or does not have a group ID.'];
            }
        } else {
            return ['message' => 'Organization ID is required to proceed.'];
        }
        if ($organization && $organization->company_id !== null) {
            $query->where(function ($query) use ($organization) {
                $query->where('company_id', $organization->company_id)
                    ->orWhereNull('company_id');
            });
            $grAccounts = $query->get();
            if ($grAccounts->isEmpty()) {
                return ['message' => 'Record not found for the given Company ID.'];
            }
        } else {
            return ['message' => 'Organization does not have a valid Company ID.'];
        }
        if ($organization && $organization->id !== null) {
            $query->where(function ($query) use ($organization) {
                $query->where('organization_id', $organization->id)
                    ->orWhereNull('organization_id');
            });
            $grAccounts = $query->get();
            if ($grAccounts->isEmpty()) {
                return ['message' => 'Record not found for the given Organization ID.'];
            }
        } else {
            return ['message' => 'Organization ID is required to filter GR accounts.'];
        }
        if ($itemId !== null) {
            $itemIds = is_array($itemId) ? $itemId : [$itemId];
            $items = Item::whereIn('id', $itemIds)->get();
            
            if ($items->isEmpty()) {
                return ['message' => 'Record not found for the given item IDs.'];
            }

            foreach ($items as $item) {
                if ($item->category_id !== null) {
                    $query->where(function ($query) use ($item) {
                        $query->where('category_id', $item->category_id)
                            ->orWhereNull('category_id');
                    });
                    $grAccounts = $query->get();
                    if ($grAccounts->isEmpty()) {
                        return ['message' => 'Record not found for the given category ID.'];
                    }
                }

                if ($item->subcategory_id !== null) {
                    $query->where(function ($query) use ($item) {
                        $query->where('sub_category_id', $item->subcategory_id)
                            ->orWhereNull('sub_category_id');
                    });
                    $grAccounts = $query->get();
                    if ($grAccounts->isEmpty()) {
                        return ['message' => 'Record not found for the given subcategory ID.'];
                    }
                }
            }
            $query->where(function ($query) use ($itemIds) {
                foreach ($itemIds as $id) {
                    $query->orWhereRaw("JSON_CONTAINS(item_id, ?)", [json_encode([$id])])
                        ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(item_id, '$[*]')) LIKE ?", ['%' . $id . '%'])
                        ->orWhereNull('item_id');
                }
            });

            $grAccounts = $query->get();
            if ($grAccounts->isEmpty()) {
                return ['message' => 'Record not found for the given item IDs in GR accounts.'];
            }
        }
        if ($bookId !== null) {
            $bookIds = is_array($bookId) ? $bookId : [$bookId];
            $query->where(function ($query) use ($bookIds) {
                foreach ($bookIds as $id) {
                    $query->orWhereRaw("JSON_CONTAINS(book_id, ?)", [json_encode([$id])])
                        ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(book_id, '$[*]')) LIKE ?", ['%' . $id . '%'])
                        ->orWhereNull('book_id');
                }
            });

            $grAccounts = $query->get();
            if ($grAccounts->isEmpty()) {
                return ['message' => 'Record not found for the given Book ID.'];
            }
        }
        $grAccounts = $query->get();
        if ($grAccounts->isEmpty()) {
            return ['message' => 'Record not found with the applied filters.'];
        }
        return $grAccounts->map(function ($grAccount) {
            return [
                'ledger_group' => $grAccount->ledgerGroup ? $grAccount->ledgerGroup->id : null,
                'ledger_id' => $grAccount->ledger ? $grAccount->ledger->id : null,
            ];
        });
    }

}
