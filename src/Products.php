<?php
namespace Cybercenter\Cloudcart\src;

class Products{

    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->redirect = new Redirect($client);
    }
    /**
     * Select a search parameter
     *
     * @return void
     */
    public function search($type , $value){
        return $this->client->request('GET', 'products', ['filter['.$type.']'=> $value]);
    }

    public function product($product_id, $include = ''){
        return $this->client->request('GET', 'products/'.$product_id, ['include' => $include]);
    }

    /**
     * Get product list
     *
     * @return void
     */
    public function products($limit = '50', $page = '1'){
        return $this->client->request('GET', 'products', ['page[size]'=> $limit, 'page[number]' => $page]);
    }

    /**
     * Create a new product
     *
     * @return void
     */
    public function store($data){
        $product = $this->client->request('POST', 'products', $data);
        return $product;
    }

    /**
     * Update product
     *
     * @return void
     */
    public function update($product_id, $data){
        $product = $this->client->request('PATCH', 'products/'.$product_id, $data);
        return $product;
    }

    /**
     * Delete product
     *
     * @return void
     */
    public function delete($product_id){
        return $this->client->request('DELETE', 'products/'.$product_id);
    }

    /**
     * Add property value to product
     * $options is array
     * @return void
     */

    public function add_property_value($product_id, $options){
        foreach($options as $create_property){
            if(!empty($create_property)) {
                $InsToProduct[] = array(
                    'type' => 'property-options',
                    'id' => strval($create_property),
                );
            }
        }
        $data['data'] = $InsToProduct;
        $this->client->request('POST', 'products/'.$product_id.'/relationships/property-options', $data);
    }

}
