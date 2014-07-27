<?php

namespace Config\Library;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\ResultSet\ResultSetInterface;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;
use Zend\Db\TableGateway\Feature\RowGatewayFeature;
use Zend\Db\TableGateway\TableGateway;
use Zend\Debug\Debug;
use Zend\Stdlib\Hydrator\HydratorAwareInterface;
use Zend\Stdlib\Hydrator\Reflection;
use Zend\Db\ResultSet\ResultSet;

/**
 * Class CommonTableGateway
 * @package Config\Library
 */
class CommonTableGateway extends TableGateway {
	protected $idCol = 'id';
	protected $entityPrototype = null;
	protected $hydrator = null;
	protected $tableName = null;

	/**
	 * @param AdapterInterface $adapter
	 * @param EntityBase $entity
	 */
	public function __construct($adapter, $entity) {
		parent::__construct(
			$this->tableName,
			$adapter,
			new RowGatewayFeature($this->idCol)
		);

		$this->hydrator = new Reflection();
		$this->entityPrototype = $entity;
	}

	/**
	 * @param EntityBase $entity
	 */
	public function setEntity($entity) {
		$this->entityPrototype = $entity;
	}

	/**
	 * @param ResultSetInterface $results
	 * @param bool $one
	 * @return \Zend\Db\ResultSet\ResultSet
	 */
	protected function hydrate($results, $one = false) {
		$users = new HydratingResultSet(
			$this->hydrator,
			$this->entityPrototype
		);

		$hydro = $users->initialize($results->toArray());

		return $one ? $hydro->current() : $hydro;
	}

	/**
	 * @param Where|\Closure|string|array $where
	 * @return ResultSet
	 */
	public function fetchAll($where = null) {
		$select = $this->select($where);

		return $this->hydrate($select);
	}

	/**
	 * @param Where|\Closure|string|array $where
	 * @return ResultSet
	 */
	public function fetchOne($where = null) {
		$select = $this->select($where);

		return $this->hydrate($select, true);
	}

	/**
	 * @param EntityBase $entity
	 * @return int
	 */
	public function insert($entity) {
		$prepared = $this->cleanup(
			$this->hydrator->extract($entity)
		);

		return parent::insert($prepared);
	}

	/**
	 * @param EntityBase $entity
	 * @param Where|\Closure|string|array $where
	 * @return int
	 */
	public function update($entity, $where = null) {
		$prepared = $this->cleanup(
			$this->hydrator->extract($entity)
		);

		return parent::update($prepared, $where);
	}

	public function beginTransaction() {
		$this->getAdapter()->getDriver()->getConnection()->beginTransaction();
	}

	public function commit() {
		$this->getAdapter()->getDriver()->getConnection()->commit();
	}

	public function rollback() {
		$this->getAdapter()->getDriver()->getConnection()->rollback();
	}

	/**
	 * @param array $inputArray
	 * @return array
	 */
	protected function cleanup(array $inputArray) {
		$output = [];

		if (count($inputArray)) {
			foreach ($inputArray as $prop => $value) {
				if (is_null($value)) {
					continue;
				}

				$output[$prop] = $value;
			}
		}

		return $output;
	}
}
