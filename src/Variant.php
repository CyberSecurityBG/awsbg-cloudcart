<?php
namespace Cybercenter\Cloudcart\src;

class Variant{

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
    public function search($fiter){
        return $this->client->request('GET', 'variants', [$fiter]);
    }

    public function variants($limit, $page){
        return $this->client->request('GET', 'variants/', ['page[size]'=> $limit, 'page[number]' => $page]);
    }


    public function store($data){
        return $this->client->request('POST','variants', $data);
    }

    /**
     * Update product
     *
     * @return void
     */
    public function update($variant_id, $data){
        return $this->client->request('PATCH', 'variants/'.$variant_id, $data);

    }

    /**
     * Delete product
     *
     * @return void
     */
    public function delete($variant_id){
        return $this->client->request('DELETE', 'variants/'.$variant_id);
    }

}
