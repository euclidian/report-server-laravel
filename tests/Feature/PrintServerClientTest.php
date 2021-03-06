<?php

namespace Tests\Feature;

use Tests\ClientTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use App\Template;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\ClientRepository;

class PrintServerClientTest extends ClientTestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    protected $baseUrl = "127.0.0.1";

    public function testClientPrint()
    {
        $data = factory(Template::class)->create([
            "user_id" => $this->user->id
        ]);

        mkdir(config("report.dir") . "/user_id_" . $data->user_id);
        copy(base_path("tests/Test.jrxml"), config("report.dir") . "/user_id_" . $data->user_id . "/" . $data->realfilename);

        $response = $this->post('/api/client/print', [
            "template" => $data->id,
            "type" => "pdf",
            "json" => [
                "data" => [
                    "tipe" => "Executive",
                    "tanggal" => "20 Oktober 2018",
                    "hari" => "Senin",
                    "no_bis" => "B-7199-UGA",
                    "kursi" => [
                        [
                            "no_kursi" => "1",
                            "tipe" => "a",
                            "nama" => "Street 1",
                            "tujuan" => "Fairfax",
                            "catatan" => "+1 (415) 111-1111"
                        ]
                    ]
                ]
            ]
        ]);

        \File::deleteDirectory(config("report.dir") . "/user_id_" . $data->user_id);
        $response
            ->assertStatus(200)
            ->assertJson([
                "statusCode" => 200,
                "data" => [
                    "template_id" => $data->id,
                    "user_id"  => $data->user_id
                ]
            ]);
    }

    public function testClientApiSukses()
    {
        $data = factory(Template::class)->create([
            "user_id" => $this->user->id
        ]);

        $CR = new ClientRepository;
        $CR->create($this->user->id, $this->user->name, "localhost", false, false);
        $Credentials = DB::table("oauth_clients")
            ->where("password_client", 0)
            ->where("personal_access_client", 0)
            ->where("user_id", $this->user->id)
            ->first();

        mkdir(config("report.dir") . "/user_id_" . $data->user_id);
        copy(base_path("tests/Test.jrxml"), config("report.dir") . "/user_id_" . $data->user_id . "/" . $data->realfilename);

        $response = $this->post('/api/client/print_client', [
            "client_id" => $Credentials->id,
            "client_secret" => $Credentials->secret,
            "template" => $data->id,
            "type" => "pdf",
            "json" => [
                "data" => [
                    "tipe" => "Executive",
                    "tanggal" => "20 Oktober 2018",
                    "hari" => "Senin",
                    "no_bis" => "B-7199-UGA",
                    "kursi" => [
                        [
                            "no_kursi" => "1",
                            "tipe" => "a",
                            "nama" => "Street 1",
                            "tujuan" => "Fairfax",
                            "catatan" => "+1 (415) 111-1111"
                        ]
                    ]
                ]
            ]
        ]);

        \File::deleteDirectory(config("report.dir") . "/user_id_" . $data->user_id);
        $response
            ->assertStatus(200)
            ->assertJson([
                "statusCode" => 200,
                "data" => [
                    "template_id" => $data->id,
                    "user_id"  => $data->user_id
                ]
            ]);
    }

    public function testClientApiGagalClientCredentialsGakAda()
    {
        $data = factory(Template::class)->create([
            "user_id" => $this->user->id
        ]);

        $CR = new ClientRepository;
        $CR->create($this->user->id, $this->user->name, "localhost", false, false);
        $Credentials = DB::table("oauth_clients")
            ->where("password_client", 0)
            ->where("personal_access_client", 0)
            ->where("user_id", $this->user->id)
            ->first();

        mkdir(config("report.dir") . "/user_id_" . $data->user_id);
        copy(base_path("tests/Test.jrxml"), config("report.dir") . "/user_id_" . $data->user_id . "/" . $data->realfilename);

        $response = $this->post('/api/client/print_client', [
            "client_id" => $Credentials->id,
            "client_secret" => $Credentials->secret . str_random(5),
            "template" => $data->id,
            "type" => "pdf",
            "json" => [
                "data" => [
                    "tipe" => "Executive",
                    "tanggal" => "20 Oktober 2018",
                    "hari" => "Senin",
                    "no_bis" => "B-7199-UGA",
                    "kursi" => [
                        [
                            "no_kursi" => "1",
                            "tipe" => "a",
                            "nama" => "Street 1",
                            "tujuan" => "Fairfax",
                            "catatan" => "+1 (415) 111-1111"
                        ]
                    ]
                ]
            ]
        ]);
        $response
            ->assertStatus(404);
    }

    public function testClientPrintTransaction()
    {
        $data = factory(\App\Printed::class)->create([
            "user_id" => $this->user->id
        ]);

        $response = $this->post('/api/client/print_transaction', [
            "printed_id" => $data->id,
            "status" => rand(0, 1),
            "print_time" => date("Y-m-d h:i:s")
        ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                "statusCode" => 200,
                "data" => [
                    "user_id" => $data->user_id,
                    "printed_id" => $data->id
                ]
            ]);
    }

    public function testClientAllPrintTransaction()
    {
        factory(\App\PrintTransaction::class)->create([
            "user_id" => $this->user->id
        ]);
        $response = $this->get('/api/client/allPrintTransaction');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                "statusCode",
                "data" => [
                    [
                        "id",
                        "printed_id",
                        "status",
                        "print_time",
                        "created_at",
                        "updated_at",
                        "url",
                        "json",
                        "user_id"
                    ]
                ]
            ]);
    }

    public function testClientAllUnprinted()
    {
        factory(\App\Printed::class)->create([
            "user_id" => $this->user->id
        ]);
        $response = $this->get('/api/client/allUnPrinted');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                "statusCode",
                "data" => [
                    [
                        "id",
                        "image_id",
                        "template_id",
                        "user_id",
                        "created_at",
                        "updated_at",
                        "json",
                        "url"
                    ]
                ]
            ]);
    }

    public function testClientAllJRXML()
    {
        factory(\App\Template::class)->create([
            "user_id" => $this->user->id
        ]);
        $response = $this->get('/api/client/allJRXML');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                "statusCode",
                "data" => [
                    [
                        "id",
                        "filename",
                        "realfilename",
                        "user_id",
                        "created_at",
                        "updated_at"
                    ]
                ]
            ]);
    }
}
