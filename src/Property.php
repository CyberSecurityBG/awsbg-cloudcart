<?php
namespace Awsbg\Cloudcart;

use App\Events\Mapping\PropertyCreate;

class Property{

    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function get_or_create($name, $include = ''){
        $search = $this->search(['filter[name]' => $name, 'include' => $include]);
        if(isset($search['data']) && $search['data'] != null){
            $result = $search;
        } else {
            $result = $this->store($name);
        }
        event(new PropertyCreate([
            'website_url' => $this->client->access['url'],
            'platform' => 'cloudcart',
            'property_id' => $result['data']['id'],
            'name' => $name
        ]));
        return $result;
    }


    public function search($filter){
        return $this->client->request('GET', 'properties', $filter);
    }

    public function properties($limit, $page){
        return $this->client->request('GET', 'properties', ['page[size]'=> $limit, 'page[number]' => $page]);
    }

    /**
     * Create a new product
     *
     * @return void
     */
     public function get($id){

     }

    /**
     * Create a new product
     *
     * @return void
     */
    public function store($name){
        $data['data']['type'] = 'properties';
        $data['data']['attributes']['name'] = $name;
        $data['data']['attributes']['display_type'] = 'checkbox';
        return $this->client->request('POST', 'properties', $data);
    }

    /**
     * Delete product
     *
     * @return void
     */
    public function delete($product_id){

    }

}
