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
use Mailgun\Mailgun;

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


    private function getPasswordResetTableRow($token)
    {
        return DB::table('password_resets')->where('token','=',$token)->first();
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
        $message = 'Password reset mail has been sent to your mailbox. Please check your inbox';
        return $this->successResponse($message);
    }


    public function changePassword( Request $request )
    {

        $verified = $this->getPasswordResetTableRow($request->get('resetToken'));
        if (is_null($verified)) {
            $message = "Invalid Token Supplied. Please resend password reset";
            return $this->failedResponse($message);
        }

        $user = $this->user->where('email','=',$verified->email)->first();
        $this->user->find($user->id)->update(['password' => $request->get('password')]);

        DB::table('password_resets')->where('token','=', $verified->token)->delete();

        $credentials = [
            'email' => $verified->email,
            'password' => $request->get('password')
        ];

        //login user
        auth()->attempt($credentials);

        //return user instance with token
        $cert =  (new ApiAuthController($this->user))->refresh();

        return response()->json(['data' => $cert], Response::HTTP_OK);
    }


    public function send($email)
    {
        // Generate reset token
        $token = $this->createToken($email);
        //send email
//        Mail::to($email)->send(new ResetPasswordMail($token));
        return HelperController::sendMail(['message' => $token, 'to' => $email,'subject' => 'Password Reset'], 'password-reset');
    }



    public function resendConfirmEmail( Request $request )
    {
        $data = $request->all();
        $user = $this->user->whereSlug($data['slug'])->first();

        $data['user_id'] = $user->id;

        //send Welcome Mail
        (new ApiAuthController($this->user))->sendVerificationMail($data);

        return response()->json(['success' => true]);
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
