<?php

namespace Juno\Form;

use Config\Service\ProductType as ProductTypeService;
use Juno\Filter\ProductFilter;
use Zend\Authentication\Adapter\DbTable;
use Zend\Form\Form;
use Zend\ServiceManager\ServiceLocatorInterface;

class Product extends Form {
	/**
	 * @param ServiceLocatorInterface $sm
	 * @param string $action
	 * @param int $companyId
	 */
	public function __construct($sm, $action, $companyId) {
		parent::__construct('product');

		$this->setAttribute('action', $action);
		$this->setAttribute('method', 'post');
		$this->setAttribute('class', 'form-horizontal product-form');
		$this->setInputFilter(new ProductFilter());

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
			'name' => 'quantity',
			'attributes' => [
				'type' => 'text',
				'class' => 'form-control',
				'id' => 'quantity',
			],
		]);
		$this->add([
			'name' => 'description',
			'type' => 'Zend\Form\Element\Textarea',
			'attributes' => [
				'class' => 'form-control',
				'id' => 'description',
			],
		]);
		$this->add([
			'name' => 'product_type_id',
			'type' => 'Zend\Form\Element\Select',
			'attributes' => [
				'class' => 'form-control',
				'id' => 'product_type_id',
			],
			'options' => [
				'value_options' => $this->getDirections($sm, $companyId),
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

	/**
	 * @param ServiceLocatorInterface $sm
	 * @param int $companyId
	 * @return array
	 */
	public function getDirections($sm, $companyId) {
		/**
		 * @var ProductTypeService $productTypeservice
		 */
		$productTypeservice = $sm->get('ProductTypeService');
		$productTypes = $productTypeservice->getCompanyProductTypes($companyId);
		$result = [];

		if ($productTypes->count()) {
			foreach ($productTypes as $productType) {
				$result[$productType->getId()] = $productType->getName();
			}
		}

		return $result;
	}
}
