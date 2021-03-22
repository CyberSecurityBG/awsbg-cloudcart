<?php
namespace Cybercenter\Cloudcart\src;

class DiscountToProduct{

    private $client;

    const ENDPOINT = 'product-to-discount';

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

    /**
     * Create new variant parameter
     *
     * @return void
     */
    public function store($product_id, $variant_id, $discount_id, $price){
        $data['data']['type'] = 'product-to-discount';
        $data['data']['attributes']['price'] = (int)$price;
        $data['data']['relationships']['discount']['data']['type'] = 'discounts';
        $data['data']['relationships']['discount']['data']['id'] = (string)$discount_id;
        $data['data']['relationships']['product']['data']['type'] = 'products';
        $data['data']['relationships']['product']['data']['id'] = (string)$product_id;
        $data['data']['relationships']['variant']['data']['type'] = 'variants';
        $data['data']['relationships']['variant']['data']['id'] = (string)$variant_id;
        return $this->client->request('POST',self::ENDPOINT, $data);
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
