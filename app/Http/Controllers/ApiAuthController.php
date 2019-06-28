<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Chat\Message;
use App\Models\Chat\MessageConversation;
use App\Models\Feed;
use App\Models\FeedComment;
use App\Models\Graduate;
use App\Models\Mentor;
use App\Models\Partnership;
use App\Models\Store\UserInvoice;
use App\Models\Store\UserStore;
use App\Models\Student;
use App\Models\Trainee;
use App\Models\User;
use App\Models\UserHiddenFeed;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $credentials = $request->only(['username', 'password']);

        //if credentials does not match/exists
        if (! $token = auth()->attempt($credentials)) {
            //return error message
            return response()->json(['error' => 'Invalid username & Password'], '401');
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

        //check if use with thesame email exists
        if ($this->user->where('email', '=', $data['email'])->exists()) {
            return response()->json(['error' => 'User with same email already exists. Go to login page and click forgot password.'], '401');
        }

        $data['slug'] = uniqid(rand(), true);
        $user = $this->user->create($data);

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

        else if ($data['role'] === 'mentor'){
            // If Mentor, log a new profile & login
            Mentor::create($data);

            return response()->json(['slug' => $data['slug'], 'email' => $data['email']]);
        }else {

            //otherwise, it's a business body, Log & login
            Business::create(['user_id' => $user->id]);

            return response()->json(['slug' => $data['slug'], 'email' => $data['email']]);
//            return $this->login($request);
        }
    }

    public function verify(Request $request)
    {
        $data = $request->all();

        if (!$this->user->where('slug', '=', $data['slug'])->exists()) {
            return response()->json(['error' => "Your Account couldn't be found. Please contact out support team"], 401);
        }

        $user = $this->user->whereSlug($data['slug'])->first();

        if (!is_null($user->email_verified_at)) {
            return response()->json(['message' => "Your email has already been verified"]);
        }

        $this->user->find($user->id)->update(['email_verified_at' => Carbon::now()->toDateTimeString()]);

        auth()->login($user);

        //return user instance with token
        $cert =  $this->refresh();

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
            'subject' => 'Confirm Your Email Address :: Startev Africa',
            'message' => "Welcome $user->name <br/> Please confirm your email address by clicking the button below. You might not be able to log in without email confirmation",
            'token' => $user->slug
        ];

        return HelperController::sendMail($mailContents, 'confirm-email');
    }

    public function sendWelcomeMail($data)
    {
        $user = $this->user->find($data['user_id']);
        $mailContents = [
            'to' => $user->email,
            'subject' => 'Welcome to Startev Africa',
            'message' => "Welcome $user->name <br/> Your Username is $user->username"
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
            $this->handleDeletion($data);

            return response()->json(['success' => true]);
        }

        return response()->json(['error' => "You're not authorized to initiate this deletion" ]);
    }


    private function handleDeletion( $data )
    {
        $userID = $data['user_id'];

        $roleData = HelperController::fetchRoleData($userID);

        $feeds = Feed::where('user_id', '=', $userID)->get();

        foreach ($feeds as $feed) {
            if (!is_null($feed->image)) {
                HelperController::removeImage($feed->image);
            }


            $feed->delete();
        }

        DB::table('feed_comments')->where('user_id', '=', $userID)->get();

        DB::table('messages')->where('sender_id','=', $userID)->delete();

        DB::table('message_conversations')->where('sender_id','=', $userID)->delete();

        DB::table('partnerships')->where('user_id','=', $userID)->delete();

        DB::table('trainees')->where('trainer_id', '=', $userID)->delete();

        DB::table('user_hidden_feeds')->where('user_id','=',$userID)->delete();

        DB::table('user_industry')->where('user_id','=', $userID)->delete();

        if ($roleData['role'] === 'business') {
            $business_id = Business::businessId($userID);

            DB::table('user_business_products')->where('business_id','=', $business_id)->delete();

            DB::table('user_business_settings')->where('business_id', '=', $business_id)->delete();

            DB::table('business_ventures')->where('business_id','=', $business_id)->delete();

            DB::table('businesses')->where('user_id','=', $userID)->delete();
        }


        if ($roleData['role'] === 'mentor') {
            DB::table('mentors')->where('user_id','=', $userID)->delete();
        }


        if ($roleData['role'] === 'students') {
            DB::table('students')->where('user_id', '=', $userID)->delete();

            $store_id = UserStore::storeId($userID);

            DB::table('user_venture_products')->where('store_id','=', $store_id)->delete();

            DB::table('user_venture_reviews')->where('store_id', '=', $store_id)->delete();
        }

        DB::table('user_stores')->where('user_id','=', $userID)->delete();


        DB::table('user_notifications')->where('user_id','=', $userID)->delete();


        $user = User::find($userID);

        if (!is_null($user->avatar)) {
            HelperController::removeImage($user->avatar);
        }

        if (!is_null($user->bg_image)) {
            HelperController::removeImage($user->bg_image);
        }


        $user->delete();
    }
}
