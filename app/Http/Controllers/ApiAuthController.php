<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Mentor;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;

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
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        //Get login credentials from request
        $credentials = request(['username', 'password']);

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
        $user = $this->user->create($data);
        $data['user_id'] = $user->id;

        if ($data['role'] === 'student') {
            // Log a new profile for student & login
            Student::create($data);

            return $this->login($request);
        }
        else if ($data['role'] === 'mentor'){
            // If Mentor, log a new profile & login
            Mentor::create($data);

            return $this->login($request);
        }else {

            //otherwise, it's a business body, Log & login
            Business::create($data);

            return $this->login($request);
        }
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
}
