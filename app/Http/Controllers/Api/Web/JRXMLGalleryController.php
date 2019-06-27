<?php

namespace App\Http\Controllers\Api\Web;

use Illuminate\Http\Request;
use App\TemplateGallery;
use App\Template;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class JRXMLGalleryController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function allJRXML()
    {
        $data = TemplateGallery::all();
        $response["statusCode"] = 200;
        $response["data"] = $data;
        return response()->json($response);
    }

    public function detailJRXML($id)
    {
        $data = TemplateGallery::findOrfail($id);
        $response["statusCode"] = 200;
        $response["data"] = $data;
        return response()->json($response);
    }

    public function copyJRXML(Request $request)
    {
        $filename       = $request->input('filename');
        $realfilename   = $request->input('realfilename');
        $user_id        = Auth::user()->id;
        $pecah          = explode(".",$filename);
        $jenis_file     = count($pecah) - 1;
        $nama_file      = str_random(20) . ".".$pecah[$jenis_file];
        Template::create([
            "filename"      => $filename,
            "realfilename"  => $nama_file,
            "user_id"       => $user_id
        ]);
        $success = \File::copy(config("gallery.dir"). '/' .$realfilename,config("report.dir").'/user_id_' . Auth::user()->id . '/' . $nama_file);
        $response["statusCode"] = 200;
        $response["data"] = $filename;
        return response()->json($response);
    }

    public function searchJRXML(Request $request)
    {
        $pencarian   = $request->input('search');

        $data = TemplateGallery::where('filename', 'like', "%{$pencarian}%")->get();
        $response["statusCode"] = 200;
        $response["data"] = $data;
        return response()->json($response);
    }
}
