<?php

namespace Juno\Controller;

use Config\Auth\Adapter\BcryptDbAdapter;
use Config\Helper\Utils;
use Zend\Authentication\AuthenticationService;
use Zend\Debug\Debug;
use Zend\Form\View\Helper\Captcha\Dumb;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Storage\ArrayStorage;
use Zend\Session\Storage\SessionArrayStorage;
use Zend\Session\Storage\SessionStorage;
use Zend\View\Model\ViewModel;

class AuthController extends AbstractActionController {
	private $loginForm;

	/**
	 * @var AuthenticationService $authService
	 */
	private $authService;

	/**
	 * @var BcryptDbAdapter $authAdapter
	 */
	private $authAdapter;

	public function loginAction() {
		if ($this->authService->hasIdentity()) {
			return $this->redirect()->toUrl('/');
		}

		if (!$this->loginForm) {
			throw new \BadMethodCallException('Login form not yet set');
		}

		if (!$this->authService) {
			throw new \BadMethodCallException('Auth service not yet set');
		}

		if ($this->getRequest()->isPost()) {
			$this->loginForm->setData($this->getRequest()->getPost());

			if ($this->loginForm->isValid()) {
				$data = $this->loginForm->getData();

				$this->authService->getAdapter()->setIdentity($data['login']);
				$this->authService->getAdapter()->setCredential($data['password']);

				$authResult = $this->authService->authenticate();

				if (!$authResult->isValid()) {
					return new ViewModel([
						'form' => $this->loginForm,
						'loginError' => true,
					]);
				} else {
					$auth = new AuthenticationService();
					$storage = $auth->getStorage();

					Debug::dump($this->authService->getAdapter());

					$storage->write($this->authService->getAdapter()->getResultRowObject(
						null,
						'password'
					));

					return $this->redirect()->toUrl('/login');
				}
			}
		}

		return new ViewModel([
			'form' => $this->loginForm,
		]);
	}

	public function logoutAction() {
		if ($this->authService->hasIdentity()) {
			$this->authService->clearIdentity();
		}

		return $this->redirect()->toUrl('/login');
	}

	public function setLoginForm($loginForm) {
		$this->loginForm = $loginForm;
	}

	public function getLoginForm() {
		return $this->loginForm;
	}

	public function setAuthService($authService) {
		$this->authService = $authService;
	}

	public function getAuthService() {
		return $this->authService;
	}
}
