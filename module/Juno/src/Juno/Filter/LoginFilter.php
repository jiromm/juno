<?php

namespace Juno\Filter;

use Zend\InputFilter\InputFilter;

class LoginFilter extends InputFilter {
	public function __construct() {
		$this->add([
			'name' => 'login',
			'required' => true
		]);

		$this->add([
			'name' => 'password',
			'required' => true
		]);
	}
}
