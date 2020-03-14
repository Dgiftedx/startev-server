<?php

namespace App\Http\Controllers;

use App\BlockUser;
use App\Models\User;
use App\Models\UserContact;
use Illuminate\Http\Request;

class ContactListController extends Controller
{
    /**
     * @var User
     */
    protected $user;

    protected $contacts;

    /**
     * ApiAccountController constructor.
     * @param User $userModel
     * @param UserContact $userContact
     */
    public function __construct( User $userModel, UserContact $userContact )
    {
        $this->user = $userModel;
        $this->contacts = $userContact;
        $this->middleware('auth:api');
    }

    public function index( $id )
    {
        $contacts = [];

        $contactBook = $this->grabContactList($id);
        if (!is_null($contactBook)) {
            $contacts = User::whereIn('id', $contactBook->contacts_id)->get(['id','slug','avatar','name','username','status']);
            foreach ($contacts as $contact) {
                $contact->roleData = HelperController::fetchRoleData($contact->id);
            }
        }
        $connections = $this->grabConnections($id);

        return response()->json(['contacts' => $contacts, 'connections' => $connections]);
    }

    private function grabContactList($id)
    {
        return $this->contacts->where('user_id', '=', $id)->first();
    }

    private function grabConnections($id)
    {
        $contactIds = HelperController::pullContacts($id);

        $contacts = User::whereIn('id',$contactIds)->get(['id','slug','avatar','name','username','status']);
        foreach ($contacts as $contact) {
            $contact->status = $contact->isOnline();
            $contact->roleData = HelperController::fetchRoleData($contact->id);
        }

        return $contacts;
    }


    public function addToContact( Request $request )
    {
        $data = $request->all();

        $contactBook = $this->contacts->where('user_id','=', $data['user_id'])->first();

        if (is_null($contactBook)) {
            //user doesn't have a contact book yet. create it
            $contactIds = [];
            $contactIds[] = $data['contact_id'];
            $this->contacts->create(['user_id' => $data['user_id'], 'contacts_id' => $contactIds]);

            return response()->json(['success' => true]);
        }

        $contactIds = $contactBook->contacts_id;
        array_push($contactIds, $data['contact_id']);

        $this->contacts->find($contactBook->id)->update(['contacts_id' => null]);
        $this->contacts->find($contactBook->id)->update(['contacts_id' => $contactIds]);

        return response()->json(['success' => true]);
    }


    public function removeFromContact( Request $request )
    {
        $data = $request->all();
        $contactBook = $this->contacts->where('user_id','=', $data['user_id'])->first();

        $contactIds = $contactBook->contacts_id;

        $index = array_search($data['contact_id'], $contactIds);
        array_splice($contactIds, $index, 1);

        $this->contacts->find($contactBook->id)->update(['contacts_id' => null]);
        $this->contacts->find($contactBook->id)->update(['contacts_id' => $contactIds]);

        return response()->json(['success' => true]);
    }
    public function getBlockUserList( $id) {
        $blockList = BlockUser::where('user_id', '=', $id)->get();
        foreach ($blockList as $list) {
            $list->userdetail = User::where('id',$list->blocked_user_id)->first();
        }
        return response()->json(['success' => $blockList]);
    }
    public function allBlockUserlist() {
        $all = BlockUser::all();
        return response()->json(['success' => $all]);
    }
    public function deletefromBlocklist(Request $request) {
        $all = $request->all();
        (new BlockUser)
            ->where('blocked_user_id',$all['ruser_id'])
            ->where('user_id',$all['user_id'])
            ->delete();
        $blockList = BlockUser::where('user_id', '=', $all['user_id'])->get();
        foreach ($blockList as $list) {
            $list->userdetail = User::where('id',$list->blocked_user_id)->first();
        }
        return response()->json(['success' => true, 'list' => $blockList]);
    }

}
