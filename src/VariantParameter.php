<?php
namespace Awsbg\Cloudcart;

use App\Events\Mapping\VariantParameterCreate;

class VariantParameter{

    private $client;

    const ENDPOINT = 'variant-parameters';

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Get or create vendor
     *
     * @return void
     */
    public function get_or_create($name, $type){
        $get = $this->search(['filter[name]' => $name, 'include' => 'options']);
        if($get['data'] != null){
            $id = $get['data']['id'];
        } else {
            $id = $this->store($name, $type)['data']['id'];
        }
        event(new VariantParameterCreate([
            'website_url' => $this->client->access['url'],
            'platform' => 'cloudcart',
            'variant_id' => $id,
            'name' => $name,
            'type' => $type
        ]));
        return $id;
    }
    /**
     * Select a search parameter
     *
     * @return void
     */
    public function search($fiter){
        return $this->client->request('GET', self::ENDPOINT, $fiter);
    }


    public function parameters($limit, $page){
        return $this->client->request('GET', self::ENDPOINT, ['page[size]'=> $limit, 'page[number]' => $page]);
    }

    /**
     * Create new variant parameter
     *
     * @return void
     */
    public function store($name, $type){
        $data['data']['type'] = 'variant-parameters';
        $data['data']['attributes']['name'] = trim($name);
        $data['data']['attributes']['display_type'] = trim($type);
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
