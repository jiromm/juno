<?php

namespace Config\Mapper;

use Config\Library\CommonTableGateway;
use Config\Constant\DBTable;
use Zend\Db\Sql\Select;

class RelSaleProduct extends CommonTableGateway {
	protected $tableName = DBTable::REL_SALE_PRODUCT;
}
