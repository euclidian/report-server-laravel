<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Template;
use Illuminate\Support\Facades\Auth;

class JRXMLController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function allJRXML()
    {
        $data = Template::where("user_id", Auth::user()->id)->get();
        $response["statusCode"] = 200;
        $response["data"] = $data;
        return response()->json($response);
    }

    public function addJRXML(Request $r)
    {
        $r->validate([
            'template' => 'required|mimetypes:application/octet-stream,text/xml'
        ]);
        $file = $r->file('template');
        $nama_file = str_random(20) . "." . $file->getClientOriginalExtension();

        $data = new Template();
        if ($r->id) {
            $data = Template::findOrFail($r->id);
        }

        $data->filename =  $file->getClientOriginalName();
        $data->realfilename = $nama_file;
        $data->user_id = Auth::user()->id;
        $data->save();

        $file->move(config("report.dir") . "/user_id_" . Auth::user()->id, $nama_file);
        $response["statusCode"] = 200;
        $response["data"] = $nama_file;
        return response()->json($response);
    }

    public function deleteJRXML($id)
    {
        $data = Template::findOrfail($id);
        $data->delete();
        $response["statusCode"] = 200;
        $response["data"] = $data;
        return response()->json($response);
    }
}
