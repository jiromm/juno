<?php

namespace Config\Mapper;

use Config\Library\CommonTableGateway;
use Config\Constant\DBTable;
use Zend\Db\Sql\Select;

class ProductType extends CommonTableGateway {
	protected $tableName = DBTable::PRODUCT_TYPE;
}
