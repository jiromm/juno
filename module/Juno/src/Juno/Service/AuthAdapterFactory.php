<?php

namespace Juno\Service;

use Config\Auth\Adapter\BcryptDbAdapter as AuthAdapter;
use Config\Constant\DBTable as DBTableConfig;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable;
use Zend\Authentication\Storage\Session;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AuthAdapterFactory implements FactoryInterface {
	const TABLE_NAME = DBTableConfig::USER;
	const IDENTITY_COLUMN = "login";
	const CREDENTIAL_COLUMN = "password";

	public function createService(ServiceLocatorInterface $serviceLocator) {
		/**
		 * @var $dbAdapter \Zend\Db\Adapter\Adapter
		 */
		$dbAdapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');
		$authAdapter = new AuthAdapter($dbAdapter);

		$authAdapter
			->setTableName(self::TABLE_NAME)
			->setIdentityColumn(self::IDENTITY_COLUMN)
			->setCredentialColumn(self::CREDENTIAL_COLUMN);

		return $authAdapter;
	}
}
