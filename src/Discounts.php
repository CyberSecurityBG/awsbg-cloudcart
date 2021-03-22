<?php
namespace Cybercenter\Cloudcart\src;

class Discounts{

    private $client;

    const ENDPOINT = 'discounts';

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Select a search parameter
     *
     * @return void
     */
    public function search($type, $value){
        return $this->client->request('GET', self::ENDPOINT, ['filter['.$type.']'=> $value]);
    }


    public function discount($limit, $page){
        return $this->client->request('GET', self::ENDPOINT, ['page[size]'=> $limit, 'page[number]' => $page]);
    }

    /**
     * Create new variant parameter
     *
     * @return void
     */
    public function store($product_id, $variant_id, $discount_id, $price){

    }

    /**
     * Update product
     *
     * @return void
     */
    public function update($disount_id){

    }

    /**
     * Delete product
     *
     * @return void
     */
    public function delete($disount_id){
        return $this->client->request('DELETE', self::ENDPOINT.'/'.$disount_id);
    }

}
