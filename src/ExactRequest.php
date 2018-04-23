<?php

namespace Hestec\ExactOnline;

class ExactRequest
{

    public function Connect()
    {
        /*$apikey = Config::inst()->get('GetResponse', 'apikey');

        $service = new RestfulService("https://api.getresponse.com", 0);
        $service->httpHeader("Content-Type: application/json");
        $service->httpHeader("X-Auth-Token: api-key " . $apikey);

        return $service;*/


        $connconfig = ExactOnlineConnection::config();
        $connobject = ExactOnlineConnection::get()->first();

        $connection = new \Picqer\Financials\Exact\Connection();
        $connection->setExactClientId($connconfig->ClientId);
        $connection->setExactClientSecret($connconfig->ClientSecret);

        $connection->setAccessToken(unserialize($connobject->AccessToken));
        $connection->setRefreshToken($connobject->RefreshToken);

        try {
            $connection->connect();
        } catch (\Exception $e)
        {
            throw new Exception('Could not connect to Exact: ' . $e->getMessage());
        }

        //return "CONNECTED";

        return $connection;

    }

}