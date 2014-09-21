<?php

namespace Config\Mapper;

use Config\Entity\ProductFull;
use Config\Library\CommonTableGateway;
use Config\Constant\DBTable;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;
use Zend\Debug\Debug;

class Product extends CommonTableGateway {
	protected $tableName = DBTable::PRODUCT;

	/**
	select
	product.id,
	product.quantity,
	product.name as name,
	product_type.name as type,
	warehouse.name as warehouse,
	point_of_sale.name as point_of_sale,
	IFNULL(warehouse.name, point_of_sale.name) as direction
	from product
	left join product_type on product_type.id = product.product_type_id
	 *
	left join rel_product_point_of_sale on rel_product_point_of_sale.product_id = product.id
	left join rel_product_warehouse on rel_product_warehouse.product_id = product.id
	 *
	left join warehouse on warehouse.id = rel_product_warehouse.warehouse_id
	left join point_of_sale on point_of_sale.id = rel_product_point_of_sale.point_of_sale_id
	where product_type.company_id = 1
	;
	 */

	public function getCompanyProducts($companyId) {
		$this->setEntity(new ProductFull());

		$select = new Select($this->getTable());
		$select->columns([
			'id',
			'quantity',
			'name',
			'direction' => new Expression('IFNULL(' . DBTable::WAREHOUSE . '.name, ' . DBTable::POINT_OF_SALE . '.name)'),
		]);
		$select->join(
			DBTable::PRODUCT_TYPE,
			$this->getTable() . '.product_type_id = ' . DBTable::PRODUCT_TYPE . '.id',
			['type' => 'name'],
			Select::JOIN_LEFT
		);

		$select->join(
			DBTable::REL_PRODUCT_POINT_OF_SALE,
			$this->getTable() . '.id = ' . DBTable::REL_PRODUCT_POINT_OF_SALE . '.product_id',
			[],
			Select::JOIN_LEFT
		);

		$select->join(
			DBTable::REL_PRODUCT_WAREHOUSE,
			$this->getTable() . '.id = ' . DBTable::REL_PRODUCT_WAREHOUSE . '.product_id',
			[],
			Select::JOIN_LEFT
		);

		$select->join(
			DBTable::WAREHOUSE,
			DBTable::REL_PRODUCT_WAREHOUSE . '.warehouse_id = ' . DBTable::WAREHOUSE . '.id',
			[],
			Select::JOIN_LEFT
		);

		$select->join(
			DBTable::POINT_OF_SALE,
			DBTable::REL_PRODUCT_POINT_OF_SALE . '.point_of_sale_id = ' . DBTable::POINT_OF_SALE . '.id',
			[],
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
