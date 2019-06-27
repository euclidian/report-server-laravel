<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Laravel\Passport\ClientRepository;
use Laravel\Passport\TokenRepository;
use Lcobucci\JWT\Parser;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public static function getCurrentClient($request){
        $clientRepository = new ClientRepository();
        $jwt = (new Parser())->parse($request->bearerToken());
        $client = $clientRepository->find($jwt->getClaim('aud'));
        return $client;
    }

    public static function getCurrentToken($request){
        $tokenRepository = new TokenRepository();
        $jwt = (new Parser())->parse($request->bearerToken());
        $token= $tokenRepository->find($jwt->getClaim('jti'));
        return $token;
    }
}
