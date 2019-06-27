<?php

namespace App\Http\Controllers\Api\Web\Admin;

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
        $this->middleware('admin');
    }

    public function addJRXML(Request $r)
    {
        $r->validate([
            'template' => 'required|mimetypes:application/octet-stream,text/xml'
        ]);
        $file       = $r->file('template');
        $preview    = $r->file('preview');
        $random     = str_random(20);
        $nama_file  = $random . "." . $file->getClientOriginalExtension();
        $nama_file_gambar  = $random . "." . $preview->getClientOriginalExtension();
        $file->move(config("gallery.dir"), $nama_file);
        $preview->move('/tmp', $nama_file_gambar);

        $url_gambar = new TemplateGallery;
        $upload_gambar = $url_gambar->url($nama_file_gambar);

        $data = config("tokenapi.download_url");

        TemplateGallery::create([
            "filename"      =>  $file->getClientOriginalName(),
            "realfilename"  => $nama_file,
            "preview"       => $data.$upload_gambar
        ]);
        $response["statusCode"] = 200;
        $response["data"] = $nama_file;
        return response()->json($response);
    }

    public function deleteJRXML($id)
    {
        $data = TemplateGallery::findOrfail($id);
        $data->delete();
        $response["statusCode"] = 200;
        $response["data"] = $data;
        return response()->json($response);
    }

}
