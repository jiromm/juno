<?php

namespace Admin\Service;

use Config\Constant\DBTable as DBTableConfig;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable;
use Zend\Authentication\Storage\Session;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AuthServiceFactory implements FactoryInterface {
	const TABLE_NAME = DBTableConfig::USER_ADMIN;
	const IDENTITY_COLUMN = "login";
	const CREDENTIAL_COLUMN = "password";

	public function createService(ServiceLocatorInterface $serviceLocator) {
		/**
		 * @var $dbAdapter \Zend\Db\Adapter\Adapter
		 */
		$dbAdapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');

		$service = new AuthenticationService(
			new Session(),
			new DbTable(
				$dbAdapter,
				self::TABLE_NAME,
				self::IDENTITY_COLUMN,
				self::CREDENTIAL_COLUMN,
				'sha1(?)'
			)
		);

		return $service;
	}
}
