<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    
    public function _initConfig() 
    {
        Zend_Registry::set('constants', new Zend_Config_Ini(
                APPLICATION_PATH . '/configs/application.ini', 'constants'));
        
        $config = new Zend_Config($this->getOptions(), true);
        Zend_Registry::set('config', $config);
        return $config;
    }
}

