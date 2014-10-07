<?php

namespace Juno\Controller;

use Config\Helper\Utils;
use Config\Library\CommonController;
use Config\Service\User as UserService;
use Config\Mapper\User as UserMapper;
use Config\Mapper\RelUserPermission as RelUserPermissionMapper;
use Config\Entity\User as UserEntity;
use Config\Entity\RelUserPermission as RelUserPermissionEntity;
use Juno\Form\User as UserForm;
use Zend\Http\Request;
use Zend\View\Model\ViewModel;

class UserController extends CommonController {
	public function init() {
		/**
		 * @todo: security checks
		 */
	}

    public function indexAction() {
	    /**
	     * @var UserService $service
	     */
	    $service = $this->getServiceLocator()->get('UserService');
	    $result = $service->getCompanyUsers($this->getCompanyId());

	    return new ViewModel([
		    'data' => $result,
	    ]);
    }

	public function addAction() {
		/**
		 * @var Request $request
		 * @var UserMapper $mapper
		 * @var RelUserPermissionMapper $permissionMapper
		 * @var UserEntity|bool $result
		 */
		$request = $this->getRequest();

		$mapper = $this->getServiceLocator()->get('UserMapper');
		$permissionMapper = $this->getServiceLocator()->get('RelUserPermissionMapper');

		$form = new UserForm($this->getServiceLocator(), $this->url()->fromRoute('user/add'));
		$form->prepare();

		if ($request->isPost()) {
			$form->setData($request->getPost());

			if ($form->isValid()) {
				$entity = new UserEntity();
				$entity->setName($request->getPost('name'));
				$entity->setEmail($request->getPost('email'));
				$entity->setLogin($request->getPost('login'));
				$entity->setCompanyId($this->getCompanyId());
				$entity->setIsPrimary(0);
				$entity->setPassword(
					Utils::generateHash($request->getPost('password'))
				);

				$mapper->beginTransaction();

				try {
					$mapper->insert($entity);
					$userId = $mapper->lastInsertValue;

					// Manage permissions
					$permissionMapper->delete(['user_id' => $userId]);

					if (count($request->getPost('permission'))) {
						$permissionEntity = new RelUserPermissionEntity();

						foreach ($request->getPost('permission') as $role) {
							$permissionEntity->setPermissionId($role);
							$permissionEntity->setUserId($userId);
							$permissionMapper->insert($permissionEntity);
						}
					}

					$mapper->commit();
					$this->flashMessenger()->addSuccessMessage('User successfully added!');
					$this->redirect()->toRoute('user/manage', ['id' => $userId]);
					return $this->getResponse();
				} catch (\Exception $ex) {
					$mapper->rollback();
					$this->flashMessenger()->addErrorMessage('Something went wrong. Please try again later!');
				}
			} else {
				$this->flashMessenger()->addErrorMessage('Form is not valid!');
				$form->populateValues($request->getPost());
			}

			$this->redirect()->toRoute('user/add');
		}

		return new ViewModel([
			'form' => $form,
			'error' => isset($error) ? $error : false,
		]);
	}

	public function manageAction() {
		/**
		 * @var Request $request
		 * @var UserMapper $mapper
		 * @var RelUserPermissionMapper $permissionMapper
		 * @var UserEntity|bool $result
		 */
		$request = $this->getRequest();
		$userId = $this->params()->fromRoute('id');

		$mapper = $this->getServiceLocator()->get('UserMapper');
		$permissionMapper = $this->getServiceLocator()->get('RelUserPermissionMapper');
		$result = $mapper->fetchOne([
			'id' => $userId,
		]);
		$permissionResult = $permissionMapper->fetchAll([
			'user_id' => $userId,
		]);

		$form = new UserForm($this->getServiceLocator(), $this->url()->fromRoute('user/manage', [
			'id' => $userId,
		]));
		$form->prepare();

		if ($request->isPost()) {
			$form->setData($request->getPost());

			if ($form->isValid()) {
				$entity = new UserEntity();
				$entity->setName($request->getPost('name'));
				$entity->setEmail($request->getPost('email'));
				$entity->setLogin($request->getPost('login'));

				$password = $request->getPost('password');
				if (!empty($password)) {
					$entity->setPassword(
						Utils::generateHash($request->getPost('password'))
					);
				}

				$mapper->beginTransaction();

				try {
					$mapper->update($entity, ['id' => $userId]);

					// Manage permissions
					$permissionMapper->delete(['user_id' => $userId]);

					if (count($request->getPost('permission'))) {
						$permissionEntity = new RelUserPermissionEntity();

						foreach ($request->getPost('permission') as $role) {
							$permissionEntity->setPermissionId($role);
							$permissionEntity->setUserId($userId);
							$permissionMapper->insert($permissionEntity);
						}
					}

					$mapper->commit();
					$this->flashMessenger()->addSuccessMessage($request->getPost('name') . ' has been successfully modified!');
				} catch (\Exception $ex) {
					$mapper->rollback();
					$this->flashMessenger()->addErrorMessage('Something went wrong. Please try again later!');
				}

			} else {
				$this->flashMessenger()->addErrorMessage('Form is not valid!');
				$form->populateValues($request->getPost());
			}

			$this->redirect()->toRoute('user/manage', ['id' => $userId]);
		} else {
			// Hide sensitive data
			$result->setPassword('');

			$form->populateValues(
				array_merge_recursive(
					$result->exchangeArray(), [
						'permission' => array_map(function($item) {
							return $item['permission_id'];
						}, $permissionResult->toArray())
					]
				)
			);
		}

		return new ViewModel([
			'form' => $form,
			'id' => $userId,
			'isPrimaryUser' => $result->getIsPrimary(),
		]);
	}

	public function suspendAction() {
		/**
		 * @var UserMapper $mapper
		 */
		$mapper = $this->getServiceLocator()->get('UserMapper');
		$entity = new UserEntity();
		$entity->setIsActive(0);

		try {
			$mapper->update($entity, ['id' => $this->params()->fromRoute('id')]);

			$this->flashMessenger()->addSuccessMessage('User has been successfully suspended!');
		} catch (\Exception $ex) {
			$this->flashMessenger()->addErrorMessage('Something went wrong. Please try again later!');
		}

		$this->redirect()->toRoute('user');

		return new ViewModel([
			'id' => $this->params()->fromRoute('id'),
		]);
	}

	public function activateAction() {
		/**
		 * @var UserMapper $mapper
		 */
		$mapper = $this->getServiceLocator()->get('UserMapper');
		$entity = new UserEntity();
		$entity->setIsActive(1);

		try {
			$mapper->update($entity, ['id' => $this->params()->fromRoute('id')]);

			$this->flashMessenger()->addSuccessMessage('User has been successfully activated!');
		} catch (\Exception $ex) {
			$this->flashMessenger()->addErrorMessage('Something went wrong. Please try again later!');
		}

		$this->redirect()->toRoute('user');

		return new ViewModel([
			'id' => $this->params()->fromRoute('id'),
		]);
	}

	public function deleteAction() {
		/**
		 * @var UserMapper $mapper
		 * @var RelUserPermissionMapper $permissionMapper
		 */
		$mapper = $this->getServiceLocator()->get('UserMapper');
		$permissionMapper = $this->getServiceLocator()->get('RelUserPermissionMapper');
		$entity = new UserEntity();
		$entity->setIsRemoved(1);

		$mapper->beginTransaction();

		try {
			$mapper->update($entity, ['id' => $this->params()->fromRoute('id')]);
			$permissionMapper->delete(['user_id' => $this->params()->fromRoute('id')]);
			$mapper->commit();

			$this->flashMessenger()->addSuccessMessage('User has been successfully removed!');
		} catch (\Exception $ex) {
			$mapper->rollback();
			$this->flashMessenger()->addErrorMessage('Something went wrong. Please try again later!');
		}

		$this->redirect()->toRoute('user');

		return new ViewModel([
			'id' => $this->params()->fromRoute('id'),
		]);
	}
}
