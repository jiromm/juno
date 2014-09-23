<?php

namespace Config\Mapper;

use Config\Library\CommonTableGateway;
use Config\Constant\DBTable;
use Zend\Db\Sql\Select;

class PropertyType extends CommonTableGateway {
	protected $tableName = DBTable::PROPERTY_TYPE;

	/**
	 * @param int $companyId
	 * @return \Config\Entity\PropertyType[]|\ArrayObject
	 */
	public function getPropertyTypes($companyId) {
		$select = new Select($this->getTable());
		$select->where
			->equalTo('company_id', $companyId)
			->or
			->equalTo('is_verified', 1);

		return $this->hydrate(
			$this->selectWith($select)
		);
	}

	/**
	 * @param string $propertyName
	 * @return \Config\Entity\PropertyType|bool
	 */
	public function getByName($propertyName) {
		$select = new Select($this->getTable());
		$select->where(['name' => $propertyName]);

		return $this->hydrate(
			$this->selectWith($select),
			true
		);
	}
}
