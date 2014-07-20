<?php

use Zend\Mvc\Router\Http\Literal;
use Zend\Mvc\Router\Http\Segment;

return array(
    'router' => array(
        'routes' => array(
	        'admin' => array(
		        'type' => 'Literal',
		        'options' => array(
			        'route' => '/admin',
			        'defaults' => array(
				        'controller' => 'Admin\Controller\Index',
				        'action'     => 'index',
			        ),
		        ),
		        'may_terminate' => true,
		        'child_routes' => array(
			        'login' => array(
				        'type' => 'Literal',
				        'options' => array(
					        'route' => '/login',
					        'defaults' => array(
						        'controller' => 'Admin\Controller\Auth',
						        'action'     => 'login',
					        ),
				        ),
			        ),
			        'logout' => array(
				        'type' => 'Literal',
				        'options' => array(
					        'route'    => '/logout',
					        'defaults' => array(
						        'controller' => 'Admin\Controller\Auth',
						        'action'     => 'logout',
					        ),
				        ),
			        ),
		        )
	        ),
        ),
    ),
	'service_manager' => array(
		'factories' => array(),
	),
    'controllers' => array(
        'invokables' => array(
	        'Admin\Controller\Auth' => 'Admin\Controller\AuthController',
	        'Admin\Controller\Index' => 'Admin\Controller\IndexController',
        ),
    ),
	'view_manager' => array(
		'template_path_stack' => array(
			__DIR__ . '/../view',
		),
	),
);
