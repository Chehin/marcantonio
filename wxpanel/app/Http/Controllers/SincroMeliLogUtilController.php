<?php
namespace App\Http\Controllers;


class SincroMeliLogUtilController extends GenericUtilController
{
    public function __construct(SincroMeliLogController $res) {

        parent::__construct();

        $this->resource = $res->resource;
        $this->resourceLabel = $res->resourceLabel;
        $this->user = $res->user;
        $this->modelName = $res->modelName;
        $this->viewPrefix = $res->viewPrefix;
        $this->aExtraParams['imageCropW'] = 1024;
        $this->aExtraParams['imageCropH'] = 210;
        //$this->itemNameField = 'titulo';
    }
}
