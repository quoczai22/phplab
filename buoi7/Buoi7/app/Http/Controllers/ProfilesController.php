<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profiles;

class ProfilesController extends Controller
{
    public function index()
    {
        $profiles = Profiles::with('user')->get();
        return view('profiles.index', compact('profiles'));
    }
}
