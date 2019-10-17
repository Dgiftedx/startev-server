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

    public function newOrders()
    {
        return view('pages.order.new');
    }

    public function confirmedOrders()
    {
        return view('pages.order.confirmed');
    }
    public function dispatchedOrders()
    {
        return view('pages.order.dispatched');
    }


    public function cancelledOrders()
    {
        return view('pages.order.cancelled');
    }

    public function deliveredOrders()
    {
        return view('pages.order.delivered');
    }


    public function helpTips()
    {
        return view('pages.contents.help-tips');
    }

    public function adverts()
    {
        return view('pages.contents.adverts');
    }

    public function transactions()
    {
        return view('pages.transactions');
    }


    public function sideData()
    {
        return view('pages.settings.site-data');
    }

    public function storePayoutView()
    {
        return view('pages.payouts.store');
    }

    public function deliveryPayoutView()
    {
        return view('pages.payouts.delivery');
    }

    public function businessPayoutView()
    {
        return view('pages.payouts.business');
    }
}
