<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client;
use Tiketux\Token\Models\Token;

class TemplateGallery extends Model
{
    //
    protected $table = "template_gallery";
    protected $fillable=["filename","realfilename","user_id","preview"];

    public static function url($nama_file_gambar)
    {
    	$data = $nama_file_gambar;

    	$output = '/tmp/' . $data;
    	$token = new Token;
        $http = new Client();

        $access_token = $token->getToken(
            config("tokenapi.group"),
            config("tokenapi.url"),
            config("tokenapi.grant_type"),
            config("tokenapi.client_id"),
            config("tokenapi.client_secret")
        )->token;

        $respon = $http->post(config("tokenapi.upload_url"), [
            'multipart' => [
                [
                    'name'     => 'image',
                    'contents' => fopen($output, 'r'),
                    'filename' => $output
                ]
            ],
            'headers' => [
                'Authorization' => "Bearer " . $access_token,
                'Accept'        => 'application/json'
            ]
        ]);

        $last_response = json_decode($respon->getBody()->getContents());

        $data = parse_url($last_response->data->url)["path"];

        return $data;
    }
}
