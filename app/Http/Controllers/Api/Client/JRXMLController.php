<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Template;

class JRXMLController extends Controller
{
    public function __construct()
    {
        $this->middleware('client');
    }

    public function allJRXML(Request $request)
    {
        $data = Template::where("user_id", $this->getCurrentClient($request)->user_id)->get();
        $response["statusCode"] = 200;
        $response["data"] = $data;
        return response()->json($response);
    }
}
