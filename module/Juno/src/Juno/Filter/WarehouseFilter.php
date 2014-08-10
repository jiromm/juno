<?php

namespace Juno\Filter;

use Zend\InputFilter\InputFilter;

class WarehouseFilter extends InputFilter {
	public function __construct() {
		$this->add([
			'name' => 'name',
			'required' => true,
		]);

		$this->add([
			'name' => 'address',
			'required' => false,
		]);
	}
}
