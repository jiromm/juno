<?php

namespace Juno\Form;

use Config\Constant\Common;
use Config\Mapper\PropertyType as PropertyTypeMapper;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Authentication\Adapter\DbTable;
use Juno\Filter\ProductTypeFilter;
use Zend\Form\Form;

class ProductType extends Form {
	/**
	 * @param ServiceLocatorInterface $sm
	 * @param string $action
	 * @param int $companyId
	 */
	public function __construct($sm, $action, $companyId) {
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

		for ($i = 0; $i < Common::PROPERTY_TYPE_COUNT; $i++) {
			$this->add([
				'name' => "property[{$i}]",
				'attributes' => [
					'type' => 'text',
					'class' => 'property form-control',
				],
			]);

			$this->add([
				'name' => "property_type[{$i}]",
				'type' => 'Zend\Form\Element\Select',
				'attributes' => [
					'class' => 'selectize-create',
				],
				'options' => [
					'value_options' => $this->getPropertyTypes($sm, $companyId),
				],
			]);
		}

		$this->add([
			'name' => 'submit',
			'attributes' => [
				'type' => 'submit',
				'value' => 'Submit',
				'class' => 'btn btn-lg btn-primary btn-block',
			],
		]);
	}

	/**
	 * @param ServiceLocatorInterface $sm
	 * @param int $companyId
	 * @return array
	 */
	private function getPropertyTypes($sm, $companyId) {
		/**
		 * @var PropertyTypeMapper $mapper
		 */
		$mapper = $sm->get('PropertyTypeMapper');
		$propertyTypes = $mapper->getPropertyTypes($companyId);
		$propertyTypeList = [];

		if ($propertyTypes->count()) {
			foreach ($propertyTypes as $propertyType) {
				$propertyTypeList[$propertyType->getId()] = $propertyType->getName();
			}
		}

		return $propertyTypeList;
	}
}
