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
        'OauthCode' => 'Text'
    );

    private static $summary_fields = array(
        'getConnectionTitle'
    );

    public function getConnectionTitle(){

        if (strlen($this->OauthCode) > 100){

            return DBField::create_field('HTMLText', 'Exact Online <span style="color: green;font-weight: bold;">'._t("ExactOnlineConnection.CONNECTED", "CONNECTED").'</span>, '._t("ExactOnlineConnection.CLICK_HERE_TO_DISCONNECT", "click here to disconnect."));

        }else{

            return DBField::create_field('HTMLText', 'Exact Online <span style="color: red;font-weight: bold;">'._t("ExactOnlineConnection.NOT_CONNECTED", "NOT CONNECTED").'</span>, '._t("ExactOnlineConnection.CLICK_HERE_TO_CONNECT", "click here to connect."));

        }

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

        $infoheader = "<p><strong>"._t("ExactOnlineConnection.INSTRUCTIONS", "Instructions").":</strong></p>";
        $infostep_1 = "<li>"._t("ExactOnlineConnection.INFOSTEP_1", "Click the CONNECT button.")."</li>";
        $infostep_2 = "<li>"._t("ExactOnlineConnection.INFOSTEP_2", "You will be redirected to the Exact Online login screen.")." *</li>";
        $infostep_3 = "<li>"._t("ExactOnlineConnection.INFOSTEP_3", "Login with your Exact Online credentials.")." *</li>";
        $infostep_4 = "<li>"._t("ExactOnlineConnection.INFOSTEP_4", "When the connection is made, you will be redirected back to the CMS.")."</li>";
        $infostep_5 = "<li>"._t("ExactOnlineConnection.INFOSTEP_5", "The status in the CMS will display CONNECTED.")."</li>";
        $infostep_6 = "<p>* "._t("ExactOnlineConnection.INFOSTEP_6", "note: when you are already logged in in Exact Online with the same browser, step 2 en 3 are skipped.")."</p>";

        $infotext = $infoheader."<ol>".$infostep_1.$infostep_2.$infostep_3.$infostep_4.$infostep_5."</ol>".$infostep_6;

        $IntroField = LiteralField::create('IntroField', $infotext);
        //$ClientIdField = TextField::create('ClientId', 'ClientId');
        //$ClientSecretField = TextField::create('ClientSecret', 'ClientSecret');
        //$WebhookSecretField = TextField::create('WebhookSecret', 'WebhookSecret');
        if (strlen($this->OauthCode) < 100) {

            $ConnectButtonField = LiteralField::create('ConnectButtonField', '<a href="' . Director::absoluteBaseURL() . 'ExactController/Connect/' . $this->ID . '" class="btn btn-primary font-icon-save">'._t("ExactOnlineConnection.CONNECT", "CONNECT").'</a>');

        }else{

            $ConnectButtonField = LiteralField::create('ConnectButtonField', '<a href="' . Director::absoluteBaseURL() . 'ExactController/Disconnect/' . $this->ID . '" class="btn btn-primary font-icon-logout">'._t("ExactOnlineConnection.DISCONNECT", "DISCONNECT").'</a>');

        }
        return new FieldList(
            $IntroField,
            $ConnectButtonField
        );

    }

    /*public function validate()
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
    }*/

    public function canDelete($member = null) {
        return false;
    }
    public function canEdit($member = null) {
        return false;
    }
    public function canView($member = null) {
        return Permission::check('CMS_ACCESS_CMSMain', 'any', $member);
    }

}