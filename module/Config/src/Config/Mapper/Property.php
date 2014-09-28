<?php

namespace Config\Mapper;

use Config\Entity\ProductProperties;
use Config\Entity\PropertyFull;
use Config\Library\CommonTableGateway;
use Config\Constant\DBTable;
use Zend\Db\Sql\Select;

class Property extends CommonTableGateway {
	protected $tableName = DBTable::PROPERTY;

	/**
	 * @param int $productTypeId
	 * @return \Config\Entity\Property[]|\ArrayObject
	 */
	public function getProductTypeProperties($productTypeId) {
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

	/**
	 * @param int $productTypeId
	 * @return ProductProperties[]|\ArrayObject
	 */
	public function getProductProperties($productTypeId) {
		$this->setEntity(new ProductProperties());

		$select = new Select($this->getTable());
		$select->columns(['id', 'name']);
		$select->join(
			DBTable::REL_PRODUCT_TYPE_PROPERTY,
			$this->getTable() . '.id = ' . DBTable::REL_PRODUCT_TYPE_PROPERTY . '.property_id',
			[]
		);
		$select->join(
			DBTable::PROPERTY_TYPE,
			$this->getTable() . '.property_type_id = ' . DBTable::PROPERTY_TYPE . '.id',
			['type' => 'name']
		);
		$select->where([DBTable::REL_PRODUCT_TYPE_PROPERTY . '.product_type_id' => $productTypeId]);

		return $this->hydrate(
			$this->selectWith($select)
		);
	}

	/**
	 * @param int $companyId
	 * @return PropertyFull[]|\ArrayObject
	 */
	public function getCompanyProperties($companyId) {
		$this->setEntity(new PropertyFull());

		$select = new Select($this->getTable());
		$select->columns(['id', 'name']);
		$select->join(
			DBTable::REL_PRODUCT_TYPE_PROPERTY,
			$this->getTable() . '.id = ' . DBTable::REL_PRODUCT_TYPE_PROPERTY . '.property_id',
			[]
		);
		$select->join(
			DBTable::PROPERTY_TYPE,
			$this->getTable() . '.property_type_id = ' . DBTable::PROPERTY_TYPE . '.id',
			['property_type' => 'name']
		);
		$select->join(
			DBTable::PRODUCT_TYPE,
			DBTable::PRODUCT_TYPE . '.id = ' . DBTable::REL_PRODUCT_TYPE_PROPERTY . '.product_type_id',
			['product_type_id' => 'id']
		);
		$select->where([DBTable::PRODUCT_TYPE . '.company_id' => $companyId]);
		$select->order([DBTable::REL_PRODUCT_TYPE_PROPERTY . '.product_type_id DESC', $this->getTable() . '.id ASC']);

		return $this->hydrate(
			$this->selectWith($select)
		);
	}
}
