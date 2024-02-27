<?php

namespace App\Api;

use Illuminate\Support\Facades\Http;
use \GuzzleHttp\Client as GuzzleHttpClient;

class ClientService {
    private $http;

    public function __construct() {
        $this->http = Http::setClient(new GuzzleHttpClient([
            'base_uri' => 'http://api-server:8000',
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]));
    }

    public function get(string $path, array $headers = []) {
        return $this->http->withHeaders($headers)->get($path);
    }

    public function post(string $path, array $data = [], array $headers = []) {
        return $this->http->withHeaders($headers)->post($path, $data);
    }
}
