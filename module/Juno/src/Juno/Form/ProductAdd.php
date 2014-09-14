<?php

namespace Juno\Form;

use Zend\Authentication\Adapter\DbTable;
use Zend\ServiceManager\ServiceLocatorInterface;

class ProductAdd extends Product {
	/**
	 * @param ServiceLocatorInterface $sm
	 * @param string $action
	 */
	public function __construct($sm, $action) {
		parent::__construct($sm, $action);

		$this->add([
			'name' => 'direction',
			'type' => 'Zend\Form\Element\Select',
			'attributes' => [
				'class' => 'form-control',
				'id' => 'direction',
			],
			'options' => [
				'value_options' => ['dir1', 'dir2', 'dir3'],
			],
		]);
	}
}
