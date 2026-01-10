<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CookieController extends Controller
{
    /**
     * Display the cookie policy page.
     */
    public function policy()
    {
        return view('cookies.policy');
    }
}
