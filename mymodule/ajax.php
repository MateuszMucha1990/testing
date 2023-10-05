<?php

class MyModouleAjaxController  extends ModuleFrontController
{
	// public function initContent() {
    //     parent::initContent();
    //     $this->ajax = true; // enable ajax
    // }


    // public function ajaxProcess()
    // {
    //   $query = 'SELECT * FROM mytable';
    //   echo Tools::jsonEncode(array(
    //     'data'=> Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query),
    //     'fields_display' => $this->fieldsDisplay
    //   ));
    //   die();
    // }


    public function __construct()
    {
        parent::__construct();
    }

    
    public function initContent()
    {
        parent::initContent();
        $this->ajax = true; // enable ajax
        
        $response = array('name' => 'myfirstmodule');
        
        
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }






//     header('Content-Type: application/json');
//         exit(json_encode([
//             'preview' => $this->module instanceof Ps_Shoppingcart ? $this->module->renderWidget(null, ['cart' => $this->context->cart]) : '',
//             'modal' => $modal,
//         ]));
 }