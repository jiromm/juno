<?php

namespace Config\Mapper;

use Config\Library\CommonTableGateway;
use Config\Constant\DBTable;
use Zend\Db\Sql\Select;

class Feature extends CommonTableGateway {
	protected $tableName = DBTable::FEATURE;
}
