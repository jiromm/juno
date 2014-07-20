<?php

namespace Juno\Service;

use Config\Auth\Adapter\BcryptDbAdapter as AuthAdapter;
use Config\Auth\Adapter\BcryptDbAdapter;
use Config\Constant\DBTable as DBTableConfig;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable;
use Zend\Authentication\Storage\Session;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AuthServiceFactory implements FactoryInterface {
	public function createService(ServiceLocatorInterface $serviceLocator) {
		/**
		 * @var $authAdapter BcryptDbAdapter
		 */
		$authAdapter = $serviceLocator->get('Juno\Service\AuthAdapter');

		$service = new AuthenticationService(
			new Session(),
			$authAdapter
		);

		return $service;
	}
}
