<?php

namespace App\Controllers;

class HomeController extends BaseController
{
    public function index()
    {
        return view('home', [
            'isLoggedIn' => session()->get('isLoggedIn') === true,
            'userRole'   => session()->get('user_role'),
            'userName'   => session()->get('user_name'),
        ]);
    }
}
