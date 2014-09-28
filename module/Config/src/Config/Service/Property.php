<?php


namespace Config\Service;

use Config\Library\GeneralServiceBase;
use Config\Mapper\Property as PropertyMapper;
use Config\Entity\PropertyFull as PropertyEntity;
use Config\Entity\ProductProperties as ProductPropertyEntity;
use Zend\Debug\Debug;

class Property extends GeneralServiceBase {
	/**
	 * @param int $companyId
	 * @return array
	 */
	public function getCompanyProperties($companyId) {
		/**
		 * @var PropertyMapper $mapper
		 * @var PropertyEntity[]|\ArrayObject $properties
		 */
		$mapper = $this->getServiceLocator()->get('PropertyMapper');

		$properties = $mapper->getCompanyProperties($companyId);
		$propertyList = [];

		if ($properties->count()) {
			foreach ($properties as $property) {
				if (!isset($propertyList[$property->getProductTypeId()])) {
					$propertyList[$property->getProductTypeId()] = [];
				}

				$propertyList[$property->getProductTypeId()][] = [
					'property_id' => $property->getId(),
					'name' => $property->getName(),
					'type' => $property->getPropertyType(),
				];
			}
		}

		return $propertyList;
	}

	public function getProductProperties($productTypeId) {
		/**
		 * @var PropertyMapper $mapper
		 * @var ProductPropertyEntity[]|\ArrayObject $properties
		 */
		$mapper = $this->getServiceLocator()->get('PropertyMapper');

		$properties = $mapper->getProductProperties($productTypeId);
		$propertyList = [];

		if ($properties->count()) {
			foreach ($properties as $property) {
				$propertyList[] = [
					'property_id' => $property->getId(),
					'name' => $property->getName(),
					'type' => $property->getType(),
				];
			}
		}

		return $propertyList;
	}
}
