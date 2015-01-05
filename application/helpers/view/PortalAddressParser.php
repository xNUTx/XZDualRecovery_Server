<?php

class MR_View_Helper_PortalAddressParser extends Zend_View_Helper_Abstract
{
	
	/**
	 * Parses supplied data to generate a full URL.
	 * Defaults to creating a HTTPS url.
	 * @param string $httphost
	 * @param string $controller
	 * @param string $action
	 * @param array $data
	 * @param string $scheme
	 * @return string
	 */
	
	public function parseUrl( $httphost, $module, $controller, $action, $data = null, $scheme = null ) {
		
		if ($scheme != "ssl") {
			$url = "http://";
		} else {
			$url = "https://";
		}
		
		$url .= $httphost;
		return $url . $this->parseUri($module, $controller, $action, $data);
		
	}
	
	/**
	 * Parses supplied data to only generate the URI.
	 * @param string $controller
	 * @param string $action
	 * @param array $data
	 */
	public function parseUri( $module, $controller, $action, $data = null ) {
		
		$uri = "/" . $module . "/" . $controller . "/" . $action;
		
		if (count($data) == 0 || !is_array($data)) {
			return $uri . "/";
		}
		
		unset($data["controller"]);
		unset($data["action"]);
		unset($data["module"]);
		
		if (count($data) > 0) {
			
			foreach ($data as $key => $value) {
				
				if ($key == "" || $value == "") {
					continue;
				}
				
				$uri .= "/" . $key . "/" . $value;
				
			}
			
		}
		return $uri;
	
	}
	
}
