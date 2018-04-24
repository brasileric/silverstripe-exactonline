<?php

namespace Hestec\ExactOnline;

use SilverStripe\Control\Director;

class ExactRequest
{

    public function ConnectApi(){

        $connobject = ExactOnlineConnection::get()->first();

        if (strlen($connobject->AccessToken) > 100 && strlen($connobject->RefreshToken) > 100 && $connobject->TokenExpires){

            $connection = $this->ConnectInit();

            $connection->setAccessToken(unserialize($connobject->AccessToken));
            $connection->setRefreshToken($connobject->RefreshToken);

            try {
                $connection->connect();
            } catch (\Exception $e)
            {
                throw new Exception('Could not connect to Exact: ' . $e->getMessage());
            }

            // get and save new tokens when tokens are expired
            if ($connobject->TokenExpires < time()){

                $connobject->AccessToken = serialize($connection->getAccessToken());
                $connobject->RefreshToken = $connection->getRefreshToken();
                $connobject->TokenExpires = $connection->getTokenExpires();
                $connobject->write();

            }

            return $connection;

        }

        return "No tokens";

    }

    public function ConnectInit(){

        $connconfig = ExactOnlineConnection::config();

        if (strlen($connconfig->ClientId) > 20 && strlen($connconfig->ClientSecret)) {

            $connection = new \Picqer\Financials\Exact\Connection();
            $connection->setRedirectUrl(Director::absoluteBaseURL() . "ExactController/Authorize"); // Same as entered online in the App Center
            $connection->setExactClientId($connconfig->ClientId);
            $connection->setExactClientSecret($connconfig->ClientSecret);

            return $connection;

        }

        return "No yml config";

    }

}