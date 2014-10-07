<?php

namespace Juno\Form;

use Juno\Filter\SaleFilter;
use Config\Service\Product as ProductService;
use Zend\Form\Form;
use Zend\ServiceManager\ServiceLocatorInterface;

class Sale extends Form {
	/**
	 * @param ServiceLocatorInterface $sm
	 * @param string $action
	 * @param int $companyId
	 */
	public function __construct($sm, $action, $companyId) {
		parent::__construct('sale');

		$this->setAttribute('action', $action);
		$this->setAttribute('method', 'post');
		$this->setAttribute('class', 'form-horizontal sale-form');
		$this->setInputFilter(new SaleFilter());

		$this->add([
			'name' => "direction",
			'type' => 'Zend\Form\Element\Select',
			'attributes' => [
				'class' => 'form-control',
				'id' => 'direction',
			],
			'options' => [
				'value_options' => $this->getDirections($sm, $companyId),
			],
		]);
		$this->add([
			'name' => "product_id[]",
			'type' => 'Zend\Form\Element\Select',
			'attributes' => [
				'class' => 'form-control',
			],
			'options' => [
				'value_options' => $this->getProducts($sm, $companyId),
			],
		]);
		$this->add([
			'name' => 'quantity[]',
			'attributes' => [
				'type' => 'number',
				'min' => 1,
				'class' => 'form-control',
				'id' => 'name',
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
	private function getDirections($sm, $companyId) {
		/**
		 * @var ProductService $productService
		 */
		$productService = $sm->get('ProductService');

		return $productService->getCompanyDirections($companyId);
	}

	/**
	 * @param ServiceLocatorInterface $sm
	 * @param int $companyId
	 * @return array
	 */
	private function getProducts($sm, $companyId) {
		/**
		 * @var ProductService $productService
		 */
		$productService = $sm->get('ProductService');

		return $productService->getCompanyProducts($companyId);
	}
}
