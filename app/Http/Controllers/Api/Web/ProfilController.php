<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfilController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function getUser()
    {
        return User::findOrFail(Auth::user()->id);
    }

    public function updatePassword(Request $r)
    {
        $r->validate([
            "password" => "required",
            "new_password" => "required"
        ]);
        $user = User::findOrFail(Auth::user()->id);
        if (!Hash::check($r->password, $user->password)) {
            abort(400,"Password tidak sama dengan sebelumnya");
        }
        $user->password = Hash::make($r->new_password);
        $user->save();
        $response["statusCode"] = 200;
        $response["data"] = $user;
        return response()->json($response);
    }
}
