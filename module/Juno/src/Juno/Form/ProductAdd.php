<?php

namespace Juno\Form;

use Zend\Authentication\Adapter\DbTable;
use Zend\Debug\Debug;
use Zend\ServiceManager\ServiceLocatorInterface;
use Config\Service\Product as ProductService;

class ProductAdd extends Product {
	/**
	 * @param ServiceLocatorInterface $sm
	 * @param string $action
	 * @param int $companyId
	 */
	public function __construct($sm, $action, $companyId) {
		parent::__construct($sm, $action, $companyId);

		$this->add([
			'name' => 'direction',
			'type' => 'Zend\Form\Element\Select',
			'attributes' => [
				'class' => 'form-control',
				'id' => 'direction',
			],
			'options' => [
				'value_options' => $this->getCompanyDirections($sm, $companyId),
			],
		]);
	}

	/**
	 * @param ServiceLocatorInterface $sm
	 * @param int $companyId
	 * @return array
	 */
	private function getCompanyDirections($sm, $companyId) {
		/**
		 * @var ProductService $service
		 */
		$service = $sm->get('ProductService');

		return $service->getCompanyDirections($companyId);
	}
}
