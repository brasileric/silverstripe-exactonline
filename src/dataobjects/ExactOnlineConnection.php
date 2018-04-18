<?php

namespace Hestec\ExactOnline;

use SilverStripe\ORM\DataObject;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\LiteralField;
use SilverStripe\Forms\TextField;
use SilverStripe\Control\Director;
use SilverStripe\ORM\FieldType\DBField;
use SilverStripe\Forms\RequiredFields;

class ExactOnlineConnection extends DataObject {

    //private static $singular_name = 'Customer';
    //private static $plural_name = 'Customers';

    private static $table_name = 'ExactOnlineConnection';

    private static $db = array(
        'ClientId' => 'Varchar(100)',
        'ClientSecret' => 'Varchar(100)',
        'WebhookSecret' => 'Varchar(100)',
        'OauthCode' => 'Text'
    );

    private static $summary_fields = array(
        'getConnectionTitle'
    );

    public function getConnectionTitle(){
        return DBField::create_field('HTMLText', "Exact Online not connected yet, click here to open and connect.");
    }

        // Run on dev buld
    function requireDefaultRecords() {
        parent::requireDefaultRecords();

        if (ExactOnlineConnection::get()->count() == 0){

            $connection = new ExactOnlineConnection();
            $connection->write();

        }

    }

    function fieldLabels($includerelations = true) {
        $labels = parent::fieldLabels($includerelations);

        $labels['getConnectionTitle'] = '';

        return $labels;
    }

    public function getCMSFields() {

        $infoheader = "<p><strong>Important! first read this steps to connect with Exact Online:</strong></p>";
        $infostep_1 = "<li>Enter the credentials from the Exact Online app in the right fields en click Save.</li>";
        $infostep_2 = "<li>When saved successfully the Connect button appears, click this button.</li>";
        $infostep_3 = "<li>A new screen opens where you will be asked to login in your Exact Online account for authorize this website.</li>";
        $infostep_4 = "<li>When the connection is successful a message appears and you can close that screen</li>";
        $infostep_5 = "<li>Refresh the browser of the CMS, after that you see the right status of the connection with Exact Online on this page in the CMS.</li>";

        $infotext = $infoheader."<ol>".$infostep_1.$infostep_2.$infostep_3.$infostep_4.$infostep_5."</ol>";

        $IntroField = LiteralField::create('IntroField', $infotext);
        $ClientIdField = TextField::create('ClientId', 'ClientId');
        $ClientSecretField = TextField::create('ClientSecret', 'ClientSecret');
        $WebhookSecretField = TextField::create('WebhookSecret', 'WebhookSecret');
        if (strlen($this->ClientId) > 20 && strlen($this->ClientSecret) > 10 && strlen($this->WebhookSecret) > 10) {

            $ConnectButtonField = LiteralField::create('ConnectButtonField', '<a href="' . Director::absoluteBaseURL() . 'ExactController/Connect/' . $this->ID . '" class="btn btn-primary">CONNECT</a>');

        }else{

            $ConnectButtonField = LiteralField::create('ConnectButtonField', "The connect button will appear after you enter the credentials and click Save.");

        }
        return new FieldList(
            $IntroField,
            $ClientIdField,
            $ClientSecretField,
            $WebhookSecretField,
            $ConnectButtonField
        );

    }

    public function validate()
    {
        $result = parent::validate();

        if(strlen($this->ClientId) <= 20) {
            $result->addError(_t('ExactOnlineConnection.VALIDATE_CLIENTID', "ClientId is too short."));
        }
        if(strlen($this->ClientSecret) <= 10) {
            $result->addError(_t('ExactOnlineConnection.VALIDATE_CLIENTID', "ClientSecret is too short."));
        }
        if(strlen($this->WebhookSecret) <= 10) {
            $result->addError(_t('ExactOnlineConnection.VALIDATE_CLIENTID', "WebhookSecret is too short."));
        }

        return $result;
    }

    public function canDelete($member = null) {
        return false;
    }

}