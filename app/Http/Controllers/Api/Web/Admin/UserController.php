<?php

namespace App\Http\Controllers\Api\Web\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Laravel\Passport\ClientRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('admin');
    }

    public function allUser()
    {
        $data = User::select("users.id", "users.name", "users.email", "users.admin", "users.created_at", "oauth_clients.id as secretid", "oauth_clients.secret")
            ->orderBy("name")
            ->leftJoin('oauth_clients', 'users.id', '=', 'oauth_clients.user_id')
            ->get();

        $response["statusCode"] = 200;
        $response["data"] = $data;
        return response()->json($response);
    }

    public function toAdmin($id)
    {
        $data = User::findOrFail($id);

        $data->admin = $data->admin == 1 ? 0 : 1;
        $data->update();

        $response["statusCode"] = 200;
        $response["data"] = $data;
        return response()->json($response);
    }

    public function addUser(Request $r)
    {
        $r->validate([
            "name" => "required",
            "email" => "required",
            "password" => "required"
        ]);
        if (User::where("email", $r->email)->first()) {
            abort(409);
        }
        $password = Hash::make($r->password);
        $data = User::create([
            "name" => $r->name,
            "email" => $r->email,
            "password" => $password
        ]);
        $response["statusCode"] = 200;
        $response["data"] = $data;
        return response()->json($response);
    }

    public function generateToken($id)
    {
        DB::table("oauth_clients")->where("password_client", 0)->where("user_id", $id)->delete();
        $user = User::findOrFail($id);

        $CR = new ClientRepository;
        $CR->create($id, $user->name, "localhost");
        $response["statusCode"] = 200;
        $response["data"] = $CR;
        return response()->json($response);
    }
}
