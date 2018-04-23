<?php

use SilverStripe\Control\Controller;
use SilverStripe\Security\Member;
use SilverStripe\Control\Director;
use Hestec\ExactOnline\ExactOnlineConnection;
use SilverStripe\Security\Permission;

class ExactController extends Controller {

    private static $allowed_actions = array (
        'Authorize',
        'Authorize2',
        'Authorize3',
        'Connect',
        'Disconnect'
    );

    public function isCmsMember(){

        if (Permission::check("CMS_ACCESS_CMSMain")){
            return true;
        }

        return false;

    }

    public function Authorize() {

        if (strlen($_GET['code']) > 100 && Member::currentUserID() && $this->isCmsMember()){

            $connconfig = ExactOnlineConnection::config();
            $connobject = ExactOnlineConnection::get()->first();

            $connection = new \Picqer\Financials\Exact\Connection();
            $connection->setRedirectUrl(Director::absoluteBaseURL() . "ExactController/Authorize");
            $connection->setExactClientId($connconfig->ClientId);
            $connection->setExactClientSecret($connconfig->ClientSecret);

            $connection->setAuthorizationCode($_GET['code']);

            try {
                $connection->connect();
            } catch (\Exception $e)
            {
                throw new Exception('Could not connect to Exact: ' . $e->getMessage());
            }

            $connobject->AccessToken = serialize($connection->getAccessToken());
            $connobject->RefreshToken = $connection->getRefreshToken();
            $connobject->TokenExpires = $connection->getTokenExpires();
            $connobject->write();

            $this->redirect(Director::absoluteBaseURL() . "admin/exactonline/Hestec-ExactOnline-ExactOnlineConnection");

        }

        return '<p>There was an error (1001), <a href="'.Director::absoluteBaseURL().'admin/exactonline/Hestec-ExactOnline-ExactOnlineConnection">CLICK HERE</a> to go back and try again</p>';
        //$this->getRequest()->getSession()->set('ConnObjectID', 2);

        //return $this->getRequest()->getSession()->get('ConnObjectID');

    }

    public function Disconnect() {

        if (Member::currentUserID() && $this->isCmsMember()){

            $connconfig = ExactOnlineConnection::get()->first();
            $connconfig->AccessToken = '';
            $connconfig->RefreshToken = '';
            $connconfig->TokenExpires = '';
            $connconfig->write();

            $this->redirect(Director::absoluteBaseURL()."admin/exactonline/Hestec-ExactOnline-ExactOnlineConnection");

        }

    }

    public function Connect() {

        $connconfig = ExactOnlineConnection::config();

        if (strlen($connconfig->ClientId) > 20 && strlen($connconfig->ClientSecret && Member::currentUserID() && $this->isCmsMember())) {

            $connection = new \Picqer\Financials\Exact\Connection();
            $connection->setRedirectUrl(Director::absoluteBaseURL() . "ExactController/Authorize"); // Same as entered online in the App Center
            $connection->setExactClientId($connconfig->ClientId);
            $connection->setExactClientSecret($connconfig->ClientSecret);
            $connection->redirectForAuthorization();

        }else{

            return '<p>There was an error (1003), <a href="'.Director::absoluteBaseURL().'admin/exactonline/Hestec-ExactOnline-ExactOnlineConnection">CLICK HERE</a> to go back and try again</p>';

        }

    }

    public function Authorize2() {

        $output = ExactOnlineConnection::get()->first();

        if ($output->TokenExpires > time()){
            return "token is nog niet verlopen";
        }

        return "token is verlopen";

    }

    public function Authorize3() {

        $connconfig = ExactOnlineConnection::config();
        $connobject = ExactOnlineConnection::get()->first();

        $connection = new \Picqer\Financials\Exact\Connection();
        //$connection->setRedirectUrl(Director::absoluteBaseURL() . "ExactController/Authorize");
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

        return "CONNECTED";

    }

}
