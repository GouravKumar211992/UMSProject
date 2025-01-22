<?php

namespace App\Providers;

use App\Helpers\Helper;  
use App\Models\OrganizationMenu;
use App\Models\OrganizationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\View;
use App\Models\UserOrganizationMapping;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Map 'employee' type to the Employee model
        Relation::morphMap([
            'employee' => \App\Models\Employee::class,
        ]);

        // Share common data across all views
        View::composer('*', function ($view) {

            $user = Helper::getAuthenticatedUser();

            if ($user) {
                // Handle undefined organization or group
                if (!$user->organization || !$user->organization->group_id) {
                    \Log::warning('User organization or group_id is missing', ['user_id' => $user->id]);
                    return;
                }

                $organizationId = $user->organization_id;

                // Fetch organization menus based on services
                $menues = OrganizationMenu::where('group_id', $user->organization->group_id)
                    ->whereNull('parent_id')
                    ->orderBy('sequence','ASC')->get(); 
                    
               
                // Fetch user organization mappings
                $mappings = UserOrganizationMapping::where('user_id', $user->id)
                    ->with(['organization:id,name'])
                    ->get();

                // Fetch Organization Logo
                $orgLogo = Helper::getOrganizationLogo($organizationId);

                // Pass organization id and mappings
                $view->with([
                    'menues' => $menues,
                    'organizations' => $mappings, 
                    'organization_id' => $organizationId,
                    'orgLogo' => $orgLogo,
                    'logedinUser'=> $user
                ]);
            } else {
                \Log::warning('View composer: Authenticated user not found');
            }
        });
    }
}
