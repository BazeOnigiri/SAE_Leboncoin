<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PrivacyController extends Controller
{
    /**
     * Display the privacy policy page.
     */
    public function policy()
    {
        return view('privacy.policy');
    }
}
