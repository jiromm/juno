<?php

namespace Juno\Form;

use Juno\Filter\HouseFilter;
use Zend\Authentication\Adapter\DbTable;
use Zend\Debug\Debug;
use Zend\Form\Form;
use Zend\ServiceManager\ServiceLocatorInterface;

class House extends Form {
	protected $categories = [];

	/**
	 * @param ServiceLocatorInterface $sm
	 * @param string $action
	 */
	public function __construct($sm, $action) {
		parent::__construct('house');

		$this->setAttribute('action', $action);
		$this->setAttribute('method', 'post');
		$this->setAttribute('class', 'form-horizontal house-form');
		$this->setInputFilter(new HouseFilter());

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

		$this->add(array(
			'name' => 'image',
			'type' => 'Zend\Form\Element\File',
			'attributes' => array(
				'class' => 'form-control',
				'accept' => 'image/jpg, image/jpeg',
			),
		));

		$this->add(array(
			'name' => 'icon',
			'type' => 'Zend\Form\Element\File',
			'attributes' => array(
				'class' => 'form-control',
				'accept' => 'image/jpg, image/jpeg',
			),
		));

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
