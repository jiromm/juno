<?php

namespace Admin\Controller;

use Admin\Form\Login;
use Zend\Debug\Debug;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;

class AuthController extends AbstractActionController {
	private $loginForm;

	public function loginAction() {
		/**
		 * @var Container|\ArrayObject $session
		 * @var Request $request
		 */
		$session = new Container('admin');
		$request = $this->getRequest();

		if ($session->is) {
			return $this->redirect()->toUrl('/admin');
		}

		$form = new Login();

		if ($request->isPost()) {
			$form->setData($request->getPost());

			if ($form->isValid()) {
				$data = $form->getData();

				// select by $data['login'] and check by $data['password']

				if (!$authResult->isValid()) {
					return new ViewModel([
						'form' => $this->loginForm,
						'loginError' => true,
					]);
				} else {
					return $this->redirect()->toUrl('/admin/login');
				}
			}
		}

		return new ViewModel([
			'form' => $form,
		]);
	}

	public function logoutAction() {
		if ($this->authService->hasIdentity()) {
			$this->authService->clearIdentity();
		}

		return $this->redirect()->toUrl('/admin/login');
	}
}
