<?php


namespace Config\Service;

use Config\Library\GeneralServiceBase;
use Config\Mapper\ProductType as ProductTypeMapper;
use Zend\Debug\Debug;

class ProductType extends GeneralServiceBase {
	public function getWarehouses($companyId) {
		/**
		 * @var ProductTypeMapper $mapper
		 */
		$mapper = $this->getServiceLocator()->get('ProductTypeMapper');

		return $mapper->fetchAll([
			'company_id' => $companyId,
		]);
	}
}
