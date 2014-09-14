<?php


namespace Config\Service;

use Config\Library\GeneralServiceBase;
use Config\Mapper\Product as ProductMapper;
use Zend\Debug\Debug;

class Product extends GeneralServiceBase {
	public function getProducts($companyId) {
		/**
		 * @var ProductMapper $mapper
		 */
		$mapper = $this->getServiceLocator()->get('ProductMapper');

		return $mapper->getCompanyProducts($companyId);
	}
}
