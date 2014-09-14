<?php

namespace Juno\Filter;

use Zend\InputFilter\InputFilter;

class ProductTypeFilter extends InputFilter {
	public function __construct() {
		$this->add([
			'name' => 'name',
			'required' => true,
		]);
	}
}
