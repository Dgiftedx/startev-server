<?php

namespace App\Http\Controllers;

use App\Models\Admin\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    /**
     * HomeController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('pages.dashboard');
    }

    /**
     * Show the application user profile.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function profile()
    {
        return view('pages.profile');
    }


    public function getProfile()
    {
        $user = Admin::find(auth()->guard('admin')->user()->id);
        return response()->json(['success' => true, 'profile' => $user]);
    }

    public function updateProfile( Request $request, $id )
    {
        $data = $request->all();

        if (isset($data['password']) && !is_null($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }else{
            unset($data['password']);
        }

        Admin::find($id)->update($data);
        $user = Admin::find($id);

        return response()->json(['success' => true, 'profile' => $user]);
    }

    public function updateProfileAvatar( Request $request, $id )
    {
        $avatar = $request->file('avatar');
        $user = Admin::find($id);

        if (!is_null($user->avatar)) {

            $old = str_replace("/storage", "", $user->avatar);
            if(Storage::disk('public')->exists($old)){
                //remove
                Storage::disk('public')->delete($old);
            }
        }

        $path = "/storage" . HelperController::processAvatarUpload($avatar, $user->name, 'admin');

        $user->update(['avatar' => $path]);

        $user = Admin::find($id);

        return response()->json(['success' => true, 'profile' =>$user]);
    }
}
