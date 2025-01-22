<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PeterPetrus\Auth\PassportToken;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = @$_COOKIE['sso_token'];

        if ($token) {
            $row = explode("|", urldecode($token));

            if (!empty($row[0])) {
                $tokenRow = PassportToken::dirtyDecode($row[0]);
            }

            if (!empty($row[1])) {
                Session::put('organization_id', $row[1]);
            }

            $dbName = env('DB_DATABASE');
            if (!empty($row[2])) {
                $dbName = $row[2];
                Session::put('DB_DATABASE', $dbName);
                config(['database.connections.mysql.database' => $dbName]);
                DB::reconnect('mysql');
            }

            if (!empty($row[3])) {
                $authType = $row[3];
            }

            if (!empty($authType) && !empty($tokenRow['user_id'])) {
                if ($authType == 'auth-0') {
                    $authType = 'user';
                    Auth::guard('web')->login(User::find($tokenRow['user_id']));
                } else if ($authType == 'auth-1') {
                    $authType = 'employee';
                    Auth::guard('web2')->login(Employee::find($tokenRow['user_id']));
                }

                $request->merge(['auth_type' => $authType]);
            }

            $request->merge(['db_name' => $dbName]);

        } else {

            // if ($_SERVER['SERVER_NAME'] === 'erp.thepresence360.com') {
            //     return redirect('https://login.thepresence360.com');
            // }
            // // Hardcoded user login for user
            $user = User::find(1);
            // if ($user) {
            //     Auth::guard('web')->login($user);
            //     Auth::guard('web2')->logout();
            //     $request->merge(['auth_type' => 'user', 'db_name' => env('DB_DATABASE')]);
          // $user = Employee::find(1);
         //$user = Employee::find(1);
            if ($user) {
                Auth::guard('web2')->login($user);
                Auth::guard('web')->logout();
                $request->merge(['auth_type' => 'user', 'db_name' => env('DB_DATABASE')]);
            } else {
                return redirect(env('MAIN_APP_URL', 'http://143.110.243.104/'));
            }

            // // Hardcoded user login for employee
        }

        return $next($request);
    }
}
