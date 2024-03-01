<?php

namespace App\Api;

use Illuminate\Support\Facades\Http;
use \GuzzleHttp\Client as GuzzleHttpClient;

class ClientService {
    private $http;

    public function __construct() {
        $api_host = config('api.host');
        $api_port = config('api.port');

        $this->http = Http::setClient(new GuzzleHttpClient([
            'base_uri' => "http://{$api_host}:{$api_port}",
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]));
    }

    /**
     * Api get method
     *
     * @param  string $path
     * @param  array|string $query
     * @param  array $headers
     * @return Illuminate\Http\Client\PendingRequest
     */
    public function get(string $path, array|string $query = null, array $headers = []) {
        return $this->http->withHeaders($headers)->get($path, $query);
    }

    /**
     * Api post method
     *
     * @param  string $path
     * @param  array $data
     * @param  array $headers
     * @return Illuminate\Http\Client\PendingRequest
     */
    public function post(string $path, array $data = [], array $headers = []) {
        return $this->http->withHeaders($headers)->post($path, $data);
    }

    /**
     * Api patch method
     *
     * @param  string $path
     * @param  array|string $query
     * @param  array $headers
     * @return Illuminate\Http\Client\PendingRequest
     */
    public function patch(string $path, array|string $query = null, array $headers = []) {
        return $this->http->withHeaders($headers)->patch($path, $query);
    }

    /**
     * Api delete method
     *
     * @param  string $path
     * @param  array|string $query
     * @param  array $headers
     * @return Illuminate\Http\Client\PendingRequest
     */
    public function delete(string $path, array|string $query = null, array $headers = []) {
        return $this->http->withHeaders($headers)->delete($path, $query);
    }
}
