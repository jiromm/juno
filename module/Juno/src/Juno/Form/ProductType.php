<?php

namespace Juno\Form;

use Juno\Filter\ProductTypeFilter;
use Zend\Authentication\Adapter\DbTable;
use Zend\Form\Form;
use Zend\ServiceManager\ServiceLocatorInterface;

class ProductType extends Form {
	/**
	 * @param ServiceLocatorInterface $sm
	 * @param string $action
	 */
	public function __construct($sm, $action) {
		parent::__construct('product-type');

		$this->setAttribute('action', $action);
		$this->setAttribute('method', 'post');
		$this->setAttribute('class', 'form-horizontal product-type-form');
		$this->setInputFilter(new ProductTypeFilter());

		$this->add([
			'name' => 'name',
			'attributes' => [
				'type' => 'text',
				'class' => 'form-control',
				'id' => 'name',
				'autofocus' => true,
				'required' => 'required',
			],
		]);

		$this->add([
			'name' => 'submit',
			'attributes' => [
				'type' => 'submit',
				'value' => 'Submit',
				'class' => 'btn btn-lg btn-primary btn-block',
			],
		]);
	}
}
