<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ViewsController extends Controller
{
    /**
     * HomeController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function allAdminUsersView()
    {
        return view('pages.all-admin-users');
    }

    public function platformMentors()
    {
        return view('pages.platform.mentors');
    }

    public function platformStudents()
    {
        return view('pages.platform.students');
    }

    public function platformGraduates()
    {
        return view('pages.platform.graduates');
    }

    public function platformBusinesses()
    {
        return view('pages.platform.businesses');
    }

    public function verificationRequests()
    {
        return view('pages.verification.requests');
    }

    public function vocalsView()
    {
        return view('pages.platform.vocals');
    }

    public function createVocalProfileView()
    {
        return view('pages.platform.create-vocal');
    }

    public function compose()
    {
        return view('pages.mailbox.compose');
    }


    public function helpTips()
    {
        return view('pages.contents.help-tips');
    }
}
