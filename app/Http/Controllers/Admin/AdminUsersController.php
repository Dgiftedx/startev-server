<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\HelperController;

class AdminUsersController extends Controller
{

    protected $admin;

    /**
     * AdminUsersController constructor.
     * @param Admin $adminModel
     */
    public function __construct( Admin $adminModel )
    {
        $this->admin = $adminModel;
        $this->middleware('auth:admin');
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function all()
    {
        $superAdministrators = $this->admin->role('super')->pluck('id')->toArray();
        $users = $this->admin->whereNotIn('id',$superAdministrators)->orderBy('id','desc')->get();

        foreach ($users as $user) {
            $user->roles = $user->getRoleNames();
        }
        $roles = Role::orderBy('name','asc')->get();
        return response()->json(['success' => true, 'users' => $users, 'roles' => $roles]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeAdmin( Request $request )
    {
        $data = $request->all();

        if ($this->admin->where('email', '=', $data['email'])->exists()) {
            return response()->json(['error' => "User with the same email already exists"]);
        }

        $data['slug'] = uniqid(rand(), true);

        if (isset($data['avatar']) && $data['avatar'] !== 'undefined') {

            if ($request->file('avatar')->isValid()) {
                $avatar = $request->file('avatar');
                $path = "/storage" . HelperController::processAvatarUpload($avatar, $data['name'], 'admin');

                $data['avatar'] = $path;
            }

        }else{
            unset($data['avatar']);
        }


        $role = $data['role'];

        unset($data['role']);

        $user = Admin::create($data);

        $user->assignRole($role);

        return response()->json(['success' => true, 'message' => "Admin user created successfully"]);
    }


    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateAdmin( Request $request, $id )
    {
        $data = $request->all();

        $user = $this->admin->find($id);

        if (isset($data['avatar']) && $data['avatar'] !== 'undefined') {

            if ($request->file('avatar')->isValid()) {

                if ($user->avatar) {
                    HelperController::removeImage($user->avatar);
                }

                $avatar = $request->file('avatar');
                $path = "/storage" . HelperController::processAvatarUpload($avatar, $data['name'], 'admin');

                $data['avatar'] = $path;
            }

        }else {
            unset($data['avatar']);
        }


        //synchronize roles if set
        if(isset($data['role']) && !is_null($data['role']) && $data['role'] !== 'undefined') {
            $user->syncRoles($data['role']);
        }


        $user->update($data);

        return response()->json(['success' => true, 'message' => "{$user->name} data was updated successfully"]);

    }


    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id )
    {
        $user = $this->admin->find($id);
        if ($user->avatar) {
            HelperController::removeImage($user->avatar);
        }

        $user->delete();
        return response()->json(['success' => true, 'message' => "Admin user removed successfully"]);
    }

}
