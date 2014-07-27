<?php

namespace Juno\Filter;

use Zend\InputFilter\InputFilter;

class UserFilter extends InputFilter {
	public function __construct() {
		$this->add([
			'name' => 'name',
			'required' => true,
		]);

		$this->add([
			'name' => 'email',
			'required' => true,
		]);

		$this->add([
			'name' => 'login',
			'required' => true,
		]);

		$this->add([
			'name' => 'password',
			'required' => false,
		]);

		$this->add([
			'name' => 'permission',
			'required' => false,
		]);
	}
}
