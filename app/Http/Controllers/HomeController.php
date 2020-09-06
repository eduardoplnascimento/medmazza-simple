<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function signin()
    {
        $user = auth()->user();
        if ($user) {
            return redirect('dashboard');
        }
        return view('signin');
    }

    public function signup()
    {
        return view('signup');
    }
}
