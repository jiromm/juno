<?php

use Zend\Mvc\Router\Http\Literal;
use Zend\Mvc\Router\Http\Segment;

return array(
	'router' => array(
		'routes' => array(
			// The following is a route to simplify getting started creating
			// new controllers and actions without needing to create a new
			// module. Simply drop new controllers in, and you can access them
			// using the path /application/:controller/:action
			'application' => array(
				'type'    => 'Literal',
				'options' => array(
					'route'    => '/Juno', /** application */
					'defaults' => array(
						'__NAMESPACE__' => 'Juno\Controller',
						'controller'    => 'Index',
						'action'        => 'index',
					),
				),
				'may_terminate' => true,
				'child_routes' => array(
					'default' => array(
						'type'    => 'Segment',
						'options' => array(
							'route'    => '/[:controller[/:action]]',
							'constraints' => array(
								'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
								'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
							),
							'defaults' => array(
							),
						),
					),
				),
			),
		),
	),
	'service_manager' => array(
		'abstract_factories' => array(
			'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
			'Zend\Log\LoggerAbstractServiceFactory',
		),
		'aliases' => array(
			'translator' => 'MvcTranslator',
			'adapter' => 'Zend\Db\Adapter\Adapter',

			// Mappers
			'RelProductTypePropertyMapper' => 'Config\Mapper\RelProductTypeProperty',
			'RelProductPointOfSaleMapper' => 'Config\Mapper\RelProductPointOfSale',
			'RelProductWarehouseMapper' => 'Config\Mapper\RelProductWarehouse',
			'RelCompanyFeatureMapper' => 'Config\Mapper\RelCompanyFeature',
			'RelUserPermissionMapper' => 'Config\Mapper\RelUserPermission',
			'RelSaleProductMapper' => 'Config\Mapper\RelSaleProduct',
			'PropertyTypeMapper' => 'Config\Mapper\PropertyType',
			'ProductTypeMapper' => 'Config\Mapper\ProductType',
			'PointOfSaleMapper' => 'Config\Mapper\PointOfSale',
			'PermissionMapper' => 'Config\Mapper\Permission',
			'UserAdminMapper' => 'Config\Mapper\UserAdmin',
			'WarehouseMapper' => 'Config\Mapper\Warehouse',
			'PropertyMapper' => 'Config\Mapper\Property',
			'ProductMapper' => 'Config\Mapper\Product',
			'CompanyMapper' => 'Config\Mapper\Company',
			'FeatureMapper' => 'Config\Mapper\Feature',
			'PlanMapper' => 'Config\Mapper\Plan',
			'SaleMapper' => 'Config\Mapper\Sale',
			'UserMapper' => 'Config\Mapper\User',

			// Services
			'ProductTypeService' => 'Config\Service\ProductType',
			'PointOfSaleService' => 'Config\Service\PointOfSale',
			'WarehouseService' => 'Config\Service\Warehouse',
			'PropertyService' => 'Config\Service\Property',
			'ProductService' => 'Config\Service\Product',
			'UserService' => 'Config\Service\User',
		),
		'factories' => array(
			'Zend\Db\Adapter\Adapter' => function($sm) {
				$config = $sm->get('Config');
				$dbParams = $config['dbParams'];

				return new Zend\Db\Adapter\Adapter([
					'driver' => 'pdo',
					'dsn' => 'mysql:dbname=' . $dbParams['database'] . ';host=' . $dbParams['hostname'],
					'database' => $dbParams['database'],
					'username' => $dbParams['username'],
					'password' => $dbParams['password'],
					'hostname' => $dbParams['hostname'],
				]);
			},

			// Service
			'Config\Service\Property' => function($sm) {
				return new \Config\Service\Property($sm);
			},
			'Config\Service\ProductType' => function($sm) {
				return new \Config\Service\ProductType($sm);
			},
			'Config\Service\Warehouse' => function($sm) {
				return new \Config\Service\Warehouse($sm);
			},
			'Config\Service\PointOfSale' => function($sm) {
				return new \Config\Service\PointOfSale($sm);
			},
			'Config\Service\Product' => function($sm) {
				return new \Config\Service\Product($sm);
			},
			'Config\Service\User' => function($sm) {
				return new \Config\Service\User($sm);
			},

			// Mappers
			'Config\Mapper\RelSaleProduct' => function($sm) {
				return new \Config\Mapper\RelSaleProduct(
					$sm->get('adapter'),
					new \Config\Entity\RelSaleProduct()
				);
			},
			'Config\Mapper\Sale' => function($sm) {
				return new \Config\Mapper\Sale(
					$sm->get('adapter'),
					new \Config\Entity\Sale()
				);
			},
			'Config\Mapper\Warehouse' => function($sm) {
				return new \Config\Mapper\Warehouse(
					$sm->get('adapter'),
					new \Config\Entity\Warehouse()
				);
			},
			'Config\Mapper\RelProductWarehouse' => function($sm) {
				return new \Config\Mapper\RelProductWarehouse(
					$sm->get('adapter'),
					new \Config\Entity\RelProductWarehouse()
				);
			},
			'Config\Mapper\RelProductTypeProperty' => function($sm) {
				return new \Config\Mapper\RelProductTypeProperty(
					$sm->get('adapter'),
					new \Config\Entity\RelProductTypeProperty()
				);
			},
			'Config\Mapper\RelProductPointOfSale' => function($sm) {
				return new \Config\Mapper\RelProductPointOfSale(
					$sm->get('adapter'),
					new \Config\Entity\RelProductPointOfSale()
				);
			},
			'Config\Mapper\PropertyType' => function($sm) {
				return new \Config\Mapper\PropertyType(
					$sm->get('adapter'),
					new \Config\Entity\PropertyType()
				);
			},
			'Config\Mapper\Property' => function($sm) {
				return new \Config\Mapper\Property(
					$sm->get('adapter'),
					new \Config\Entity\Property()
				);
			},
			'Config\Mapper\ProductType' => function($sm) {
				return new \Config\Mapper\ProductType(
					$sm->get('adapter'),
					new \Config\Entity\ProductType()
				);
			},
			'Config\Mapper\Product' => function($sm) {
				return new \Config\Mapper\Product(
					$sm->get('adapter'),
					new \Config\Entity\Product()
				);
			},
			'Config\Mapper\PointOfSale' => function($sm) {
				return new \Config\Mapper\PointOfSale(
					$sm->get('adapter'),
					new \Config\Entity\PointOfSale()
				);
			},
			'Config\Mapper\Company' => function($sm) {
				return new \Config\Mapper\Company(
					$sm->get('adapter'),
					new \Config\Entity\Company()
				);
			},
			'Config\Mapper\Feature' => function($sm) {
				return new \Config\Mapper\Feature(
					$sm->get('adapter'),
					new \Config\Entity\Feature()
				);
			},
			'Config\Mapper\Permission' => function($sm) {
				return new \Config\Mapper\Permission(
					$sm->get('adapter'),
					new \Config\Entity\Permission()
				);
			},
			'Config\Mapper\Plan' => function($sm) {
				return new \Config\Mapper\Plan(
					$sm->get('adapter'),
					new \Config\Entity\Plan()
				);
			},
			'Config\Mapper\RelCompanyFeature' => function($sm) {
				return new \Config\Mapper\RelCompanyFeature(
					$sm->get('adapter'),
					new \Config\Entity\RelCompanyFeature()
				);
			},
			'Config\Mapper\RelUserPermission' => function($sm) {
				return new \Config\Mapper\RelUserPermission(
					$sm->get('adapter'),
					new \Config\Entity\RelUserPermission()
				);
			},
			'Config\Mapper\User' => function($sm) {
				return new \Config\Mapper\User(
					$sm->get('adapter'),
					new \Config\Entity\User()
				);
			},
			'Config\Mapper\UserAdmin' => function($sm) {
				return new \Config\Mapper\UserAdmin(
					$sm->get('adapter'),
					new \Config\Entity\UserAdmin()
				);
			},
		),
	),
	'translator' => array(
		'locale' => 'en_US',
		'translation_file_patterns' => array(
			array(
				'type'     => 'gettext',
				'base_dir' => __DIR__ . '/../language',
				'pattern'  => '%s.mo',
			),
		),
	),
	'view_manager' => array(
		'strategies' => array(
			'ViewJsonStrategy',
		),
	),
	'console' => array(
		'router' => array(
			'routes' => array(),
		),
	),
);
