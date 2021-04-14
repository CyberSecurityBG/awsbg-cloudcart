<?php
namespace Awsbg\Cloudcart;

use App\Events\Mapping\CategoryCreate;
use Awsbg\Cloudcart\Redirect;

class Category{

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
    public function get_or_create($name, $parent_id = null){

        if(!empty($parent_id)) {
            $get = $this->client->request('GET', 'categories', ['filter[parent_id]' => $parent_id, 'filter[name]' => $name]);
        } else {
            $get = $this->client->request('GET', 'categories', ['filter[name]' => $name]);
        }
        if(isset($get['data'][0])){
            $result = $get['data'][0];
        } else {
            $result =  $this->store($name, '', $parent_id)['data'];
        }
        if($result['id'] > 0) {
            event(new CategoryCreate([
                'website_url' => $this->client->access['url'],
                'platform' => 'cloudcart',
                'category_id' => $result['id'],
                'parent_id' => $parent_id,
                'name' => $name
            ]));
        }
        return $result;
    }
    /**
     * Select a search parameter
     *
     * @return void
     */
    public function search($fiter){
        return $this->client->request('GET', 'categories', [$fiter]);
    }

    public function category($product_id, $include = ''){
        return $this->client->request('GET', 'categories/'.$product_id.'?include='.$include);
    }

    /**
     * Get product list
     *
     * @return void
     */
    public function categories($limit = '50', $page = '1', $include = ''){
        return $this->client->request('GET', 'categories', ['include' => $include, 'page[size]'=> $limit, 'page[number]' => $page]);
    }

    /**
     * Create a new product
     *
     * @return void
     */
    public function store($name, $img_url = '', $parent_id = ''){
        if(!empty($name)) {
            $data['data']['type'] = 'categories';
            $data['data']['attributes']['name'] = $name;
            if(!empty($img_url)){
                $data['data']['attributes']['image_url'] = $img_url;
            }
            if(!empty($parent_id)){
                $data['data']['relationships']['parent']['data']['type'] = 'categories';
                $data['data']['relationships']['parent']['data']['id'] = (string)$parent_id;
            }
            $create_category = $this->client->request('POST', 'categories', $data);
            return $create_category;
        }
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


    /**
     * Add Property to Category
     * $properys is array
     * @return void
     */
    public function add_property($category_id, $propertys){
        foreach($propertys as $create_property){
            if(!empty($create_property)) {
                $InsToProduct[] = array(
                    'type' => 'properties',
                    'id' => strval($create_property),
                );
            }
        }
        $data['data'] = $InsToProduct;
        $this->client->request('POST', 'categories/'.$category_id.'/relationships/properties', $data);
    }

}
