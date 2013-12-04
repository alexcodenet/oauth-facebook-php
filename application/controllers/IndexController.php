<?php

class IndexController extends Zend_Controller_Action
{
    
    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        //do nothing...
    }
    
    public function facebookAction()
    {
        try 
        {
            $request = $this->getRequest();
            $code = $request->getParam('code');
            
            if(isset($code))
            {
                $string = "https://graph.facebook.com/oauth/access_token?client_id="
                        . Zend_Registry::get('constants')->fboauth->app_id 
                        . "&redirect_uri="
                        . Zend_Registry::get('constants')->fboauth->redirect_uri
                        . "&client_secret="
                        . Zend_Registry::get('constants')->fboauth->app_secret
                        . "&code=" . $code;
            
                $http = new Zend_Http_Client($string);
                $result = $http->request();
            
                if($result != NULL)
                {
                    $token = $result->getBody();
                    $output = "https://graph.facebook.com/me?" . $token;
                    $http->setUri($output);
                    $json = Zend_Json_Decoder::decode($http->request()->getBody());

                    $session = new Zend_Session_Namespace('facebook');
                    $session->setExpirationSeconds(864000);
                    $session->id = $json['id'];
                    $session->name = $json['name'];
                    $session->username = $json['username'];
                    $session->link = $json['link'];
                    $session->avatar = "http://graph.facebook.com/".$json['username']."/picture";
                    $session->hometown_id = $json['hometown']['id'];
                    $session->hometown_name = $json['hometown']['name'];
                    
                    $this->_redirect('/');
                    
                }
            }
        }
        catch (Zend_Exception $e)
        {
            //error processing
        }
    }
}

