<?php

namespace App\Http\Controllers\Api\Client;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Printed;
use App\PrintTransaction;
use App\User;
use Illuminate\Support\Facades\DB;

class PrintApi extends Controller
{
    public function __construct()
    {
        $this->middleware('client', ["except" => ["printClient"]]);
    }

    public function print(Request $request)
    {
        $request->validate([
            "template" => "required",
            "type" => "required",
            "json" => "required"
        ]);
        $templateID               = $request->input('template');
        $type                     = $request->input('type');
        $encode                   = $request->input('json');

        $data = Printed::print($encode, $type, $templateID, $this->getCurrentClient($request)->user_id);

        $response["statusCode"] = 200;
        $response["data"] = $data;

        return response()->json($response);
    }

    public function printClient(Request $request)
    {
        $request->validate([
            "client_id" => "required|integer",
            "client_secret" => "required|string",
            "template" => "required",
            "type" => "required",
            "json" => "required"
        ]);
        $templateID               = $request->input('template');
        $type                     = $request->input('type');
        $encode                   = $request->input('json');

        $client_id = $request->client_id;
        $client_secret = $request->client_secret;

        $client = DB::table("oauth_client")
            ->where("id", $client_id)
            ->where("secret", $client_secret)
            ->where("user_id", "!=", null)
            ->where("revoked", 1)
            ->where("personal_access_client", 0)
            ->where("password_client", 0)->first();

        if (!$client) {
            abort(401, "Client credentials fail.");
        }

        $user = User::findOrFail($client->user_id);

        $data = Printed::print($encode, $type, $templateID, $user->id);

        $response["statusCode"] = 200;
        $response["data"] = $data;

        return response()->json($response);
    }

    public function allPrinted(Request $r)
    {
        $data = Printed::allPrinted($this->getCurrentClient($r)->user_id);
        $response["statusCode"] = 200;
        $response["data"] = $data;

        return response()->json($response);
    }

    public function printTransaction(Request $r)
    {
        $r->validate([
            "printed_id" => "required",
            "status" => "required",
            "print_time" => "required|date_format:Y-m-d h:i:s"
        ]);

        $print = Printed::findOrFail($r->printed_id);

        $data = PrintTransaction::create([
            "user_id" => $this->getCurrentClient($r)->user_id,
            "printed_id" => $r->printed_id,
            "status" => $r->status,
            "print_time" => $r->print_time,
            "json" => $print->json,
            "url" => $print->url
        ]);
        $response["statusCode"] = 200;
        $response["data"] = $data;

        return response()->json($response);
    }

    public function allPrintTransaction(Request $r)
    {
        $data = PrintTransaction::where("user_id", $this->getCurrentClient($r)->user_id)->get();
        $response["statusCode"] = 200;
        $response["data"] = $data;

        return response()->json($response);
    }

    public function allUnPrinted(Request $r)
    {
        $data = Printed::where("user_id", $this->getCurrentClient($r)->user_id)->with("transaction")->doesntHave("transaction")->get();
        $response["statusCode"] = 200;
        $response["data"] = $data;

        return response()->json($response);
    }
}
