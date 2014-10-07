<?php

namespace Juno\Filter;

use Zend\InputFilter\InputFilter;

class SaleFilter extends InputFilter {
	public function __construct() {
		$this->add([
			'name' => 'direction',
			'required' => false,
		]);

		$this->add([
			'name' => 'product_id[]',
			'required' => false,
		]);

		$this->add([
			'name' => 'quantity[]',
			'required' => false,
		]);
	}
}
