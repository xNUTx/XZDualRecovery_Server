<?php
function DecryptAndValidateData($data){
	if (openssl_private_decrypt(base64_decode($data), $decrypted, file_get_contents('privatekey.pem'))){
		return $decrypted;
	}
}
$encoded_data='wwDyvu0xwuFchlQ2hXwDtKF95IYveoi7aHi3nPdjnb93KGrp8p8aL+ZwTOkfOhfRUIxRHuLQviPzf9JRjjPFQW+HY2ZcJBYf71UxzRt+O2z83xsjIyG6oytJ6sM/CtVuwmFATMKOeX93RmD7vA69/Ww2VznN5Jf0fuh7gIg0Pep2b2HC7X3Lbo5Um1e0aFMYwOeuNx6s3QOh9uOXSeZCVjQ5qGEKS/CLUbNgavOqg8AVzEedY8RPMwChzxfi/WHnbDY7NJqB+KRkOg+LMznVUH6YKB029F+g1Jt6bP0LiDFHGDjoIvaI+C0lM/TO5Y/uMdRyEw9ZHqzSwhuOK5bR9w==';
echo "Decoded: " . DecryptAndValidateData($encoded_data);
?>