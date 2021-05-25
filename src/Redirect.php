<?php
namespace Awsbg\Cloudcart;
use Awsbg\Cloudcart\Client;
class Redirect{

    private $client;

    public function __construct($client)
    {
        $this->client = $client;
    }

    /**
     * Create a new product
     *
     * @return void
     */
    public function get($filter, $value){
        return $this->client->request('GET', 'redirects', ['filter['.$filter.']'=> $value]);
    }

    /**
     * Create a new product
     *
     * @return void
     */
    public function store($type, $id, $oldurl){
        $data['data']['type'] = 'redirects';
        $data['data']['attributes']['redirect_type'] = $type;
        $data['data']['attributes']['old_url'] = $oldurl;
        switch ($type){
            case "category": $data['data']['relationships']['item']['data']['type'] = 'categories'; break;
            case "vendor": $data['data']['relationships']['item']['data']['type'] = 'vendors'; break;
            case "product": $data['data']['relationships']['item']['data']['type'] = 'products'; break;
            case "blog": $data['data']['relationships']['item']['data']['type'] = 'blogs'; break;
            case "post": $data['data']['relationships']['item']['data']['type'] = 'posts'; break;
        }
        $data['data']['relationships']['item']['data']['id'] = (string)$id;
        return $this->client->request('POST', 'redirects', $data);
    }

    /**
     * Delete product
     *
     * @return void
     */
    public function delete($redirect_id){
        $this->client->request('DELETE', 'redirects/'.$redirect_id);
    }
}
