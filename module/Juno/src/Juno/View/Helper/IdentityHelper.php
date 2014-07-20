<?php

namespace Juno\View\Helper;

use Zend\Authentication\AuthenticationService;
use Zend\Form\View\Helper\AbstractHelper;

class IdentityHelper extends AbstractHelper {
	/**
	 * @var AuthenticationService $authService
	 */
	protected $authService;

	public function __construct($authService) {
		$this->authService = $authService;
	}

	public function __invoke() {
		return $this->authService->hasIdentity() ? $this->authService->getIdentity() : false;
	}
}
