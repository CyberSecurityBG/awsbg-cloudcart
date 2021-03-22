<?php
namespace Cybercenter\Cloudcart\src;

use App\Events\Mapping\PropertyOptionCreate;

class PropertyOption{

    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function get_or_create($property_id, $value){
        $search = $this->search(['filter[property_id]' => $property_id, 'filter[value]' => $value]);
        if(isset($search['data']) && $search['data'] != null && count($search) > 0){
            $id = $search['data'][0]['id'];
        } else {
            $id = $this->store($value, $property_id)['data']['id'];
        }
        event(new PropertyOptionCreate([
            'website_url' => $this->client->access['url'],
            'platform' => 'cloudcart',
            'value_id' => $id,
            'property_id' => $property_id,
            'value' => $value
        ]));
        return $id;
    }


    public function search($filter){
        return $this->client->request('GET', 'property-options/', $filter);
    }

    /**
     * Create a new product
     *
     * @return void
     */
    public function get($id){

    }

    public function property_values($limit, $page){
        return $this->client->request('GET', 'property-options', ['page[size]'=> $limit, 'page[number]' => $page]);
    }

    /**
     * Create a new product
     *
     * @return void
     */
    public function store($name, $property_id){
        $data['data']['type'] = 'property-options';
        $data['data']['attributes']['value'] = $name;
        $data['data']['relationships']['property']['data']['type'] = 'properties';
        $data['data']['relationships']['property']['data']['id'] = (string)$property_id;
        return $this->client->request('POST', 'property-options', $data);
    }

    /**
     * Delete product
     *
     * @return void
     */
    public function delete($product_id){

    }
}
