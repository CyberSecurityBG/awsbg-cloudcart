<?php
namespace Cybercenter\Cloudcart\src;

use App\Events\Mapping\VendorCreate;

class Vendors{

    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->redirect = new Redirect($client);
    }

    /**
     * Get or create vendor
     *
     * @return void
     */
    public function get_or_create($name, $image = ''){
         $get = $this->search('name', $name);
         if(isset($get['data'][0])){
             event(new VendorCreate([
                 'website_url' => $this->client->access['url'],
                 'platform' => 'cloudcart',
                 'vendor_id' => $get['data'][0]['id'],
                 'name' => $name
             ]));
             return $get['data'][0];
         } else {
             $create = $this->store($name, $image)['data'];
             event(new VendorCreate([
                 'website_url' => $this->client->access['url'],
                 'platform' => 'cloudcart',
                 'vendor_id' => $create['id'],
                 'name' => $name
             ]));
             return $create;
         }
    }
    /**
     * Select a search parameter
     *
     * @return void
     */
    public function search($type , $value){
        return $this->client->request('GET', 'vendors', ['filter['.$type.']' => $value]);
    }

    public function vendor($product_id, $include = ''){
        return $this->client->request('GET', 'vendors/'.$product_id.'?include='.$include);
    }

    /**
     * Get product list
     *
     * @return void
     */
    public function vendors($limit = '50', $page = '1'){
        return $this->client->request('GET', 'vendors', ['page[size]'=> $limit, 'page[number]' => $page]);
    }

    /**
     * Create a new product
     *
     * @return void
     */
    public function store($name, $image = ''){
        if(strlen($name) < 3){
            $name = $name.' ';
        }
        $data['data']['type'] = 'vendors';
        $data['data']['attributes']['name'] = $name;
        if(!empty($image)) {
            $data['data']['attributes']['image_url'] = $image;
        }
        $create_vendor = $this->client->request('POST', 'vendors', $data);
        return $create_vendor;
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
