<?php

namespace Juno\Controller;

use Config\Helper\Utils;
use Config\Service\User as UserService;
use Config\Mapper\User as UserMapper;
use Config\Mapper\RelUserPermission as RelUserPermissionMapper;
use Config\Entity\User as UserEntity;
use Config\Entity\RelUserPermission as RelUserPermissionEntity;
use Juno\Form\User as UserForm;
use Zend\Debug\Debug;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class UserController extends AbstractActionController {
    public function indexAction() {
	    /**
	     * @var UserService $service
	     */
	    $service = $this->getServiceLocator()->get('UserService');
	    $result = $service->getAllActiveUsers();

	    return new ViewModel([
		    'data' => $result,
	    ]);
    }

	public function addAction() {
		/**
		 * @var Request $request
		 * @var HouseMapper $mapper
		 */
		$request = $this->getRequest();

		$houseForm = new House($this->getServiceLocator(), $this->url()->fromRoute('house/add'));
		$houseForm->prepare();

		if ($request->isPost()) {
			$post = array_merge_recursive(
				$request->getPost()->toArray(),
				$request->getFiles()->toArray()
			);

			$houseForm->setData($post);

			if ($houseForm->isValid()) {
				$houseEntity = new HouseEntity();
				$houseEntity->setName($request->getPost('name'));

				$mapper = $this->getServiceLocator()->get('HouseMapper');
				$mapper->insert($houseEntity);

				$lastInsertId = $mapper->lastInsertValue;
				$dir = './public/upload/house/' . $lastInsertId;

				if (!is_dir($dir)) {
					if (!mkdir($dir, 0755, true)) {
						throw new \Exception('Cannot create directory: ' . $dir);
					}
				}

				$this->redirect()->toRoute('house');
			} else {
				$houseForm->populateValues($request->getPost());
			}
		}

		return new ViewModel([
			'form' => $houseForm,
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
			'error' => isset($error) ? $error : false,
			'id' => $userId,
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

		$this->redirect()->toRoute('house');

		return new ViewModel([
			'id' => $this->params()->fromRoute('id'),
		]);
	}
}
