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

        $connconfig = ExactOnlineConnection::config();

        if (strlen($_GET['code']) > 100 && is_numeric($this->getRequest()->param('ID')) && Member::currentUserID() && $this->isCmsMember()){

            $connconfig = ExactOnlineConnection::get()->byID($this->getRequest()->param('ID'));
            $connconfig->OauthCode = $_GET['code'];
            $connconfig->write();

            $this->redirect(Director::absoluteBaseURL() . "ExactController/Authorize2/".$this->getRequest()->param('ID'));
        }

        return '<p>There was an error, <a href="'.Director::absoluteBaseURL().'admin/exactonline/Hestec-ExactOnline-ExactOnlineConnection">CLICK HERE</a> to go back and try again</p>';
        //$this->getRequest()->getSession()->set('ConnObjectID', 2);

        //return $this->getRequest()->getSession()->get('ConnObjectID');

    }

    public function Authorize2() {

        $connconfig = ExactOnlineConnection::config();

        $connobject = ExactOnlineConnection::get()->byID($this->getRequest()->param('ID'));

        //if (strlen($_GET['code']) > 100 && is_numeric($this->getRequest()->param('ID')) && Member::currentUserID() && $this->isCmsMember()){

            $connection = new \Picqer\Financials\Exact\Connection();
            $connection->setRedirectUrl(Director::absoluteBaseURL() . "ExactController/Authorize/".$this->getRequest()->param('ID'));
            $connection->setExactClientId($connconfig->ClientId);
            $connection->setExactClientSecret($connconfig->ClientSecret);

            //$connection->setAuthorizationCode("5F33%21IAAAABQ1EUQwJ72GLjmoOw_Aaidrh6IbFkIe9mZbFoJ4ZAyG8QAAAAHeIwwubzC0WMc__bzHEI5jrjRp5942k03CZJuaeX9M2fKFvQP51b24UQ4ha5-zqgSAZMBbCL8JBsUbn7bQ3C-yrQdbtfxEBMmdMFpzPQX_ifuZs8vD-Qo3wrXaB0R41VlUhhW6idgWS4EiXvHIJN70dNBqf1eFzI4-3zB6rrFxJDBJNBFuguL8umMYhVYBBs57n_mm-mjT2dh48sdSs9h5EQwmDv1PUfcrEDl0twzEY7MsJizKDCoRdszZH4lfgUed6TSlshhT3O7XFZQ4Amu3pJMR7iFBdDCGqQekvivCBcFoMB_fR2M7vMMVXuJpTJI");
            $connection->setAuthorizationCode($connobject->OauthCode);

            try {
                $connection->connect();
            } catch (\Exception $e)
            {
                throw new Exception('Could not connect to Exact: ' . $e->getMessage());
            }

            //return $connconfig->OauthCode;
        $connobject->AccessToken = serialize($connection->getAccessToken());
        $connobject->RefreshToken = $connection->getRefreshToken();
        $connobject->write();

            //$this->redirect(Director::absoluteBaseURL()."admin/exactonline/Hestec-ExactOnline-ExactOnlineConnection");
        //}

        return '<p>There was an error, <a href="'.Director::absoluteBaseURL().'admin/exactonline/Hestec-ExactOnline-ExactOnlineConnection">CLICK HERE</a> to go back and try again</p>';

        //return $this->getRequest()->getSession()->get('ConnObjectID');

    }

    public function Authorize3() {

        return "TEST";

    }

    public function Disconnect() {

        if (is_numeric($this->getRequest()->param('ID')) && Member::currentUserID() && $this->isCmsMember()){

            $connconfig = ExactOnlineConnection::get()->byID($this->getRequest()->param('ID'));
            $connconfig->OauthCode = '';
            $connconfig->write();

            $this->redirect(Director::absoluteBaseURL()."admin/exactonline/Hestec-ExactOnline-ExactOnlineConnection");

        }

    }

    public function Connect() {

        $connconfig = ExactOnlineConnection::config();

        if (strlen($connconfig->ClientId) > 20 && strlen($connconfig->ClientSecret && Member::currentUserID() && $this->isCmsMember())) {

            $connection = new \Picqer\Financials\Exact\Connection();
            $connection->setRedirectUrl(Director::absoluteBaseURL() . "ExactController/Authorize/".$this->getRequest()->param('ID')); // Same as entered online in the App Center
            $connection->setExactClientId($connconfig->ClientId);
            $connection->setExactClientSecret($connconfig->ClientSecret);
            $connection->redirectForAuthorization();

        }else{

            return '<p>There was an error, <a href="'.Director::absoluteBaseURL().'admin/exactonline/Hestec-ExactOnline-ExactOnlineConnection">CLICK HERE</a> to go back and try again</p>';

        }

    }

}
