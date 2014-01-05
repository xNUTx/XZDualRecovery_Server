<?php
function EncodeData($data){
	if (openssl_public_encrypt($data, $crypted, file_get_contents('publickey.pem'))){
		return base64_encode($crypted);
	}
}
$decoded_data='string_to_encode';
echo "Encoded: " . EncodeData($decoded_data);
?>