<?php

function generateHash($password) {
	if (defined("CRYPT_BLOWFISH") && CRYPT_BLOWFISH) {
		$salt = '$2y$11$' . substr(md5(uniqid(rand(), true)), 0, 22);

		return crypt($password, $salt);
	}
}

function verify($password, $hashedPassword) {
	return crypt($password, $hashedPassword) == $hashedPassword;
}

$pass = 'trix';
$hash = generateHash($pass);

echo 'pass: ' . $pass . PHP_EOL;

echo 'hash: ' . $hash . ' (' . strlen($hash) . ')' . PHP_EOL;

echo 'is: ' . (verify($pass, $hash) ? 'true' : 'false') . PHP_EOL;

echo 'is (bad): ' . (verify('other', $hash) ? 'true' : 'false') . PHP_EOL;
