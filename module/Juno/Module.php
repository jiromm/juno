<?php

namespace Juno;

use Juno\View\Helper\BreadcrumbHelper;
use Juno\View\Helper\HasRoleHelper;
use Juno\View\Helper\IdentityHelper;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Application;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;
use Zend\ServiceManager\ServiceLocatorAwareInterface;

class Module {
    public function onBootstrap(MvcEvent $e) {
	    /**
	     * @var Application $app
	     * @var RouteMatch $routeMatch
	     * @var AuthenticationService $authService
	     */
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

	    $app = $e->getApplication();
	    $authService = $app->getServiceManager()->get('Juno\Service\AuthService');

	    $e->getApplication()->getEventManager()->getSharedManager()->attach('Zend\Mvc\Controller\AbstractActionController', 'dispatch', function (MvcEvent $e) use ($authService) {
		    $controller = $e->getTarget();
		    $routeMatch = $e->getRouteMatch();
		    $routeMatch->getMatchedRouteName();

		    if (!$authService->hasIdentity() && !in_array($routeMatch->getMatchedRouteName(), ['admin', 'login'])) {
			    $controller->plugin('redirect')->toRoute('login');
		    }
	    }, 100);
    }

    public function getConfig() {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig() {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

	public function getViewHelperConfig() {
		return array(
			'invokables' => array(
				'required' => 'Juno\View\Helper\RequiredHelper',
				'info' => 'Juno\View\Helper\InfoHelper',
			),
			'factories' => array(
				'ident' => function($sm) {
					/** @var ServiceLocatorAwareInterface $sm */
					return new IdentityHelper(
						$sm->getServiceLocator()->get('Juno\Service\AuthService')
					);
				},
				'breadcrumb' => function($sm) {
					/** @var ServiceLocatorAwareInterface $sm */
					return new BreadcrumbHelper(
						$sm->getServiceLocator()
					);
				},
			)
		);
	}
}
