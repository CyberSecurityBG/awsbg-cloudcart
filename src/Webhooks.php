<?php
namespace Awsbg\Cloudcart;

class Webhooks
{

    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->redirect = new Redirect($client);
    }

    public function create($data){
        $request = [
            'data' => [
                'type' => 'webhooks',
                'attributes' => [
                    'url' => $data['url'],
                    'event' => $data['event'],
                    'request_headers' => [
                        'platform' => 'smarty-cloud',
                        'website_id' => $data['website']
                    ]
                ]
            ]
        ];
        return $this->client->request('POST', 'webhooks', $request);
    }
}
