<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\CareerPath;
use App\Models\City;
use App\Models\Country;
use App\Models\Graduate;
use App\Models\Mentor;
use App\Models\Partnership;
use App\Models\State;
use App\Models\Student;
use App\Models\Trainee;
use App\Models\User;
use App\Models\VerificationRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ApiAccountController extends Controller
{

    /**
     * @var User
     */
    protected $user;

    protected $url;


    /**
     * ApiAccountController constructor.
     * @param User $userModel
     */
    public function __construct( User $userModel )
    {
        $this->user = $userModel;
        $this->middleware('auth:api');
        $this->url = url('/');
    }



    public function submitVerification( Request $request )
    {

        $user_id = $request->get('user_id');
        if (VerificationRequest::where('user_id', '=', $user_id)->exists()) {
            return response()->json(['error' => "verification documents has already been submitted"], 401);
        }


        $documentType = $request->get('document_type');
        $filePath = HelperController::processFileUpload($request->file('document_file'), $documentType);

        $data = [
            'user_id' => $user_id,
            'document_type' => $documentType,
            'document' => $filePath
        ];

        $role = $request->get('role');

        if ($role === 'mentor') {
            $mentor = Mentor::where('user_id','=', $user_id)->first();
            Mentor::find($mentor->id)->update(['verification_status' => 'pending']);
        }else{
            $business = Business::where('user_id','=',$user_id)->first();
            Business::find($business->id)->update(['verification_status' => 'pending']);
        }

        $update = HelperController::fetchRoleData($user_id);

        VerificationRequest::create($data);

        return response()->json(['success' => true, 'message' => "Verification Documents Submitted", 'roleData' => $update]);

    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCompletionProgress()
    {
        return response()->json(['progress' => $this->checkProfileProgress(auth()->user()->id)]);
    }


    public function myPartners( $user_id )
    {
        $partners = Partnership::where('user_id', '=', $user_id)->with('venture')->with('business')->get();
        return response()->json($partners);
    }


    public function businessPartners( $user_id )
    {
        $partners = Partnership::where('business_id', '=', Business::businessId($user_id))->with('user')->with('venture')->get();
        return response()->json($partners);
    }

    /**
     * @param $id
     * @return float|int
     */
    public function checkProfileProgress( $id )
    {
        $user = $this->user->find($id);
        $roleData = HelperController::fetchRoleData($id);

        $totalFields = 0;
        $filled = 0;

        $userObj = (array) $user;
        $roleDataObj = (array) $roleData['data'];

        $userObj = $userObj["\x00*\x00attributes"];
        $roleDataObj = $roleDataObj["\x00*\x00attributes"];

        foreach ($userObj as $key => $item){
            $totalFields++;
            if (!is_null($userObj[$key])) {
                $filled++;
            }
        }

        foreach ($roleDataObj as $index => $value) {
            $totalFields++;

            if (!is_null($roleDataObj[$index])){
                $filled++;
            }
        }

        $progressCal = ( $filled / $totalFields ) * 100;
        $progressCal = round($progressCal);

        return $progressCal;
    }


    public function suggestions($user_id)
    {
        $ids = $this->getSuggestionsIds($user_id);

        $users = $this->user->whereIn('id', array_unique($ids))->get(['id','name','avatar','slug']);

        if (count($users) > 0) {
            foreach ($users as $user) {
                $user->roleData = HelperController::fetchRoleData($user->id);
            }
        }

        return response()->json($users, 200);
    }

    private function getSuggestionsIds($user_id)
    {
        $suggestionsId = [];

        $roleData = HelperController::fetchRoleData($user_id);

        switch ($roleData['role']) {
            case 'student':
            case 'graduate':

                if (!is_null($roleData['data']->institution)) {
                    $suggestionsId = Student::orderBy('id','desc')
                        ->where('user_id', '!=' ,$user_id)
                        ->where('institution', 'LIKE', $roleData['data']->institution)
                        ->pluck('user_id')->toArray();
                }

                if(count($suggestionsId) === 0) {
                    $suggestionsId = Student::where('user_id', '!=' ,$user_id)->orderBy('id','desc')
                        ->take(5)->
                        pluck('user_id')->toArray();
                }

                break;

            case 'mentor':
                if (!is_null($roleData['data']->workExperience)) {
                    $suggestionsId = Mentor::where('user_id', '!=' ,$user_id)
                        ->whereNotNull('workExperience')
                        ->where('workExperience', 'LIKE', $roleData['data']->workExperience[0]['company'])
                        ->pluck('user_id')->toArray();
                }

                if(count($suggestionsId) === 0 && !is_null($roleData['data']->current_job_position)) {
                    $suggestionsId = Mentor::where('user_id', '!=', $user_id)
                        ->whereNotNull('current_job_position')
                        ->where('current_job_position', 'LIKE', $roleData['data']->current_job_position)
                        ->pluck('user_id')->toArray();
                }else{
                    $suggestionsId = Mentor::where('user_id', '!=' ,$user_id)->orderBy('id','desc')->take(5)->pluck('user_id')->toArray();
                }


                break;


            case 'business':

                if (!is_null($roleData['data']->services)) {
                    $suggestionsId = Business::where('user_id', '!=', $user_id)->where('services', 'LIKE', $roleData['data']->services)->pluck('user_id')->toArray();
                }
                break;

        }


        return $suggestionsId;
    }





    /**
     * @param $id
     * @param bool $general
     * @return array
     */
    public function prepareProfile($id, $general = false)
    {
        $roleData = HelperController::fetchRoleData($id);

        if ($general) {
            $user = $this->user->with('industries')->with('trainerPivot')->with('trainerPivot.loneTrainee')->find($id);
        }else{
            $user = $this->user->with('industries')->find($id);
        }

        $followers = $user->followers()->get();

        foreach ($followers as $follower){
            if ($user->isFollowing($follower->id)){
                $follower->isFollowing = true;
            }else{
                $follower->isfollowing = false;
            }
        }

        $profileData = [
            'roleData' =>  $roleData['data'],
            'role' => $roleData['role'],
            'user' => $user,
            'progress' => $this->checkProfileProgress($id),
            'followers' => $user->followers()->get(),
            'following' => $user->followings()->get()
        ];

        return $profileData;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile()
    {
        $profileData = $this->prepareProfile(auth()->user()->id);
        return response()->json(['profileData' =>  $profileData]);
    }

    /**
     * Collect top profiles
     * @return \Illuminate\Http\JsonResponse
     */
    public function topProfiles()
    {
        $ids = $this->gatherTopProfiles();
        //get with verified users.
//        $users = $this->user->whereIn('id', $ids)->whereNotNull('email_verified_at')->get(['id','avatar','name']);

        //escape verification check
        $users = $this->user->whereIn('id', $ids)->get(['id','avatar','name','slug']);

        if (count($users) > 0) {
            foreach ($users as $user) {
                $user->roleData = HelperController::fetchRoleData($user->id);
            }
        }
        return response()->json($users, 200);
    }


    public function featuredMentors()
    {
        $mentorsId = $this->gatherFeaturedMentors();

        $mentors = $this->user->whereIn('id',$mentorsId)->get(['id','avatar','name','slug']);

        if (count($mentors) > 0) {
            foreach ($mentors as $mentor) {
                $mentor->roleData = HelperController::fetchRoleData($mentor->id);
            }
        }

        return response()->json($mentors, 200);
    }


    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function newSignUps()
    {
        $users = $this->user
            ->inRandomOrder()
            ->whereBetween('created_at', [Carbon::now()->subDays(3)->toDateTimeString(), Carbon::now()->toDateTimeString()])
            ->take(10)
            ->get(['id','avatar','name','slug']);

        if (count($users) > 0) {
            foreach ($users as $user) {
                $user->roleData = HelperController::fetchRoleData($user->id);
            }
        }

        return response()->json($users, 200);
    }



    private function gatherFeaturedMentors($max = 10)
    {
        $trainers = [];
        $collection = [];

        Trainee::orderBy('id','asc')->get(['trainer_id','trainee_id'])
            ->mapToGroups(function($item) use (&$trainers) {

                $trainers[$item->trainer_id][] = $item->trainee_id;

                return [];
            });

        foreach ($trainers as $key => $trainer) {
            $collection[$key] = count($trainers[$key]);
        }

        asort($collection);

        if (count($collection) > $max) {
            $max = count($collection);
        }

        $returnedId = array_slice($collection, 0, $max, true);

        return array_keys($returnedId);
    }


    /**
     * Fetch top profiles IDs
     * @return array
     */
    private function gatherTopProfiles()
    {

        $collection = [];
        $countCollection = [];

        DB::table('followables')
            ->where('relation', '=', 'follow')
            ->where('followable_type', '=', 'App\Models\User')
            ->get()->mapToGroups(function ($item) use (&$collection) {

                $collection[$item->user_id][] = $item->followable_id;
                return [];
            });

        foreach ($collection as $user_id => $item) {
            $countCollection[$user_id] = count($collection[$user_id]);
        }

        asort($countCollection);

        //only get first 10;
        $max = 10;

        if (count($countCollection) > $max) {
            $max = count($countCollection);
        }

       $returnedId = array_slice($countCollection, 0, $max, true);

        return array_keys($returnedId);
    }

    public function generalProfile( $slug )
    {
        $authUser = $this->user->find(auth()->user()->id);
        $user = $this->user->whereSlug($slug)->first();
        $profileData = $this->prepareProfile($user->id, true);
        return response()->json(['profileData' =>  $profileData, 'myFollowers' => $authUser->followings()->get()]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateProfile( Request $request, $id )
    {
        $data = $request->all();

        $search = User::where('email','=',$data['email'])->first();

        if (!is_null($search)) {
            if ($search->id !== (int) $id) {
                return response()->json(['error' => "please enter a valid email address"], 401);
            }
        }

        if ($request->has('country') && !is_null($data['country'])){
            $country = Country::find($data['country']);
            $data['country'] = $country->name;
        }

        if ($request->has('state') && !is_null($data['state'])){
            $state = State::find($data['state']);
            $data['state'] = $state->name;
        }

        $data['dob'] = Carbon::parse($data['dob'])->toDateTimeString();

        $this->user->find($id)->update($data);

        $profileData = $this->prepareProfile($id);

        return response()->json(['success' => true, 'profileData' => $profileData]);
    }

    public function updatePasswordData( Request $request, $id )
    {
        $password = $request->get('password');

        User::find($id)->update(['password' => $password]);

        return response()->json(['success' => true ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStudentData( Request $request, $type, $id )
    {
        $data = $request->all();

        if (isset($data['bio'])) {
            $bio = $data['bio'];
            $this->user->find($id)->update(['bio' => $bio]);
            unset($data['bio']);
        }

        if ($request->has('careerPath') && !is_null($data['careerPath'])) {
            $career = CareerPath::find($data['careerPath']);
            $data['careerPath'] = $career->name;
        }

        if ($request->has('secondaryCP') && !is_null($data['secondaryCP'])) {
            $career = CareerPath::find($data['secondaryCP']);
            $data['secondaryCP'] = $career->name;
        }

        if ($type === 'student') {
            $student = Student::where('user_id','=',$id)->first();
            Student::find($student->id)->update($data);
            $update = Student::find($student->id);
        }else{
            $student = Graduate::where('user_id','=',$id)->first();
            Graduate::find($student->id)->update($data);
            $update = Graduate::find($student->id);
        }

        $progress = $this->checkProfileProgress($id);

        return response()->json(['success' => true, 'roleData' => $update, 'progress' => $progress]);

    }


    public function updateMentorData( Request $request, $id )
    {
        $data = $request->all();

        if (isset($data['bio'])) {
            $bio = $data['bio'];
            $this->user->find($id)->update(['bio' => $bio]);
            unset($data['bio']);
        }
        
        $mentor = Mentor::where('user_id','=',$id)->first();
        Mentor::find($mentor->id)->update($data);
        $update = Mentor::find($mentor->id);
        $progress = $this->checkProfileProgress($id);
        return response()->json(['success' => true, 'roleData' => $update, 'progress' => $progress]);
    }


    public function updateIndustryData( Request $request, $id )
    {
        $industries = $request->get('industries');

        //first pull old user -> industry data
        DB::table('user_industry')->where('user_id','=', $id)->delete();

        foreach ($industries as $industry) {
            DB::table('user_industry')->insert([
                'user_id' => $id,
                'industry_id' => $industry['id'],
                'created_at'=> Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
        }

        $updated = $this->user->with('industries')->find($id);
        return response()->json(['success' => true ,'updated' => $updated->industries]);
    }


    public function updateBusinessData( Request $request, $id )
    {
        $data = $request->all();

        $business = Business::where('user_id','=',$id)->first();

        Business::find($business->id)->update($data);

        $updated = Business::find($business->id);

        $progress = $this->checkProfileProgress($id);

        return response()->json(['success' => true, 'roleData' => $updated, 'progress' => $progress]);
    }


    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateAvatar( Request $request, $id )
    {
        $data = $request->all();

        $image = str_replace('data:image/png;base64,', '', $data['image']);
        $image = str_replace(' ', '+', $image);
        $path = $this->url. '/storage' . HelperController::processSimpleUpload(base64_decode($image), $id);

        $this->user->find($id)->update(['avatar' => $path]);

        $user = $this->user->find($id);

        return response()->json(['user' => $user]);
    }

    public function updateHeaderImage( Request $request, $id )
    {
        $data = $request->all();

        $user = $this->user->find($id);

        $oldImage = $user->bg_image;

        if (strpos($oldImage, $this->url) !== false) {
            $actualImageLink = str_replace($this->url,'',$oldImage);
            //Remove old image from storage if exists
            if(Storage::disk('public')->exists($actualImageLink)){
                //remove
                Storage::disk('public')->delete($actualImageLink);
            }
        }

        $path = $this->url. '/storage'. HelperController::processImageUpload($data['image'],$user->name);

        $user->update(['bg_image' => $path]);

        $user = $this->user->find($id);

        return response()->json(['user' => $user, 'url' => $this->url]);

    }


    public function removeBgImage($user_id)
    {
        $user = $this->user->find($user_id);

        $oldImage = $user->bg_image;

        if (strpos($oldImage, $this->url) !== false) {
            $actualImageLink = str_replace($this->url,'',$oldImage);
            //Remove old image from storage if exists
            if(Storage::disk('public')->exists($actualImageLink)){
                //remove
                Storage::disk('public')->delete($actualImageLink);
            }
        }

        $user->update(['bg_image' => null]);

        $user = $this->user->find($user_id);

        return response()->json($user);
    }


    public function generateCode( Request $request )
    {
        $data = $request->all();
        $code = HelperController::generateIdentifier(4) . $data['store_id'];
        return response()->json($code);
    }

    public function toggleConnection( $user, $mentor )
    {
        $connections = HelperController::toggleConnection($user, $mentor);
        return response()->json(['connections' => $connections['connections'], 'message' => $connections['message']]);
    }

}
