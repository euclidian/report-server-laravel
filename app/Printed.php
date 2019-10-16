<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Template;
use GuzzleHttp\Client;
use Tiketux\Token\Models\Token;
use DateTime;
use JasperPHP\JasperPHP as JasperPHP;

class Printed extends Model
{
    protected $table = "prints";
    protected $fillable = ["image_id", "template_id", "user_id", "json"];
    public static function print($encode, $type, $templateID, $userId)
    {
        if ($type != "pdf" && $type != "xls") {
            abort(422, "Tipe tidak sesuai.");
        }
        $templateName             = Template::findOrFail($templateID)->realfilename;
        $date = new DateTime();
        $json = json_encode($encode);
        $filename = base_path("storage/json/data-" . $date->getTimestamp() . ".json");
        $myfile = fopen($filename, "w") or die("Unable to open file!");
        fwrite($myfile, $json);
        fclose($myfile);
        $randName = str_random(15);
        $output = base_path('storage/json/' . $randName); //indicate the name of the output PDF        

        $jasper = new JasperPHP(config("report.dir"));
        $error = $jasper->process(
            config("report.dir") . '/user_id_' . $userId . "/" . $templateName,
            $output,
            array($type),
            array(),
            array("driver" => "json", "json_query" => "data", "data_file" =>  $filename)
        )->execute();
        if (count($error) > 0) {
            abort(404, $error[0]);
        }

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
                    'contents' => fopen($output . "." . $type, 'r'),
                    'filename' => $output . "." . $type
                ]
            ],
            'headers' => [
                'Authorization' => "Bearer " . $access_token,
                'Accept'        => 'application/json'
            ]
        ]);

        $last_response = json_decode($respon->getBody()->getContents());

        $data = new Printed;
        $data->image_id = $last_response->data->id;
        $data->template_id = $templateID;
        $data->user_id = $userId;
        $data->json = json_encode($encode);
        $data->url = parse_url($last_response->data->url)["path"];
        $data->save();
        return $data;
    }

    public static function allPrinted($userId)
    {
        return Printed::where("user_id", $userId)->with("template")->get();
    }

    public function template()
    {
        return $this->belongsTo("App\Template", "template_id", "id");
    }

    public function transaction()
    {
        return $this->hasOne("App\PrintTransaction", "printed_id", "id");
    }
}
