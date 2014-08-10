<?php

namespace Juno\Form;

use Juno\Filter\WarehouseFilter;
use Zend\Authentication\Adapter\DbTable;
use Zend\Form\Form;
use Zend\ServiceManager\ServiceLocatorInterface;

class Warehouse extends Form {
	/**
	 * @param ServiceLocatorInterface $sm
	 * @param string $action
	 */
	public function __construct($sm, $action) {
		parent::__construct('house');

		$this->setAttribute('action', $action);
		$this->setAttribute('method', 'post');
		$this->setAttribute('class', 'form-horizontal warehouse-form');
		$this->setInputFilter(new WarehouseFilter());

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
			'name' => 'address',
			'attributes' => [
				'type' => 'text',
				'class' => 'form-control',
				'id' => 'address',
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
