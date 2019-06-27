<?php

namespace App\Http\Controllers\Api\Web;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;
use App\Printed;
use App\PrintTransaction;

class PrintApi extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
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
        
        $data = Printed::print($encode, $type, $templateID,Auth::user()->id);

        $response["statusCode"] = 200;
        $response["data"] = $data;

        return response()->json($response);
    }

    public function allPrinted()
    { 
        $data["list"] = Printed::where("user_id",Auth::user()->id)->with("template")->orderBy('created_at', 'desc')->paginate(25);
        $data["baseurl"] = config("tokenapi.download_url");
        $response["statusCode"] = 200;
        $response["data"] = $data;

        return response()->json($response);
    }

    public function allPrintTransaction()
    {
        $data = PrintTransaction::where("user_id",Auth::user()->id)->paginate(25);
        $response["statusCode"] = 200;
        $response["data"] = $data;

        return response()->json($response);
    }
}
