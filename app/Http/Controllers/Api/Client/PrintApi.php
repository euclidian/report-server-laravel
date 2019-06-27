<?php

namespace App\Http\Controllers\Api\Client;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Printed;
use App\PrintTransaction;

class PrintApi extends Controller
{
    public function __construct()
    {
        $this->middleware('client');
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
