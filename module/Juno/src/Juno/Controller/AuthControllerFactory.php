<?php

namespace Juno\Controller;

use Juno\Form\Login;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AuthControllerFactory implements FactoryInterface {
	public function createService(ServiceLocatorInterface $serviceLocator) {
		$controller = new AuthController();
		$controller->setLoginForm(new Login());
		$controller->setAuthService(
			$serviceLocator->getServiceLocator()->get('Juno\Service\AuthService')
		);

		return $controller;
	}
}
