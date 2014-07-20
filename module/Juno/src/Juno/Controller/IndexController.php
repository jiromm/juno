<?php

namespace Juno\Controller;

use Config\Service\Product;
use Zend\Authentication\AuthenticationService;
use Zend\Debug\Debug;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController {
    public function indexAction() {
	    /**
	     * @var Product $productService
	     */
	    $authService = $this->getServiceLocator()->get('Juno\Service\AuthService');
	    $productService = $this->getServiceLocator()->get('Config\Service\Product');

//	    Debug::dump($authService->getIdentity());
	    Debug::dump($productService->test());

        return new ViewModel();
    }
}
