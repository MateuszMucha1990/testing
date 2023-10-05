<?php 


if (!defined('_PS_VERSION_')) {
    exit;
};

class MyModule extends Module
{

    
    public function __construct()
    {
        $this->name = 'mymodule';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'mateusz mucha';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = [
            'min' => '1.6.0',
            'max' => '1.7.9',];
        $this->bootstrap = true;

        parent:: __construct();
        
        $this->displayName = $this->l('Pierwszy modul', 'mymodule');
        $this->description = $this->l('opis modułu.','mymodule');    
   
        $this->confirmUninstall = $this->l('Chcesz odinstalować');
        if (!Configuration::get('MYMODULE_NAME')) {
            $this->warning = $this->l('No name provided.');
        }
    }    


 

public function install()
    {
        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }
    
        return parent::install() &&
             $this->registerHook('displayLeftColumn') &&

            //CSS
  $this->registerHook('actionFrontControllerSetMedia') &&

            //DISPLAY HOME
   $this->registerHook('displayHome') &&
            Configuration::updateValue('MYMODULE_NAME', 'my friend');

        if (!parent::install() || !$this->registerHook('displayHome')|| !$this->installDB() )
         {
                 return false;}
            
                 //AJAX
     $this->registerAjaxController('myModuleAjax');
                 return parent::install();

 return true;

}



public function uninstall()
    {
        return parent::uninstall();
    }


public function displayForm()
{
    $form = [
        'form' => [
            'legend' => [
                'title' => $this->l('Dane'),
            ],
            'input' => [
                [
                    'type' => 'textarea',
                    'label' => $this->l('Wprowadź Imie'),
                    'name' => 'myfirstmodule',
                    'size' => 20,
                    'required' => true,
                    'autoload_rte' => true,
                ],
            ],
            'submit' => [
                'title' => $this->l('Save'),
                'class' => 'btn btn-default pull-right',
            ],
        ],
    ];

    $helper = new HelperForm();

    $helper->table = $this->table;
    $helper->name_controller = $this->name;
    $helper->token = Tools::getAdminTokenLite('AdminModules');
    $helper->currentIndex = AdminController::$currentIndex . '&' . http_build_query(['configure' => $this->name]);
    $helper->submit_action = 'submit' . $this->name;


    $helper->default_form_language = (int) Configuration::get('PS_LANG_DEFAULT');

    //$helper->fields_value['myfirstmodule'] = Tools::getValue('myfirstmodule', Configuration::get('myfirstmodule'));
    $helper->tpl_vars = array(
        'fields_value' => array('myfirstmodule' => Configuration::get('myfirstmodule')),
        'languages' => $this->context->controller->getLanguages());

    return $helper->generateForm([$form]);
}




public function getContent()
    {
        $output = null;
 
       if (Tools::isSubmit('submit'.$this->name)) {
            $myfirstmodule = strval(Tools::getValue('myfirstmodule'));
 
            
            if (!isset($myfirstmodule)){
                $output .= $this->displayError($this->l('Proszę uzupełnić pole.'));
            } else {
                Configuration::updateValue('myfirstmodule', $myfirstmodule);
 
                $output .= $this->displayConfirmation($this->l('Ustawienia zostały zaktualizowane.')
            
            );
            }
       }

return $myfirstmodule.$this->display(__FILE__, 'views/templates/front/lista.tpl').' ' .$output.$this->displayForm(__FILE__, 'views/templates/front/lista.tpl');
 }



public function hookDisplayHome($params)
    {
        $this->context->smarty->assign(
            array('myfirstmodule' => Configuration::get('myfirstmodule'))
        );
        return Configuration::get('myfirstmodule').$this->display(__FILE__, '/views/templates/hook/myfirstmodule.tpl');
    }


public function hookActionFrontControllerSetMedia()
    {
        $this->context->controller->registerStylesheet(
            'mymodule-style',
            $this->_path.'views/css/mymodule.css',
            [
                'media' => 'all',
                'priority' => 1000,
            ]
        );

        $this->context->controller->registerJavascript(
            'mymodule-javascript',
            $this->_path.'views/js/mymodule.js',
            [
                'position' => 'bottom',
                'priority' => 1000,
            ]
        );
    } 
    
    // public function hookDisplayLeftColumn($params)
    // {
    //     $this->context->smarty->assign([
    //         'my_module_name' => Configuration::get('MYMODULE_NAME'),
    //         'my_module_link' => $this->context->link->getModuleLink('mymodule', 'display')
    //     ]);

    //     return $this->display(__FILE__, '/views/templates/myfirstmodule.tpl');
    // }
    
    // public function hookDisplayRightColumn($params)
    // {
    //     return $this->hookDisplayLeftColumn($params);
    // }




    public function installDB(){
        $correct = true;
 
        $correct = Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'myfirstmodule` ( 
                `id_myfirstmodule` INT UNSIGNED NOT NULL AUTO_INCREMENT , 
                `id_shop` INT NOT NULL , 
                `blank` INT NOT NULL ,
                PRIMARY KEY (`id_myfirstmodule`)
            ) ENGINE = ' . _MYSQL_ENGINE_ . ';
        ');
 
 
        return $correct;
    }




    private function registerAjaxController($controllerName)
    {
        $controller = new MyModuleAjaxController();
        $controller->run();
    
    }







};
