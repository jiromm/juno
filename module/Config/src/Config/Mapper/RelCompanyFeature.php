<?php

namespace Config\Mapper;

use Config\Library\CommonTableGateway;
use Config\Constant\DBTable;
use Zend\Db\Sql\Select;

class RelCompanyFeature extends CommonTableGateway {
	protected $tableName = DBTable::REL_COMPANY_FEATURE;
}
