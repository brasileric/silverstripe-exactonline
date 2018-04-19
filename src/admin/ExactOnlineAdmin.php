<?php

namespace Hestec\ExactOnline;

use SilverStripe\Admin\ModelAdmin;
use SilverStripe\Forms\GridField\GridFieldFilterHeader;
use SilverStripe\Forms\GridField\GridFieldDeleteAction;
use SilverStripe\Forms\GridField\GridFieldAddNewButton;
use SilverStripe\Forms\GridField\GridFieldPrintButton;
use SilverStripe\Forms\GridField\GridFieldImportButton;
use SilverStripe\Forms\GridField\GridFieldExportButton;
use SilverStripe\Forms\GridField\GridFieldDataColumns;

class ExactOnlineAdmin extends ModelAdmin {

    private static $managed_models = array(
        ExactOnlineConnection::class
    );

    // disable the importer
    private static $model_importers = array();

    // Linked as /admin/slides/
    private static $url_segment = 'exactonline';

    // title in cms navigation
    private static $menu_title = 'ExactOnline';

    // menu icon
    private static $menu_icon = 'resources/hestec/silverstripe-exactonline/client/images/icons/icon-exactonline.png';

    public function getEditForm($id = null, $fields = null)
    {
        $form = parent::getEditForm($id, $fields);

        // $gridFieldName is generated from the ModelClass, eg if the Class 'Product'
        // is managed by this ModelAdmin, the GridField for it will also be named 'Product'


        /*$gridFieldName = $this->sanitiseClassName($this->modelClass);
        $gridField = $form->Fields()->fieldByName($gridFieldName);

        // modify the list view.
        $gridField->getConfig()->addComponent(new GridFieldFilterHeader());*/

        // get gridfield
        $gridfield = $form->Fields()
            ->dataFieldByName($this->sanitiseClassName($this->modelClass));

        $gridfieldConfig = $gridfield->getConfig();

        $gridfieldConfig->removeComponentsByType(GridFieldDeleteAction::class);
        $gridfieldConfig->removeComponentsByType(GridFieldAddNewButton::class);
        $gridfieldConfig->removeComponentsByType(GridFieldPrintButton::class);
        $gridfieldConfig->removeComponentsByType(GridFieldImportButton::class);
        $gridfieldConfig->removeComponentsByType(GridFieldExportButton::class);
        /*$dataColumns = $gridfieldConfig->getComponentByType(GridFieldDataColumns::class);
        //->addComponent(new GridFieldFilterHeader());


        $dataColumns->setFieldCasting(array(
            'ConnectionTitle' => 'HTMLText->RAW'
        ));*/


        return $form;
    }

}
