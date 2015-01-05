<?php

class IndexController extends Zend_Controller_Action
{

    public function init() {
        /* Initialize action controller here */
    	$frontController = Zend_Controller_Front::getInstance();
    	$action = $frontController->getRequest()->getActionName();
    	Zend_Registry::set('visibleaction', $action);
    	$controller = "index";
    }

    public function indexAction() {
        // action body
    }

    public function loadAction() {
    	
    	$this->_helper->layout()->disableLayout();
    	$params = $this->getRequest()->getUserParams();
    	
    	//throw new Zend_Exception('BLURGH!', 500);
    	
    	$this->view->blabla = $params["dinges"];
    	
    }

    public function editAction() {
    	
    	$params = $this->getRequest()->getUserParams();
    	
    	//throw new Zend_Exception('BLURGH!', 500);
    	
    	$this->view->showing = $params["edit"];
    	
    }

    public function saveAction() {
    	
    	$this->_helper->layout()->disableLayout();
    	
    	//throw new Zend_Exception('BLURGH!', 500);
    	
    	$this->view->post = $this->getRequest()->getPost();
    	
    }
	
    public function hubAction() {
    	 
    	$this->_helper->layout()->disableLayout();
    	$params = $this->getRequest()->getUserParams();
    	 
    	//throw new Zend_Exception('BLURGH!', 500);
    	
    	if (isset($params["overview"])) {
    		$this->renderScript("index/hub.phtml");
    	}
    	if (isset($params["sales"])) {
    		$this->renderScript("hub/sales.phtml");
    	}
    	if (isset($params["finance"])) {
    		$this->renderScript("hub/finance.phtml");
    	}
    	if (isset($params["hardware"])) {
    		$this->renderScript("hub/hardware.phtml");
    	}
    	if (isset($params["visits"])) {
    		$this->renderScript("hub/visits.phtml");
    	}
    	if (isset($params["contact"])) {
    		$this->renderScript("hub/contact.phtml");
    	}
    	
    }
    
}

