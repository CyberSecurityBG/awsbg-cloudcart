<?php
namespace Cybercenter\Cloudcart\src;

use App\Events\Mapping\VariantParameterOptionCreate;

class VariantOption{

    private $client;

    const ENDPOINT = 'variant-options';

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function get_or_create($parameter_id, $name){
        $get = $this->search($parameter_id, $name);
        if($get['data'] != null){
            $id = $get['data']['id'];
        } else {
            $id = $this->store($parameter_id, $name)['data']['id'];
        }
        event(new VariantParameterOptionCreate([
            'website_url' => $this->client->access['url'],
            'platform' => 'cloudcart',
            'value_id' => $id,
            'variant_id' => $parameter_id,
            'name' => $name,
        ]));
        return $id;
    }

    /**
     * Select a search parameter
     *
     * @return void
     */
    public function search($parameter_id, $name){
        return $this->client->request('GET', self::ENDPOINT,  ['filter[parameter_id]'=> $parameter_id, 'filter[name]' => $name]);
    }

    public function options($limit, $page){
        return $this->client->request('GET', self::ENDPOINT, ['page[size]'=> $limit, 'page[number]' => $page]);
    }

    /**
     * Create new variant parameter
     *
     * @return void
     */
    public function store($parameter_id, $name){
        $data['data']['type'] = 'variant-options';
        $data['data']['attributes']['name'] = trim($name);
        $data['data']['relationships']['parameter']['data']['type'] = 'variant-parameters';
        $data['data']['relationships']['parameter']['data']['id'] = (string)$parameter_id;
        return $this->client->request('POST',self::ENDPOINT, $data);
    }

    /**
     * Update product
     *
     * @return void
     */
    public function update($product_id){

    }

    /**
     * Delete product
     *
     * @return void
     */
    public function delete($product_id){

    }

}
