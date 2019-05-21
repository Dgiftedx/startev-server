<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\CareerPath;
use App\Models\City;
use App\Models\Country;
use App\Models\Mentor;
use App\Models\State;
use App\Models\Student;
use App\Models\User;
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

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCompletionProgress()
    {
        return response()->json(['progress' => $this->checkProfileProgress(auth()->user()->id)]);
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


    /**
     * @param $id
     * @return array
     */
    public function prepareProfile($id)
    {
        $roleData = HelperController::fetchRoleData($id);
        $user = $this->user->with('industries')->find($id);
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
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateProfile( Request $request, $id )
    {
        $data = $request->all();

        if ($request->has('country') && !is_null($data['country'])){
            $country = Country::find($data['country']);
            $data['country'] = $country->name;
        }

        if ($request->has('state') && !is_null($data['state'])){
            $state = State::find($data['state']);
            $data['state'] = $state->name;
        }

        if ($request->has('city') && !is_null($data['city'])){
            $city = City::find($data['city']);
            $data['city'] = $city->name;
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
    public function updateStudentData( Request $request, $id )
    {
        $data = $request->all();

        if ($request->has('careerPath') && !is_null($data['careerPath'])) {
            $career = CareerPath::find($data['careerPath']);
            $data['careerPath'] = $career->name;
        }

        if ($request->has('secondaryCP') && !is_null($data['secondaryCP'])) {
            $career = CareerPath::find($data['secondaryCP']);
            $data['secondaryCP'] = $career->name;
        }

        $student = Student::where('user_id','=',$id)->first();

        Student::find($student->id)->update($data);

        $update = Student::find($student->id);

        $progress = $this->checkProfileProgress($id);

        return response()->json(['success' => true, 'roleData' => $update, 'progress' => $progress]);

    }


    public function updateMentorData( Request $request, $id )
    {
        $data = $request->all();
        $workExperience = $data['workExperience'];
        $education = $data['education'];


        /**
         * if fist and second item in the array is not empty, format the date field,
         * else remove entire field from array to be saved
         */
        if (!is_null($workExperience[0]['company']) && !is_null($workExperience[0]['position'])){
            foreach ($data['workExperience'] as $index => $experience) {

                if (!is_null($data['workExperience'][$index]['from_date'])) {
                    $data['workExperience'][$index]['from_date'] = Carbon::parse($data['workExperience'][$index]['from_date'])->toDateTimeString();
                }

                if (!is_null($data['workExperience'][$index]['to_date'])) {
                    $data['workExperience'][$index]['to_date'] = Carbon::parse($data['workExperience'][$index]['to_date'])->toDateTimeString();
                }
            }
        }else{
            unset($data['workExperience']);
        }

        /**
         * if fist and second item in the array is not empty, format the date field,
         * else remove entire field from array to be saved
         */
        if (!is_null($education[0]['institution']) && !is_null($education[0]['program'])){
            foreach ($data['education'] as $index => $edu) {

                if (!is_null($data['education'][$index]['from_date'])) {
                    $data['education'][$index]['from_date'] = Carbon::parse($data['education'][$index]['from_date'])->toDateTimeString();
                }

                if (!is_null($data['education'][$index]['to_date'])) {
                    $data['education'][$index]['to_date'] = Carbon::parse($data['education'][$index]['to_date'])->toDateTimeString();
                }
            }
        }else{
            unset($data['education']);
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

}
