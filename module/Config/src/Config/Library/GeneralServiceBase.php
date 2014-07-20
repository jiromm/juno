<?php

namespace Config\Library;

use Zend\ServiceManager\ServiceLocatorInterface;

class GeneralServiceBase {
	/**
	 * @var ServiceLocatorInterface $sm
	 */
	protected $sm;

	public function __construct($sm) {
		$this->sm = $sm;
	}

	public function getServiceLocator() {
		$this->sm;
	}
}
