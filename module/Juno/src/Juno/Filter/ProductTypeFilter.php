<?php

namespace Juno\Filter;

use Config\Constant\Common;
use Zend\InputFilter\InputFilter;

class ProductTypeFilter extends InputFilter {
	public function __construct() {
		$this->add([
			'name' => 'name',
			'required' => true,
		]);

		for ($i = 0; $i < Common::PROPERTY_TYPE_COUNT; $i++) {
			$this->add([
				'name' => "property[{$i}]",
				'required' => false,
			]);

			$this->add([
				'name' => "property_type[{$i}]",
				'required' => false,
			]);
		}
	}
}
