<?php

require_once 'Zend/Controller/Action.php';

class MR_Dispatch extends Zend_Controller_Action {

	public function preDispatch() {
		
		$this->checkLogin();
		
	}

	public function postDispatch() {
	
		//
		
	}
	
	protected function checkLogin($controller = false, $action = false) {
	
		$frontController = Zend_Controller_Front::getInstance();
		
		if ($controller == false) {
			$controller = $frontController->getRequest()->getControllerName();
		}
		if ($action == false) {
			$action = $frontController->getRequest()->getActionName();
		}
		Zend_Registry::set('visibleaction', $action);
		Zend_Registry::set('visiblecontroller', $controller);
		
		$session = new Zend_Session_Namespace('user');
		$redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
		if (($controller == "dcxs" || $controller == "dcxsadmin") && (!isset($session->user->dcxsrole) || $session->user->dcxsrole == false)) {
			$redirector->gotoUrl('/');
		} elseif ($controller == "dcxs" && $action == "testing" && $session->user->dcxsrole != false) {
			// test conditie... 
		} elseif ($controller != "dcxsadmin" && $session->user->dcxsrole >= 4) {
			$redirector->gotoUrl('/dcxsadmin');
		} elseif ($controller == "dcxsadmin" && $session->user->dcxsrole < 4) {
			$redirector->gotoUrl('/dcxs');
		} elseif ($controller == "dcxs" && $action != "notification" && $action != "dcinfo" && $action != "done" && $action != "menu" && $action != "request" && $action != "shipment" && ($session->user->dcxsrole == 1 || $session->user->dcxsrole == 2)) {
			$redirector->gotoUrl('/request');
		}
		
		// Reset timer, new request extends the session's lifetime.
		$myparams = Zend_Registry::get('myparams');
		Zend_Session::rememberMe( $myparams["sessionlifetime"] );
		
	}
	
}

