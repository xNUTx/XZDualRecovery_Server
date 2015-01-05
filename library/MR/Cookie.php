<?php

/**
 * Cookie Management Model
 *
 * @author b.schut
 * @version
 */

class MR_Cookie {
	
	protected $_name;
	protected $_content;
	protected $_host;
	protected $_expire;
	protected $_path;
	protected $_secure;
	
	public function createCookie($name, $content, $host, $expire = 0, $path = '/', $secure = null) {
		
		if (isset($name) && isset($content) && isset($host)) {
			
			$this->_name = $name;
			$this->_content = $content;
			$this->_host = $host;
			$this->_expire = $expire;
			
			if ($path != null) {
				$this->_path = $path;
			}
			if ($secure != null) {
				$this->_secure = $secure;
			}
			
			return self::setCookie();
			
		} else {
			
			throw new Zend_Application_Exception("MR_Cookie::createCookie mandatory options (name, content and httphost) not met!");
			
			return false;
			
		}
		
	}
	
	public function checkCookie( $name, $exception = false ) {
		
		if (isset($_COOKIE[$name]) && !$exception) {
			
			return true;
			
		} else {
			
			if (isset($_COOKIE[$name]) && $exception) {
				
				return true;
				
			} elseif (!isset($_COOKIE[$name]) && $exception) {
				
				throw new Zend_Application_Exception("MR_Cookie::checkCookie Exception: The specified cookie does not exist!");
				return false;
				
			} else {
				
				return false;
				
			}
			
		}
		
	}
	
	public function readCookie( $name,  $exception = false) {
		
		if (self::checkCookie($name, $exception)) {
			
			return $_COOKIE[$name];
			
		} else {
			
			return false;
			
		}
		
	}
	
	private function setCookie() {
		
		if (setcookie($this->_name, $this->_content, $this->_expire, $this->_path, $this->_host, $this->_secure, false) !== false) {
			
			return $this->_name;
			
		} else {
			
			throw new Zend_Application_Exception("MR_Cookie::setCookie Exception: Failed to create cookie, there has been output before creation!");
			return false;
			
		}
		
	}
	
	public function destroyCookie( $name, $content, $host, $expire = 0, $path = '/', $secure = null ) {
		
		if (isset($name) && isset($content) && isset($host)) {
			
			$this->_name = $name;
			$this->_content = $content;
			$this->_host = $host;
			if ($expire == 0) {
				$this->_expire = time() - 3600;
			} else {
				$this->_expire = $expire;
			}
			
			if ($path != null) {
				$this->_path = $path;
			}
			if ($secure != null) {
				$this->_secure = $secure;
			}
			
			return self::setCookie();
			
		} else {
			
			throw new Zend_Application_Exception("MR_Cookie::destroyCookie mandatory options (name, content and httphost) not met!");
			
			return false;
			
		}
		
	}
	
}