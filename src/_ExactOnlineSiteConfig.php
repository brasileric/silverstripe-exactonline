<?php

namespace Hestec\ExactOnline;

use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\LiteralField;
use SilverStripe\Forms\NumericField;
use SilverStripe\Forms\TreeDropdownField;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\DataExtension;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\HiddenField;
use SilverStripe\Control\Director;
use SilverStripe\SiteConfig\SiteConfig;

class ExactOnlineSiteConfig extends DataExtension {

    private static $db = array(
        'ExactClientId' => 'Varchar(100)',
        'ExactClientSecret' => 'Varchar(100)',
        'ExactWebhookSecret' => 'Varchar(100)',
        'ExactOauth' => 'Text'
    );

    public function updateCMSFields(FieldList $fields)
    {

        $siteconfigid = SiteConfig::current_site_config()->ID;

        $infoheader = "<p><strong>Important! first read this steps to connect with Exact Online:</strong></p>";
        $infostep_1 = "<li>Enter the credentials from the Exact Online app in the right fields en click Save.</li>";
        $infostep_2 = "<li>When saved successfully the Connect button appears, click this button.</li>";
        $infostep_3 = "<li>A new screen opens where you will be asked to login in your Exact Online account for authorize this website.</li>";
        $infostep_4 = "<li>When the connection is successful a message appears and you can close that screen</li>";
        $infostep_5 = "<li>Refresh the browser of the CMS, after that you see the right status of the connection with Exact Online on this page in the CMS.</li>";

        $infotext = $infoheader."<ol>".$infostep_1.$infostep_2.$infostep_3.$infostep_4.$infostep_5."</ol>";

        $IntroField = LiteralField::create('IntroField', $infotext);
        $ExactClientIdField = TextField::create('ExactClientId', 'ClientId');
        $ExactClientSecretField = TextField::create('ExactClientSecret', 'ClientSecret');
        $ExactWebhookSecretField = TextField::create('ExactWebhookSecret', 'WebhookSecret');
        $ConnectButtonField = LiteralField::create('ConnectButtonField', '<a href="'.Director::absoluteBaseURL().'ExactController/Connect/'.$siteconfigid.'" target="_blank" class="btn btn-primary">CONNECT</a>');

        $fields->addFieldsToTab("Root.ExactOnline", array(
            $IntroField,
            $ExactClientIdField,
            $ExactClientSecretField,
            $ExactWebhookSecretField,
            $ConnectButtonField
        ));

    }

}