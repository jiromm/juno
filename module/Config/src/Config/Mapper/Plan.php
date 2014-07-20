<?php

namespace Config\Mapper;

use Config\Library\CommonTableGateway;
use Config\Constant\DBTable;
use Zend\Db\Sql\Select;

class Plan extends CommonTableGateway {
	protected $tableName = DBTable::PLAN;
}
