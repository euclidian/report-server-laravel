<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function getUser()
    {
        return Auth::user();
    }

    public function updatePassword(Request $r)
    {
        $r->validate([
            "password" => "required",
            "new_password" => "required"
        ]);
        $user = User::findOrFail(Auth::user()->id);
        if (!Hash::check($r->password, $user->password)) {
            abort(400);
        }
        $user->password = Hash::make($r->new_password);;
        $user->save();
        $response["statusCode"] = 200;
        $response["data"] = $user;
        return response()->json($response);
    }
}
