<?php

namespace Tests\Feature;

use Tests\PassportTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class PrintServerWebTest extends PassportTestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public $baseUrl   = 'http://127.0.0.1';

    public function testAllUser()
    {
        $response = $this->get('/api/allUser');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                "statusCode",
                "data" => [
                    [
                        "id",
                        "name",
                        "email",
                        "admin",
                        "created_at"
                    ]
                ]
            ]);
    }

    public function testToAdmin()
    {
        $user = factory(\App\User::class)->create([
            "admin" => 0
        ]);
        $response = $this->get('/api/toAdmin/' . $user->id);

        $response
            ->assertStatus(200)
            ->assertJson([
                "statusCode" => 200,
                "data" => [
                    "id" => $user->id,
                    "name" => $user->name,
                    "email" => $user->email,
                    "admin" => 1
                ]
            ]);
    }

    public function testToAdminTakBerhak()
    {
        $this->headers['Authorization'] = 'Bearer ' . $this->tokenBukanAdmin;
        $user = factory(\App\User::class)->create([
            "admin" => 0
        ]);
        $response = $this->get('/api/toAdmin/' . $user->id);

        $response
            ->assertStatus(403);
    }

    public function testToAdminUserTakAda()
    {
        $user = factory(\App\User::class)->create([
            "admin" => 0
        ]);
        $response = $this->get('/api/toAdmin/' . rand(1000, 2000));

        $response
            ->assertStatus(404);
    }

    public function testGenerateToken()
    {
        $user = factory(\App\User::class)->create([
            "admin" => 0
        ]);
        $response = $this->get('/api/generateToken/' . $user->id);

        $response
            ->assertStatus(200);
    }

    public function testGenerateTokenTakBerhak()
    {
        $this->headers['Authorization'] = 'Bearer ' . $this->tokenBukanAdmin;
        $user = factory(\App\User::class)->create([
            "admin" => 0
        ]);
        $response = $this->get('/api/generateToken/' . $user->id);

        $response
            ->assertStatus(403);
    }

    public function testGenerateTokenUserTakAda()
    {
        $user = factory(\App\User::class)->create([
            "admin" => 0
        ]);
        $response = $this->get('/api/generateToken/' . rand(1000, 2000));

        $response
            ->assertStatus(404);
    }

    public function testAllPrinted()
    {
        factory(\App\Printed::class)->create([
            "user_id" => $this->user->id
        ]);

        $response = $this->get('/api/allPrinted');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                "statusCode",
                "data" => [
                    "list" => [
                        "data" => [[
                            "id",
                            "image_id",
                            "template_id",
                            "user_id",
                            "created_at",
                            "updated_at",
                            "json",
                            "url",
                            "template"
                        ]]
                    ]
                ]
            ]);
    }

    public function testAllPrintTransaction()
    {
        factory(\App\PrintTransaction::class)->create([
            "user_id" => $this->user->id
        ]);

        $response = $this->get('/api/allPrintTransaction');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                "statusCode",
                "data" => [
                    "data" => [[
                        "id",
                        "printed_id",
                        "status",
                        "print_time",
                        "created_at",
                        "updated_at",
                        "url",
                        "json",
                        "user_id"
                    ]]
                ]
            ]);
    }

    public function testAddUser()
    {
        $name = str_random(20);
        $email = str_random(20);
        $password =  str_random(20);
        $response = $this->post('/api/addUser/', [
            "name" => $name,
            "email" => $email,
            "password" => $password
        ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                "statusCode" => 200,
                "data" => [
                    "name" => $name,
                    "email" => $email
                ]
            ]);
        return $response;
    }

    public function testAddUserTakBerhak()
    {
        $this->headers['Authorization'] = 'Bearer ' . $this->tokenBukanAdmin;
        $user = factory(\App\User::class)->create([
            "admin" => 0
        ]);
        $name = str_random(20);
        $email = str_random(20);
        $password =  str_random(20);
        $response = $this->post('/api/addUser/', [
            "name" => $name,
            "email" => $email,
            "password" => $password
        ]);

        $response
            ->assertStatus(403);
    }

    public function testAllJRXML()
    {
        factory(\App\Template::class)->create([
            "user_id" => $this->user->id
        ]);

        $response = $this->get('/api/allJRXML');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                "statusCode",
                "data" => [[
                    "id",
                    "filename",
                    "realfilename",
                    "user_id",
                    "created_at",
                    "updated_at"
                ]]
            ]);
    }

    public function testAddJRXML()
    {
        Storage::fake('templates');

        $response = $this->post('/api/addJRXML', [
            'template' => UploadedFile::fake()->create("template.jrxml")
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                "statusCode",
                "data"
            ]);
    }

    public function testDeleteJRXML()
    {
        $data = factory(\App\Template::class)->create([
            "user_id" => $this->user->id
        ]);

        $response = $this->get('/api/deleteJRXML/' . $data->id);

        $response
            ->assertStatus(200)
            ->assertJson([
                "statusCode" => 200,
                "data" => [
                    "id" => $data->id,
                    "filename" => $data->filename,
                    "realfilename" => $data->realfilename,
                    "user_id" => $data->user_id,
                    "created_at" => $data->created_at,
                    "updated_at" => $data->updated_at
                ]
            ]);
    }

    public function testGetUser()
    {
        $response = $this->get('/api/getUser');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                "id",
                "name",
                "email",
                "created_at",
                "updated_at",
                "admin"
            ]);
    }

    public function testUpdatePassword()
    {
        $response = $this->post('/api/updatePassword', [
            "password" => "123",
            "new_password" => str_random(10)
        ]);
        
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                "statusCode",
                "data" => [
                    "id",
                    "name",
                    "email"
                ]
            ]);
    }

    public function testUpdatePasswordYangLamaSalah()
    {
        $response = $this->post('/api/updatePassword', [
            "password" =>  str_random(10),
            "new_password" => str_random(10)
        ]);
        
        $response
            ->assertStatus(400);
    }
}
