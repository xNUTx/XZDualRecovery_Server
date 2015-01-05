<?php
require_once '../library/MR/Mobile.php';

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initAutoload() {
        $autoloader = new Zend_Loader_Autoloader_Resource( array (
    			'namespace' => 'MR_',
    			'basePath' => APPLICATION_PATH,
    			'resourceTypes' => array(
    			        'viewhelper' => array(
    			        		'path'      => '/helpers/view/',
    			        		'namespace' => 'View_Helper_'
    			        ),
    					'helper' => array(
    							'path'      => '/controllers/helpers/',
    							'namespace' => 'Controller_Helper_'
    					),
    			        'model' => array(
								'path'      => 'models/',
								'namespace' => 'Model'
						),
						'mappers' => array(
								'path'      => 'models/mappers/',
								'namespace' => 'Mapper'
						),
						'dbtable' => array(
								'path'      => 'models/DbTable/',
								'namespace' => 'DbTable'
						),
						'form' => array(
								'path'      => 'models/forms/',
								'namespace' => 'Form'
						),
						'formelement' => array(
								'path'      => 'models/forms/elements/',
								'namespace' => 'Form_Element'
						),
	    			)
	    	)
    	);
    	
    }
    
    protected function _initRegistery() {
    	$locale = new Zend_Locale ( 'en_EN' );
    	Zend_Registry::set('Zend_Locale', $locale);
    	unset ( $locale );
		
		$myparams = $this->getOption('myparams');
	    Zend_Registry::set('myparams', $myparams);
	    
	    //require_once '../library/Dcxs/Mobile_Detect.php';
	    $detect = new MR_Mobile_Detect;
	    $deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
	    Zend_Registry::set('device', $deviceType);
	    //Zend_Registry::set('device', 'phone');
	    Zend_Registry::set('android', ($detect->version('Android')>2.1)?(true):(false));
	    
	}

	protected function _initLayout() {
	    
	    $resources = $this->getOption('resources');
	    
	    $layout = Zend_Layout::startMvc();
	    $layout->setLayoutPath($resources["layout"]["layoutPath"]);
	    
	    if (Zend_Registry::get('device') != 'computer') {
	        $layout->setLayout("mobile");
	    } else {
	        $layout->setLayout("desktop");
	    }
	    
	}
	
    protected function _initDoctype() {
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->doctype('XHTML1_STRICT');
    }
    
    protected function _initUserSession() {
    	
    	$myparams = Zend_Registry::get('myparams');
    	$ini = new Zend_Config_Ini(APPLICATION_PATH . '/configs/session.ini', APPLICATION_ENV);
    	$config = $ini->toArray();
    	$jar = new MR_Cookie();
    	$sessioncookie = $jar->readCookie( $config["name"] );
    	
    	if ($sessioncookie !== false) {
    		Zend_Registry::set('cookies', true);
    		if (($preferences =$jar->readCookie( 'preferences' )) !== false) {
    			Zend_Registry::set('layoutprefs', Zend_Json_Decoder::decode($preferences));
    		}
    	} else {
    	    Zend_Registry::set('cookies', false);
    	}
    	
    	Zend_Session::setOptions($config);
    	Zend_Session::start();
    	$session = new Zend_Session_Namespace('user');
    	if (!isset($session->user)) {
    		$session->user = false;
    	}
    	$sessionmsg = new Zend_Session_Namespace('message');
    	if (!isset($sessionmsg->message)) {
    		$sessionmsg->message = false;
    	}
    	if ($sessioncookie !== false && $sessioncookie != Zend_Session::getId()) {
    	    $sessionmsg->message = "Your session has expired.";
    	}
    	$session_title = new Zend_Session_Namespace('title');
    	if (!isset($session_title->pagename) || $session_title->pagename != 'Team Zeus Multi Recovery') {
    		$session_title->pagename = 'Team Zeus Multi Recovery';
    	}
    	
    	
    	return $session;
    }
    
    public function _initRoute() {
    
    	$frontController  = Zend_Controller_Front::getInstance();
    
    	/*
    	$route = new Zend_Controller_Router_Route("webshop",
    			array("action" => "page",
    					"controller" => "index",
    					"module" => "default",
    					"id" => "7",
    					"doc" => "webshop.html")
    	);
    	$frontController->getRouter()->addRoute("webshop",$route);
    	*/
    	
    }
    
    protected function _initView() {
    
    	$options = $this->getOptions();
    	$config = $options['resources']['view'];
    
    	if (isset($config)) {
    		 
    		$view = new Zend_View($config);
    
    	} else {
    		 
    		$view = new Zend_View;
    
    	}
    	
    	/*
    	 * Set base page title
    	 */
    	$view->headTitle('Team Zeus Multi Recovery');
    	
    	if (isset($config['doctype'])) {
    		 
    		$view->doctype($config['doctype']);
    
    	}
    
    	if (isset($config['charset'])) {
    			
    		$view->headMeta()->appendHttpEquiv('Content-Type', 'text/html; charset=' . $config['charset'] . '');
    		
    	}
    
    	$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper(
    			'ViewRenderer'
    	);
    
    	/*
    	 * Base headScript and headLink setup
    	*
    	* This is used on all possible websites, the javascript and css frameworks.
    	*/
    	$view->headScript()->appendFile($view->appendModifiedDate('/js/prototype.js'), $type = 'text/javascript');
    	$view->headScript()->appendFile($view->appendModifiedDate('/js/scriptaculous.js'), $type = 'text/javascript');
    	$view->headScript()->appendFile($view->appendModifiedDate('/js/modalbox.js'), $type = 'text/javascript');
    	$view->headScript()->appendFile($view->appendModifiedDate('/js/contentloaded.js'), $type = 'text/javascript');
    	
    	//$view->headLink()->appendStylesheet($view->appendModifiedDate('/css/blueprint/screen.css'), 'screen, projection', FALSE);
    	//$view->headLink()->appendStylesheet($view->appendModifiedDate('/css/blueprint/plugins/liquid/screen.css'), 'screen, projection', FALSE);
    	//$view->headLink()->appendStylesheet($view->appendModifiedDate('/css/blueprint/print.css'), 'projection, print', FALSE);
    	//$view->headLink()->appendStylesheet($view->appendModifiedDate('/css/blueprint/ie.css'), 'screen, projection', 'IE');
    	$view->headLink()->appendStylesheet($view->appendModifiedDate('/css/modalbox.css'), 'screen, projection', FALSE);
    
    	/*
    	 * Read and apply the theme javascript.
    	*/
    	$view->headScript()->appendFile($view->appendModifiedDate('/js/main.js'), $type = 'text/javascript');
    
    	/*
    	 * Read and apply the theme stylesheet.
    	*/
    	$view->headLink()->appendStylesheet($view->appendModifiedDate('/css/main.css'), 'screen, projection', FALSE);
    	
    	if (Zend_Registry::get('device') != 'computer') {
    		$view->headLink()->appendStylesheet($view->appendModifiedDate('/css/mobile.css'), 'screen, projection', FALSE);
    		$view->headMeta()->appendName("viewport", "content=width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no");
    	} else {
    	    $view->headLink()->appendStylesheet($view->appendModifiedDate('/css/desktop.css'), 'screen, projection', FALSE);
    	}
    	$viewRenderer->setView($view);
    
    	return $view;
    
    }
    
}

