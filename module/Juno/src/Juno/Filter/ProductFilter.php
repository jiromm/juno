<?php

namespace Juno\Filter;

use Zend\InputFilter\InputFilter;

class ProductFilter extends InputFilter {
	public function __construct() {
		$this->add([
			'name' => 'name',
			'required' => true,
		]);

		$this->add([
			'name' => 'quantity',
			'required' => true,
		]);

		$this->add([
			'name' => 'description',
			'required' => false,
		]);

		$this->add([
			'name' => 'product_type_id',
			'required' => false,
		]);
	}
}
