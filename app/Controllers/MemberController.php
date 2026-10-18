<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class MemberController extends BaseController
{
    public function dashboard()
    {
        return view('member/dashboard');
    }
}
