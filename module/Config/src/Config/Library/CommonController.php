<?php

namespace Config\Library;

use Zend\Authentication\AuthenticationService;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\Controller\PluginManager;
use Zend\Mvc\MvcEvent;

class CommonController extends AbstractActionController {
	private $auth;

	public function setPluginManager(PluginManager $plugins) {
		$this->getEventManager()->attach(
			MvcEvent::EVENT_DISPATCH, [
				$this,
				'onInit'
			], 100
		);

		return parent::setPluginManager($plugins);
	}

	public function onInit(MvcEvent $e) {
		return static::init();
	}

	public function init() {
		return false;
	}

	public function getIdentity() {
		return $this->getAuth()->getIdentity();
	}

	public function getCompanyId() {
		return $this->getAuth()->getIdentity()->company_id;
	}

	/**
	 * @return AuthenticationService
	 */
	private function getAuth() {
		/**
		 * @var AuthenticationService $auth
		 */
		if (is_null($this->auth)) {
			$this->auth = $this->getServiceLocator()->get('auth');
		}

		return $this->auth;
	}
}
