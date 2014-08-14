<?php


namespace Config\Service;

use Config\Library\GeneralServiceBase;
use Config\Mapper\PointOfSale as PointOfSaleMapper;
use Zend\Debug\Debug;

class PointOfSale extends GeneralServiceBase {
	public function getPointsOfSale() {
		/**
		 * @var PointOfSaleMapper $mapper
		 */
		$mapper = $this->getServiceLocator()->get('PointOfSaleMapper');
		return $mapper->fetchAll();
	}
}
