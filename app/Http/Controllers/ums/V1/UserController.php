<?php

namespace App\Http\Controllers\V1;

use Models\User;
use Models\Service;
use Models\MailBox;
use Models\AccountUser;
use Lib\Setup\User\Account;
use Illuminate\Http\Request;
use Models\SubscriptionService;
use App\Helpers\ConstantHelper;
use App\Services\Mailers\Mailer;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Lib\Validation\User as Validator;
use App\Exceptions\NotFoundException;
use App\Exceptions\ApiGenericException;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $length = !empty($request->length)?$request->length:ConstantHelper::PAGE_LIMIT_20;

        $account_user_id = Auth::guard('api')->id();
        $user = User::where('account_user_id', $account_user_id)->first();

        $teamData = User::where('organization_id', $user->organization_id)
            // ->where('id','!=',$user->id)
            ->with('roles');

        if($request->search) {
            $teamData->where('first_name','like', '%' .$request->search. '%');
            $teamData->orWhere('last_name','like', '%' .$request->search. '%');
        }

        $team = $teamData->paginate($length);

        return [
            'data' => $team
        ];
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!$this->canInviteMoreUser()){
            throw new ApiGenericException(__('message.user_add_limit_reached'));
        }

        $account_user = Auth::guard('api')->user();
        $user = User::where('account_user_id','=',$account_user->id)->first();
        $validator = (new Validator($request))->store($user);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $user = (new Account($account_user, $request))->store();

        return [
            'message' => __('message.invitation_sent'),
            'data' => $user
        ];
    }

    public function reInvite(User $user)
    {
        if(!$user){
            throw new ApiGenericException(__('message.doesnt_exist',['static' => __('static.user')]),404);
        }else if($user->status == ConstantHelper::ACTIVE){
            return [
                'message' => __('message.already_activated',['static' => __('static.user')])
            ];
        }

        $account = AccountUser::find($user->account_user_id);

        if(!empty($account) ){
            $account_remember_token = bcrypt(\Str::random(6));
            $remember_token = bcrypt(\Str::random(6));

            $account->remember_token =  $account_remember_token;
            $account->save();

            $user->update(['remember_token' => $remember_token]);

            if($account->status == ConstantHelper::PENDING){
                $activationUrl = sprintf('%s/activate?token=%s',env('WEB_URL'), $remember_token);
                $link = sprintf('%s/create-password?token=%s&redirectUri=%s',env('ACCOUNT_WEB_URL'), $account_remember_token, $activationUrl);
            }else{
                $link = sprintf('%s/activate?token=%s',env('WEB_URL'), $remember_token);
            }

            $mailbox = new MailBox();
            $mailData = array(
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'link' => sprintf('%s/create-password?token=%s&source=%s',env('user_WEB_URL'), $remember_token,env('WEB_URL'))
            );
            $mailbox->mail_body = json_encode($mailData);
            $mailbox->mail_to = $user->email;
            $mailbox->layout = 'invitation';
            $mailbox->subject = __('message.user_invitation_subject',['invited_by' => $user->first_name ,'service' => ConstantHelper::SERVICE_NAME]);
            $mailbox->save();
            $mailer = new Mailer;
            $mailer->emailTo($mailbox);
        }

        return [
            'message' => __('message.invitation_sent')
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $account_user = Auth::guard('api')->user();
        $loggedInUser = User::where('account_user_id','=',$account_user->id)->first();

        $user->load('roles');

        if($loggedInUser->organization_id != $user->organization_id) {
            throw new NotFoundException(__('message.doesnt_exist',['static' => __('static.user')]));
        }
        return [
            "data" => $user,
        ];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $userData = User::with('roles')->find($id);

		return [
            'data' => $userData
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $authUser = Auth::user();
        $accountUser = AccountUser::find($user->account_user_id);

        $validator = (new Validator($request))->update($user);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $user = (new Account($authUser, $request))->update($accountUser);

		return [
            'message' => __('message.updated successfully',['static' => __('static.user')]),
            "data" => $user,
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {

        $user->delete();
        return [
            'message' => __('message.deleted successfully',['static' => __('static.user')]),
        ];
    }

    private function canInviteMoreUser()
    {
        $accountUser = Auth::guard('api')->user();
        $service = Service::where('alias','=',ConstantHelper::SERVICE_ALIAS)->first();

         $subscriptionService = SubscriptionService::whereHas('subscription', function($q) use($accountUser){
            $q->where('organization_id', $accountUser->organization_id);
        })->where('service_id', $service->id)->first();

        return $subscriptionService->available_no_of_users ? true : false;
    }
}
