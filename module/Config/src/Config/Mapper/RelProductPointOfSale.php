<?php

namespace Config\Mapper;

use Config\Library\CommonTableGateway;
use Config\Constant\DBTable;
use Zend\Db\Sql\Select;

class RelProductPointOfSale extends CommonTableGateway {
	protected $tableName = DBTable::REL_PRODUCT_POINT_OF_SALE;
}
