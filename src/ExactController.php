<?php

use SilverStripe\Control\Controller;
use SilverStripe\SiteConfig\SiteConfig;
use SilverStripe\Security\Member;

class ExactController extends Controller {

    private static $allowed_actions = array (
        'Authorize',
        'Setup'
    );

    public function Authorize() {

        $code = $_GET['code'];


        if ($_GET['code'] && Member::currentUserID()){

        //if (1 == 2){
            //$config = SiteConfig::get()->byID(1);
            //$config->ExactOauth = $code;
            //$config->ExactOauth = "1234";
            //$config->write();

            $this->redirect("https://www.hestec.nl?code=".$_GET['code']);
            //return $_GET['code'];
        }

        //return SiteConfig::current_site_config()->GlobalFromEmail;
        //return SiteConfig::current_site_config()->GlobalFromEmail();

        return "fout";

    }

    public function Setup() {

        $connection = new \Picqer\Financials\Exact\Connection();
        $connection->setRedirectUrl('http://www.ss-boilerplate.hst1.nl/ExactController/Authorize'); // Same as entered online in the App Center
        $connection->setExactClientId('a9804607-e1b6-4a7b-9ec8-8c670e822510');
        $connection->setExactClientSecret('DSG5SPofzWny');
        $connection->redirectForAuthorization();

        //return SiteConfig::current_site_config()->GlobalFromEmail;
        //return SiteConfig::current_site_config()->GlobalFromEmail();

    }


}
