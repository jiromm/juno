<?php


namespace Config\Service;

use Config\Library\GeneralServiceBase;
use Config\Mapper\Warehouse as WarehouseMapper;
use Zend\Debug\Debug;

class Warehouse extends GeneralServiceBase {
	public function getWarehouses($companyId) {
		/**
		 * @var WarehouseMapper $mapper
		 */
		$mapper = $this->getServiceLocator()->get('WarehouseMapper');

		return $mapper->fetchAll([
			'company_id' => $companyId,
		]);
	}
}
