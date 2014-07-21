<?php

use Zend\Mvc\Router\Http\Literal;
use Zend\Mvc\Router\Http\Segment;

return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Juno\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
	        'user' => array(
		        'type' => 'Literal',
		        'options' => array(
			        'route'    => '/user',
			        'defaults' => array(
				        'controller' => 'Juno\Controller\User',
				        'action'     => 'index',
			        ),
		        ),
		        'may_terminate' => true,
		        'child_routes' => array(
			        'add' => array(
				        'type'    => 'Literal',
				        'options' => array(
					        'route'    => '/add',
					        'defaults' => array(
						        'controller' => 'Juno\Controller\Users',
						        'action'     => 'add',
					        ),
				        ),
			        ),
			        'edit' => array(
				        'type'    => 'Segment',
				        'options' => array(
					        'route'    => '/edit/[:id]',
					        'defaults' => array(
						        'controller' => 'Juno\Controller\Users',
						        'action'     => 'edit',
					        ),
					        'constraints' => array(
						        'id' => '[1-9][0-9]*',
					        ),
				        ),
			        ),
			        'suspend' => array(
				        'type'    => 'Segment',
				        'options' => array(
					        'route'    => '/suspend/[:id]',
					        'defaults' => array(
						        'controller' => 'Juno\Controller\Users',
						        'action'     => 'suspend',
					        ),
					        'constraints' => array(
						        'id' => '[1-9][0-9]*',
					        ),
				        ),
			        ),
			        'delete' => array(
				        'type'    => 'Segment',
				        'options' => array(
					        'route'    => '/delete/[:id]',
					        'defaults' => array(
						        'controller' => 'Juno\Controller\Users',
						        'action'     => 'delete',
					        ),
					        'constraints' => array(
						        'id' => '[1-9][0-9]*',
					        ),
				        ),
			        ),
		        ),
	        ),
	        'login' => array(
		        'type' => 'Segment',
		        'options' => array(
			        'route'    => '/login',
			        'defaults' => array(
				        'controller' => 'Juno\Controller\Auth',
				        'action'     => 'login',
			        ),
		        ),
	        ),
	        'logout' => array(
		        'type' => 'Segment',
		        'options' => array(
			        'route'    => '/logout',
			        'defaults' => array(
				        'controller' => 'Juno\Controller\Auth',
				        'action'     => 'logout',
			        ),
		        ),
	        ),
        ),
    ),
    'service_manager' => array(
	    'factories' => array(
		    'Juno\Service\AuthService' => 'Juno\Service\AuthServiceFactory',
		    'Juno\Service\AuthAdapter' => 'Juno\Service\AuthAdapterFactory',
	    ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Juno\Controller\Index' => 'Juno\Controller\IndexController',
            'Juno\Controller\User' => 'Juno\Controller\UserController',
        ),
	    'factories' => array(
		    'Juno\Controller\Auth' => 'Juno\Controller\AuthControllerFactory',
	    ),
    ),
    'view_manager' => array(
	    'template_path_stack' => array(
		    __DIR__ . '/../view',
	    ),
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
    ),
);