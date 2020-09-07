<?php

namespace App\Http\Controllers;

use Session;
use App\Models\User;
use App\Models\Patient;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function signin(Request $request)
    {
        request()->validate([
            'email'    => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $user = auth()->user();
            return redirect('/dashboard')->withSuccess('Bem-vindo, ' . $user->name . '!');
        }
        return redirect()->back()->withError('Ops! Login incorreto.');
    }

    public function makeAppLogin(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $user = auth()->user();
            return response()->json([
                'success'   => true,
                'api_token' => $user->api_token
            ]);
        }
        return response()->json(['success' => false]);
    }

    public function logout()
    {
        Session::flush();
        Auth::logout();
        return redirect()->route('login');
    }
}
