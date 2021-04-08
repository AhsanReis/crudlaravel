<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Profile;

class ProfileController extends Controller
{
    public function index()
    {
        $data['page_title'] = "Profile";
        $data['dbprofile'] = Profile::get();
        return view('page.profile', $data);
    }
    public function insert($request)
    {
        # code...
    }
}
