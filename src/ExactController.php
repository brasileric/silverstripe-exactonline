<?php

use SilverStripe\Control\Controller;
use SilverStripe\SiteConfig\SiteConfig;
use SilverStripe\Security\Member;
use SilverStripe\Control\Director;

class ExactController extends Controller {

    private static $allowed_actions = array (
        'Authorize',
        'Connect',
        'Connected'
    );

    public function MySession()
    {
        return $this->getRequest()->getSession();
    }

    public function Authorize() {

        //$code = $_GET['code'];


        //if ($_GET['code'] && Member::currentUserID()){
        if ($_GET['code']){

            //$this->MySession()->set('ExactCode', $_GET['code']);

        //if (1 == 2){
            $config = SiteConfig::get()->byID(1);
            $config->ExactOauth = $_GET['code'];
            //$config->ExactOauth = "1234";
            $config->write();

            $this->redirect(Director::absoluteBaseURL()."ExactController/Connected");
        }

        //return SiteConfig::current_site_config()->GlobalFromEmail;
        //return SiteConfig::current_site_config()->GlobalFromEmail();

        return "fout";

    }

    public function xxAuthorize() {

        $code = $_GET['code'];


        //if ($_GET['code'] && Member::currentUserID()){
        if ($_GET['code']){

            $this->MySession()->set('ExactCode', $_GET['code']);

        //if (1 == 2){
            //$config = SiteConfig::get()->byID(1);
            //$config->ExactOauth = $code;
            //$config->ExactOauth = "1234";
            //$config->write();

            $this->redirect(Director::absoluteBaseURL()."ExactController/Connected");
            //return $_GET['code'];
        }

        //return SiteConfig::current_site_config()->GlobalFromEmail;
        //return SiteConfig::current_site_config()->GlobalFromEmail();

        return "fout";

    }

    public function Connected() {

        return $_SERVER['HTTP_HOST']." has been successfully authorized by Exact Online and connected with your Exact Online account. Close this screen or tab.";

    }

    public function Connect() {

        $config = SiteConfig::get()->byID(1);

        if (strlen($config->ExactClientId) > 20 && strlen($config->ExactClientSecret) > 10 && strlen($config->ExactWebhookSecret) > 10) {

            $connection = new \Picqer\Financials\Exact\Connection();
            $connection->setRedirectUrl(Director::absoluteBaseURL() . "ExactController/Authorize"); // Same as entered online in the App Center
            $connection->setExactClientId($config->ExactClientId);
            $connection->setExactClientSecret($config->ExactClientSecret);
            $connection->redirectForAuthorization();

        }else{

            return "The credentials you entered are not valid or you clicked the CONNECT button without clicked the Save button first. Go back to the CMS, check and try again. Close this screen or tab.";

        }

    }

}
