<?php

namespace Config\Service;

use Config\Library\GeneralServiceBase;
use Config\Mapper\User as UserMapper;
use Config\Entity\User as UserEntity;
use Zend\Debug\Debug;

class User extends GeneralServiceBase {
	/**
	 * @param int $companyId
	 * @return UserEntity[]|\ArrayObject
	 */
	public function getCompanyUsers($companyId) {
		/**
		 * @var UserMapper $userMapper
		 */
		$userMapper = $this->getServiceLocator()->get('UserMapper');

		return $userMapper->getCompanyUsers($companyId);
	}

	/**
	 * @return UserEntity[]|\ArrayObject
	 */
	public function getAllActiveUsers() {
		/**
		 * @var UserMapper $userMapper
		 */
		$userMapper = $this->getServiceLocator()->get('UserMapper');

		return $userMapper->fetchAll(['is_active' => 1]);
	}

	/**
	 * @return UserEntity[]|\ArrayObject
	 */
	public function getAllInactiveUsers() {
		/**
		 * @var UserMapper $userMapper
		 */
		$userMapper = $this->getServiceLocator()->get('UserMapper');

		return $userMapper->fetchAll(['is_active' => 0]);
	}

	/**
	 * @param int $id
	 * @return UserEntity|bool
	 */
	public function getUser($id) {
		/**
		 * @var UserMapper $userMapper
		 */
		$userMapper = $this->getServiceLocator()->get('UserMapper');

		return $userMapper->fetchAll(['id' => $id]);
	}
}
