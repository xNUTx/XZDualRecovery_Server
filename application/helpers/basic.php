<?php

class MR_Helper_Basic {
	
	public function stripSubdomains( $requestHost ) {
	
		if (substr_count($requestHost, ".") > 1) {
				
			$parts = preg_split("/\./", $requestHost, null, PREG_SPLIT_NO_EMPTY);
			
			$total = count($parts);
				
			return $parts[($total-2)] . "." . $parts[($total-1)];
				
		}
		return $requestHost;
	
	}
	
	public function makeIniSafe( $array = array() ) {
		
		array_walk_recursive($array, 'self::replaceQuotes');
		
		return $array;
		
	}
	
	public function makeIniUnsafe( $array = array() ) {
		
		array_walk_recursive($array, 'self::restoreQuotes');
		
		return $array;
		
	}
	
	public static function replaceQuotes( &$value, $key ) {
		
		$quotereplacement = '&#34;';
		
		$value = str_replace('"', $quotereplacement, $value, $replaced);
		
	}
	
	public static function restoreQuotes( &$value, $key ) {
		
		$quotereplacement = '&#34;';
		
		$value = str_replace($quotereplacement, '"', $value);
		
	}
	
}