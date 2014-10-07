<?php


namespace Config\Service;

use Config\Helper\Utils;
use Config\Library\GeneralServiceBase;
use Config\Entity\Product as ProductEntity;
use Config\Mapper\Product as ProductMapper;
use Config\Mapper\PointOfSale as PointOfSaleMapper;
use Zend\Debug\Debug;

class Product extends GeneralServiceBase {
	/**
	 * @param int $companyId
	 * @return ProductEntity[]|\ArrayObject
	 */
	public function getProducts($companyId) {
		/**
		 * @var ProductMapper $mapper
		 */
		$mapper = $this->getServiceLocator()->get('ProductMapper');

		return $mapper->getCompanyProducts($companyId);
	}

	public function getCompanyProducts($companyId) {
		$productEntities = $this->getProducts($companyId);
		$productList = [];

		if ($productEntities->count()) {
			foreach ($productEntities as $productEntity) {
				$productList[$productEntity->getId()] = $productEntity->getName();
			}
		}

		return $productList;
	}

	public function getCompanyDirections($companyId) {
		/**
		 * @var PointOfSaleMapper $mapper
		 */
		$mapper = $this->getServiceLocator()->get('PointOfSaleMapper');
		$directions = $mapper->getCompanyDirections($companyId);
		$directionList = [];

		if ($directions->count()) {
			$groupCounter = 0;

			foreach ($directions as $direction) {
				if (!isset($directionList[$groupCounter])) {
					$directionList[$groupCounter] = [
						'label' => Utils::asDisplayName($direction->getDirection()),
						'options' => [],
					];
				}

				if ($directionList[$groupCounter]['label'] != Utils::asDisplayName($direction->getDirection())) {
					$directionList[++$groupCounter] = [
						'label' => Utils::asDisplayName($direction->getDirection()),
						'options' => [],
					];
				}

				$directionList[$groupCounter]['options'][$direction->getDirection() . '-' . $direction->getId()] = $direction->getName();
			}
		}

		return $directionList;
	}
}
