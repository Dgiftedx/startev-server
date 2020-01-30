<?php

namespace App\Http\Controllers;

use App\Jobs\SendConfirmationMail;
use App\Jobs\SendEmailNotification;
use App\Models\Business;
use App\Models\Graduate;
use App\Models\Mentor;
use App\Models\Student;
use App\Models\Trainee;
use App\Models\User;
use App\Models\UserHiddenFeed;
use App\Models\Vocal;
use App\Models\VocalReferral;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\HelperController as AdminHelperController;

class ApiAuthController extends Controller
{

    protected $user;

    /**
     * AuthController constructor.
     * @param User $userModel
     */
    public function __construct(User $userModel)
    {
        $this->user = $userModel;
        $this->middleware('auth:api', ['except' => ['login','register','verify']]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login( Request $request )
    {
        //Get login credentials from request
        $credentials = [
            'email' => $request->get('email'),
            'password' => $request->get('password')
        ];

//        $ttl_in_minutes = 2;
//        $ttl_in_minutes = 60*24*100;
        //if credentials does not match/exists
//        if (! $token = auth()->setTTL($ttl_in_minutes)->attempt($credentials)) {
        if (! $token = auth()->attempt($credentials)) {
            //return error message
            return response()->json(['error' => 'Invalid email address or Password'], '401');
        }

        //return user instance with token
        return $this->respondWithToken($token);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register( Request $request )
    {
        $data = $request->all();

        $refCode = $data['ref_code'];

        //check if use with thesame email exists
        if ($this->user->where('email', '=', $data['email'])->exists()) {
            return response()->json(['error' => 'User with same email already exists. Go to login page and click forgot password'], '401');
        }

        unset($data['ref_code']);
        $data['slug'] = uniqid(rand(), true);

        $user = $this->user->create($data);

        //handle referral logic and determine if this user was referred or not
        $this->handleVocalRegistration($user->id, $refCode);

        $data['user_id'] = $user->id;

        //send Welcome Mail
        $this->sendVerificationMail($data);

        if ($data['role'] === 'student') {
            // Log a new profile for student & login
            Student::create($data);

            return response()->json(['slug' => $data['slug'], 'email' => $data['email']]);
        }

        if ($data['role'] === 'graduate') {
            // Log a new profile for student & login
            Graduate::create($data);
            return response()->json(['slug' => $data['slug'], 'email' => $data['email']]);
        }

        if ($data['role'] === 'mentor'){
            // If Mentor, log a new profile & login
            Mentor::create($data);

            return response()->json(['slug' => $data['slug'], 'email' => $data['email']]);
        }

        if ($data['role'] === 'business'){

            //otherwise, it's a business body, Log & login
            Business::create(['user_id' => $user->id]);

            return response()->json(['slug' => $data['slug'], 'email' => $data['email']]);
//            return $this->login($request);
        }

        return response()->json(['error' => "Your account cannot be created this time, Please contact support"], 401);
    }


    /**
     * @param $userID
     * @param $RefCode
     */
    private function handleVocalRegistration($userID, $RefCode)
    {

        // Get the vocal with this referral code
        $vocal = Vocal::where('ref_code', '=', $RefCode)->first();


        // If found, proceed
        if ($vocal) {

            // Check if vocal and user haven't been created already, if no, proceed
            if (!VocalReferral::where('vocal_id','=',$vocal->id)->where('user_id','=',$userID)->exists()) {

                // Arrange the data to create a new entry
                $toSeed = [
                    'vocal_id' => $vocal->id,
                    'user_id' => $userID,
                    'registered_on' => Carbon::now()->toDateTimeString()
                ];

                // Log referral record
                VocalReferral::create($toSeed);
            }
        }
    }

    public function verify(Request $request)
    {
        $data = $request->all();

        if (!$this->user->where('slug', '=', $data['slug'])->exists()) {
            return response()->json(['error' => "Your Account couldn't be found or Invalid Token. Please contact out support team"], 401);
        }

        $user = $this->user->whereSlug($data['slug'])->first();

        if (!is_null($user->email_verified_at)) {
            return response()->json(['message' => "Your email has already been verified"]);
        }

        $this->user->find($user->id)->update(['email_verified_at' => Carbon::now()->toDateTimeString()]);

        auth()->login($user);

        //return user instance with token
        $cert =  $this->refresh();
//        return response()->json(['error'=>$user,$cert->original['accessData']['role']]);

        $data['user_id'] = $user->id;
        $data['role'] = $cert->original['accessData']['role'];

        //send mail
        $this->sendWelcomeMail($data);

        return response()->json(['data' => $cert], 200);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }


    public function sendVerificationMail($data)
    {
        $user = User::find($data['user_id']);
        $mailContents = [
            'to' => $user->email,
            'subject' => 'Confirm Your Email Address',
            'message' => "Welcome $user->name <br/><br/> Please confirm your email address by clicking the button below. You might not be able to log in without email confirmation",
            'token' => $user->slug,
            'base_url' => env('APP_BASE_URL','https://startev.africa')
        ];

        dispatch(new SendConfirmationMail($mailContents));
    }

    public function sendWelcomeMail($data)
    {
        $user = $this->user->find($data['user_id']);
        $mailContents = [
            'to' => $user->email,
            'subject' => 'Welcome to Startev Africa',
//            'message' => "Welcome $user->name <br/> Your Username is $user->username",
            'message' => "Hello $user->name <br/>Welcome to Startev Africa"
        ];

        if ($data['role'] === 'student') {
            return HelperController::sendMail($mailContents, 'student-welcome-mail');
        }

        if ($data['role'] === 'mentor') {
            return HelperController::sendMail($mailContents, 'mentor-welcome-mail');
        }

        return HelperController::sendMail($mailContents, 'business-welcome-mail');
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {

        $roleData = HelperController::fetchRoleData(auth()->user()->id);

        $userData = [
            'roleData' =>  $roleData['data'],
            'role' => $roleData['role'],
            'progress' => (new ApiAccountController($this->user))->checkProfileProgress(auth()->user()->id)
        ];

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user(),
            'accessData' => $userData
        ]);
    }

    public function removeAccount( Request $request )
    {
        $data = $request->all();

        if (auth()->check()) {
            AdminHelperController::handleDeletion($data);
            return response()->json(['success' => true]);
        }

        return response()->json(['error' => "You're not authorized to initiate this deletion" ]);
    }
}
