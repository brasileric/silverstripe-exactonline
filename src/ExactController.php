<?php

use SilverStripe\Control\Controller;
use SilverStripe\Security\Member;
use SilverStripe\Control\Director;
use Hestec\ExactOnline\ExactOnlineConnection;
use SilverStripe\Security\Permission;

class ExactController extends Controller {

    private static $allowed_actions = array (
        'Authorize',
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

        if (strlen($_GET['code']) > 100 && is_numeric($this->getRequest()->param('ID')) && Member::currentUserID() && $this->isCmsMember()){

            $connconfig = ExactOnlineConnection::get()->byID($this->getRequest()->param('ID'));
            $connconfig->OauthCode = $_GET['code'];
            $connconfig->write();

            $this->redirect(Director::absoluteBaseURL()."admin/exactonline/Hestec-ExactOnline-ExactOnlineConnection");
        }

        return '<p>There was an error, <a href="'.Director::absoluteBaseURL().'admin/exactonline/Hestec-ExactOnline-ExactOnlineConnection">CLICK HERE</a> to go back and try again</p>';
        //$this->getRequest()->getSession()->set('ConnObjectID', 2);

        //return $this->getRequest()->getSession()->get('ConnObjectID');

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
