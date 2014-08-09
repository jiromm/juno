<?php

namespace Config\Mapper;

use Config\Library\CommonTableGateway;
use Config\Constant\DBTable;
use Zend\Db\Sql\Select;

class PointOfSale extends CommonTableGateway {
	protected $tableName = DBTable::POINT_OF_SALE;
}
