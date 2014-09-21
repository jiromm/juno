<?php

namespace Config\Mapper;

use Config\Entity\ProductDirection;
use Config\Library\CommonTableGateway;
use Config\Constant\DBTable;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;
use Zend\Debug\Debug;

class PointOfSale extends CommonTableGateway {
	protected $tableName = DBTable::POINT_OF_SALE;

	/**
	 * @param int $companyId
	 * @return \ArrayObject|ProductDirection[]
	 */
	public function getCompanyDirections($companyId) {
		$this->setEntity(new ProductDirection());

		$statement = ['company_id' => $companyId];

		$selectPOS = new Select($this->getTable());
		$selectPOS->columns([
			'direction' => new Expression('"' . $this->getTable() . '"'),
			'id',
			'name',
		]);
		$selectPOS->where($statement);

		$selectWH = new Select(DBTable::WAREHOUSE);
		$selectWH->columns([
			'direction' => new Expression('"' . DBTable::WAREHOUSE . '"'),
			'id',
			'name',
		]);
		$selectWH->where($statement);

		$selectPOS->combine($selectWH);

		return $this->hydrate(
			$this->selectWith($selectPOS)
		);
	}
}
