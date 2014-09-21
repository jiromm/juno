<?php

namespace Config\Mapper;

use Config\Library\CommonTableGateway;
use Config\Constant\DBTable;
use Zend\Db\Sql\Select;

class User extends CommonTableGateway {
	protected $tableName = DBTable::USER;

	public function getCompanyUsers($companyId) {
		$select = new Select($this->getTable());
		$select->where([
			'is_removed' => 0,
			'company_id' => $companyId,
		]);
		$select->order(['is_active DESC', 'is_primary DESC']);

		return $this->hydrate(
			$this->selectWith($select)
		);
	}
}
