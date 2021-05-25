<?php

namespace Awsbg\Cloudcart;
set_time_limit(0);

use App\Http\Controllers\Errors\ErrorApiController;
use Carbon\Carbon;
use GuzzleHttp\Client as GuzzleClient;
use Exception;
use Illuminate\Support\Facades\Cache;


class Client{

    const LIMIT_REQUEST = 300;

    public $access;
    protected $client;
    protected $product;
    protected $vendors;

    public function __construct($url, $key)
    {
        $this->access = [
            'url' => $url,
            'key' => $key
        ];
        $this->client = new GuzzleClient(['base_uri' => $url.'api/v2/']);
    }

    public function request($method, $endpoint, $data = '', $try = 1){
        if(Cache::get('cloudcart_limit') != null){
            $duration = convert_microtime(Cache::get('cloudcart_limit')-strtotime(Carbon::now('GMT')), 'seconds');
            echo 'The limit of requests to CloudCart has been reached, waiting time '.$duration.' seconds'.PHP_EOL;
            sleep($duration);
        }
        try {
            if($method == 'GET'){
                $response = $this->client->request($method, $endpoint,  [
                    'query' => $data ,
                 //   'debug' => true,
                    'headers' =>  [
                        'Content-Type' => 'application/vnd.api+json',
                        'Accept' => 'application/vnd.api+json',
                        'X-CloudCart-ApiKey' => $this->access['key']
                    ]
                ]);
            } elseif($method == 'POST' || $method == 'PUT' || $method == 'PATCH'){
                $response = $this->client->request($method, $endpoint,  [
                    'json' => $data,
                  //  'debug' => true,
                    'headers' =>  [
                        'Content-Type' => 'application/vnd.api+json',
                        'Accept' => 'application/vnd.api+json',
                        'X-CloudCart-ApiKey' => $this->access['key']
                    ]
                ]);
            } else {
                $response = $this->client->request($method, $endpoint,  [
                  //  'debug' => true,
                    'headers' =>  [
                        'Content-Type' => 'application/vnd.api+json',
                        'Accept' => 'application/vnd.api+json',
                        'X-CloudCart-ApiKey' => $this->access['key']
                    ]
                ]);
            }
            if(isset($response->getHeader('X-RateLimit-Remaining')[0])) {
                if ($response->getHeader('X-RateLimit-Remaining')[0] < 5) {
                    $duration = convert_microtime(strtotime(date_format(date_create($response->getHeader('X-RateLimit-Reset')[0]), 'd.m.Y H:i:s')) - strtotime(Carbon::now('GMT')), 'seconds');
                    Cache::add('cloudcart_limit', strtotime(date_format(date_create($response->getHeader('X-RateLimit-Reset')[0]), 'd.m.Y H:i:s')), $duration);
                    echo 'The limit of requests to CloudCart has been reached, waiting time ' . $duration . ' seconds' . PHP_EOL;
                }
            }
            return json_decode($response->getBody()->getContents(), true);
        } catch (Exception $e) {
            if($e->getCode() == 500 && $try < 4){
                sleep(3);
                $this->request($method,  $endpoint, $data, $try+1);
            }
            // За редирект и изображения ерорите също не ги записваме
            if ($endpoint != 'redirects' && $endpoint != 'webhooks') {
                ErrorApiController::store($this->access['url'], $this->access['key'], $method, $endpoint, $e->getResponse()->getBody(true), $data, $e->getCode());
                echo 'An error has occurred, please check the error logs!' . PHP_EOL;
            }
            return false;
        }
    }
}
