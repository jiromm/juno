<?php

namespace Config\Mapper;

use Config\Library\CommonTableGateway;
use Config\Constant\DBTable;
use Zend\Db\Sql\Select;
use Zend\Debug\Debug;

class Product extends CommonTableGateway {
	protected $tableName = DBTable::PRODUCT;

	public function getCompanyProducts($companyId) {
		$select = new Select($this->getTable());

		$select->join(
			DBTable::PRODUCT_TYPE,
			$this->getTable() . '.product_type_id = ' . DBTable::PRODUCT_TYPE . '.id',
			['product_type_name' => 'name'],
			Select::JOIN_LEFT
		);

		$select->where([
			DBTable::PRODUCT_TYPE . '.company_id' => $companyId,
		]);

		return $this->hydrate(
			$this->selectWith($select)
		);
	}
}
