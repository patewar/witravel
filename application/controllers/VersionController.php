<?php

class VersionController extends Zend_Rest_Controller
{


    public function init() { 
        $bootstrap = $this->getInvokeArg('bootstrap');   
        $options = $bootstrap->getOption('resources');   
        $contextSwitch = $this->_helper->getHelper('contextSwitch'); 
        $contextSwitch->addActionContext('index', array('xml','json'))->initContext(); 
        //$this->_helper->viewRenderer->setNeverRender();  
        $this->view->success = "true"; 
        $this->view->version = "1.0"; 
    }


    public function headAction() {}

    // Handle GET and return a list of resources
    public function indexAction() {
        echo __METHOD__;
    }

    // Handle GET and return a specific resource item
    public function getAction() {
        echo __METHOD__;
    }

    // Handle POST requests to create a new resource item
    public function postAction() {
        echo __METHOD__;
    }

    // Handle PUT requests to update a specific resource item
    public function putAction() {
        $this->_forward('index');
    }

    // Handle DELETE requests to delete a specific item
    public function deleteAction() {
        $this->_forward('index');
    }
}