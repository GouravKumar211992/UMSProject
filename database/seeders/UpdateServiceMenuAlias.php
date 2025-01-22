<?php

namespace Database\Seeders;

use App\Models\OrganizationMenu;
use App\Models\PermissionMaster;
use App\Models\ServiceMenu;
use DB;
use Exception;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UpdateServiceMenuAlias extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::beginTransaction();
        try {
            $serviceMenus = ServiceMenu::where('name', 'Quotation') -> get();
    
            foreach ($serviceMenus as $serviceMenu) {
                $serviceMenu -> alias = "sales-quotation";
                $serviceMenu -> save();
            }
    
            $organizationMenus = OrganizationMenu::where('name', 'Quotation') -> get();
    
            foreach ($organizationMenus as $organizationMenu) {
                $organizationMenu -> alias = "sales-quotation";
                $organizationMenu -> save();
            }
    
            $permissionMasters = PermissionMaster::where('name', 'Quotation') -> get();
    
            foreach ($permissionMasters as $permissionMaster) {
                $permissionMaster -> alias = "menu.sales-quotation";
                $permissionMaster -> save();
            }
            DB::commit();
        } catch(Exception $ex) {
            DB::rollBack();
            dd($ex -> getMessage());
        }
    }
}
