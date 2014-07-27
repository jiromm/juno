<?php

namespace Juno\Form;

use Config\Mapper\Permission;
use Juno\Filter\UserFilter;
use Zend\Authentication\Adapter\DbTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Debug\Debug;
use Zend\Form\Form;
use Zend\ServiceManager\ServiceLocatorInterface;

class User extends Form {
	/**
	 * @param ServiceLocatorInterface $sm
	 * @param string $action
	 */
	public function __construct($sm, $action) {
		parent::__construct('house');

		$this->setAttribute('action', $action);
		$this->setAttribute('method', 'post');
		$this->setAttribute('class', 'form-horizontal user-form');
		$this->setInputFilter(new UserFilter());

		$this->add([
			'name' => 'name',
			'attributes' => [
				'type' => 'text',
				'class' => 'form-control',
				'id' => 'name',
				'autofocus' => true,
				'required' => 'required',
			],
		]);

		$this->add([
			'name' => 'email',
			'attributes' => [
				'type' => 'email',
				'class' => 'form-control',
				'id' => 'email',
				'required' => 'required',
			],
		]);

		$this->add([
			'name' => 'login',
			'attributes' => [
				'type' => 'text',
				'class' => 'form-control',
				'id' => 'login',
				'required' => 'required',
			],
		]);

		$this->add([
			'name' => 'password',
			'attributes' => [
				'type' => 'text',
				'class' => 'form-control',
				'id' => 'password',
			],
		]);

		$this->add([
			'name' => 'permission',
			'type' => 'select',
			'attributes' => [
				'class' => 'selectize',
				'id' => 'permission',
				'multiple' => true,
			],
			'options' => [
				'value_options' => $this->getUserPermissions($sm),
			],
		]);

		$this->add([
			'name' => 'submit',
			'attributes' => [
				'type' => 'submit',
				'value' => 'Submit',
				'class' => 'btn btn-lg btn-primary btn-block',
			],
		]);
	}

	/**
	 * @param ServiceLocatorInterface $sm
	 * @return array $result
	 */
	public function getUserPermissions($sm) {
		/**
		 * @var Permission $rolesMapper
		 * @var ResultSet|\Config\Entity\Permission[] $entityList
		 */
		$rolesMapper = $sm->get('PermissionMapper');
		$entityList = $rolesMapper->fetchAll();
		$result = [];

		if ($entityList->count()) {
			foreach($entityList as $entity) {
				$result[$entity->getId()] = $entity->getName();
			}
		}

		return $result;
	}
}
