<?php

namespace Config\Mapper;

use Config\Library\CommonTableGateway;
use Config\Constant\DBTable;
use Zend\Db\Sql\Select;

class RelUserPermission extends CommonTableGateway {
	protected $tableName = DBTable::REL_USER_PERMISSION;
}
