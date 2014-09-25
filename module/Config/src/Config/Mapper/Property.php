<?php

namespace Config\Mapper;

use Config\Library\CommonTableGateway;
use Config\Constant\DBTable;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;

class Property extends CommonTableGateway {
	protected $tableName = DBTable::PROPERTY;

	/**
	 * @param int $productTypeId
	 * @return \Config\Entity\Property[]|\ArrayObject
	 */
	public function getCompanyProperties($productTypeId) {
		$select = new Select($this->getTable());
		$select->columns(['id', 'name', 'property_type_id']);
		$select->join(
			DBTable::REL_PRODUCT_TYPE_PROPERTY,
			$this->getTable() . '.id = ' . DBTable::REL_PRODUCT_TYPE_PROPERTY . '.property_id',
			[]
		);
		$select->where([DBTable::REL_PRODUCT_TYPE_PROPERTY . '.product_type_id' => $productTypeId]);

		return $this->hydrate(
			$this->selectWith($select)
		);
	}
}
