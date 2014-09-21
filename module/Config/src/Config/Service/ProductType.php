<?php


namespace Config\Service;

use Config\Library\GeneralServiceBase;
use Config\Mapper\ProductType as ProductTypeMapper;
use Config\Entity\ProductType as ProductTypeEntity;
use Zend\Debug\Debug;

class ProductType extends GeneralServiceBase {
	/**
	 * @param $companyId
	 * @return ProductTypeEntity[]|\ArrayObject
	 */
	public function getCompanyProductTypes($companyId) {
		/**
		 * @var ProductTypeMapper $mapper
		 */
		$mapper = $this->getServiceLocator()->get('ProductTypeMapper');

		return $mapper->fetchAll([
			'company_id' => $companyId,
		]);
	}
}
