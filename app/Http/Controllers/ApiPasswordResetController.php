<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Mail\ResetPasswordMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ApiPasswordResetController extends Controller
{

    /**
     * @var User
     */
    protected $user;

    /**
     * ApiPasswordResetController constructor.
     * @param User $userModel
     */
    public function __construct( User $userModel )
    {
        $this->user = $userModel;
    }


    private function getPasswordResetTableRow($request)
    {
        return DB::table('password_resets')->where('token','=',$request->get('resetToken'))->first();
    }


    /**
     * Handle password reset for user
     * @param Request $request
     * @return mixed
     */
    public function sendEmail( Request $request )
    {
        if(!$this->validateEmail($request->get('email'))){
            $message = 'Email does\'t  exist in our record';
            return $this->failedResponse($message);
        }

        $this->send($request->get('email'));
        $message = 'Password reset mail sent successfully. Please check your inbox';
        return $this->successResponse($message);
    }


    public function changePassword( ChangePasswordRequest $request )
    {
        $verified = $this->getPasswordResetTableRow($request);
        if (is_null($verified)) {
            $message = "Invalid Token Supplied. Please resend password reset";
            return $this->failedResponse($message);
        }

        $user = $this->user->where('email','=',$verified->email)->first();
        $this->user->find($user->id)->update(['password' => $request->get('password')]);

        DB::table('password_resets')->where('token','=', $verified->token)->delete();

        $message = "Password changed successfully. You can now log in with your new password";

        return $this->successResponse( $message );
    }


    public function send($email)
    {
        // Generate reset token
        $token = $this->createToken($email);
        //send email
        Mail::to($email)->send(new ResetPasswordMail($token));
    }

    /**
     * Validate user email if it exists in database
     * @param $email
     * @return bool
     */
    public function validateEmail($email)
    {
        return !!$this->user->where('email','=',$email)->first();
    }

    /**
     * Create token for password reset
     * @param $email
     * @return mixed
     */
    public function createToken($email)
    {
        $oldToken = DB::table('password_resets')->where('email','=',$email)->first();

        if ($oldToken) {
            return $oldToken->token;
        }
        $token = uniqid(rand(), true);
        $this->saveToken($token, $email);

        return $token;
    }


    public function saveToken($token, $email)
    {
        DB::table('password_resets')->insert([
            'email' => $email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);
    }

    /**
     * return error response to API call
     * @param $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function failedResponse($message)
    {
        return response()->json([
            'error' => $message
        ], Response::HTTP_NOT_FOUND);
    }

    /**
     * return success response to API Call
     * @param $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function successResponse($message)
    {
        return response()->json([
            'data' => $message
        ], Response::HTTP_OK);
    }
}
