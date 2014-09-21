<?php

namespace Config\Helper;

class Utils {
	/**
	 * @param string $date
	 * @return bool|string
	 */
	public static function reformatDate($date) {
		return date('d M, Y', strtotime($date));
	}

	/**
	 * @param string $date
	 * @return bool|string
	 */
	public static function deformatDate($date) {
		return date('Y-m-d', strtotime($date));
	}

	/**
	 * @param string $password
	 *
	 * @return string
	 * @throws \Exception
	 */
	public static function generateHash($password) {
		if (defined("CRYPT_BLOWFISH") && CRYPT_BLOWFISH) {
			$salt = '$2y$11$' . substr(md5(uniqid(rand(), true)), 0, 22);

			return crypt($password, $salt);
		} else {
			throw new \Exception('CRYPT_BLOWFISH not defined');
		}
	}

	/**
	 * @param string $password
	 * @param string $hashedPassword
	 *
	 * @return bool
	 */
	public static function verify($password, $hashedPassword) {
		return crypt($password, $hashedPassword) == $hashedPassword;
	}

	public static function asDisplayName($string) {
		return ucfirst(
			str_replace('_', ' ', $string)
		);
	}
}
