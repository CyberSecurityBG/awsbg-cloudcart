<?php
namespace Awsbg\Cloudcart;

class Image{

    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Create a new product
     *
     * @return void
     */
    public function get(){
        dd('dasds');
    }

    /**
     * Create a new product
     *
     * @return void
     */
    public function store($type, $id, $image_url){
        $data['data']['type'] = 'images';
        $data['data']['attributes']['src'] = $image_url;
        switch ($type){
            case "category": $data['data']['relationships'][$type]['data']['type'] = 'categories'; break;
            case "vendor": $data['data']['relationships'][$type]['data']['type'] = 'vendors'; break;
            case "product": $data['data']['relationships'][$type]['data']['type'] = 'products'; break;
            case "blog": $data['data']['relationships'][$type]['data']['type'] = 'blogs'; break;
            case "post": $data['data']['relationships'][$type]['data']['type'] = 'posts'; break;
        }
        $data['data']['relationships'][$type]['data']['id'] = $id;
        return $this->client->request('POST', 'images', $data);
    }

    /**
     * Delete product
     *
     * @return void
     */
    public function delete($image_id){
        $this->client->request('DELETE', 'images/'.$image_id);
    }

}
