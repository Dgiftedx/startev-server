<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class TestController extends Controller
{


    protected $user;

    public function __construct(User $userModel)
    {
        $this->user = $userModel;
    }


    public function index()
    {
        dd((new ApiAccountController($this->user))->checkProfileProgress(1));
    }
}
