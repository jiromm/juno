<?php

namespace Config\Mapper;

use Config\Library\CommonTableGateway;
use Config\Constant\DBTable;
use Zend\Db\Sql\Select;

class RelProductTypeProperty extends CommonTableGateway {
	protected $tableName = DBTable::REL_PRODUCT_TYPE_PROPERTY;
}
