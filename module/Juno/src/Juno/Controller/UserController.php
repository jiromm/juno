<?php

namespace Juno\Controller;

use Config\Service\User as UserService;
use Juno\Filter\UserFilter;
use Juno\Form\User;
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

	public function editAction() {
		/**
		 * @var Request $request
		 * @var HouseMapper $mapper
		 */
		$request = $this->getRequest();
		$id = $this->params()->fromRoute('id');

		$houseForm = new House($this->getServiceLocator(), $this->url()->fromRoute('house/edit', [
			'id' => $id,
		]));
		$houseForm->prepare();

		if ($request->isPost()) {
			$houseForm->setData($request->getPost());

			if ($houseForm->isValid()) {
				$houseEntity = new HouseEntity();
				$houseEntity->setName($request->getPost('name'));

				$mapper = $this->getServiceLocator()->get('HouseMapper');
				$mapper->update($houseEntity, ['id' => $id]);

				$lastInsertId = $id;
				$dir = './public/upload/house/' . $lastInsertId;

				if (!is_dir($dir)) {
					if (!mkdir($dir, 777, true)) {
						throw new \Exception('Cannot create directory: ' . $dir);
					}
				}
			} else {
				$houseForm->populateValues($request->getPost());
			}
		} else {
			$mapper = $this->getServiceLocator()->get('HouseMapper');
			$result = $mapper->fetchOne([
				'id' => $id,
			]);

			$houseForm->populateValues($result->exchangeArray());
		}

		return new ViewModel([
			'form' => $houseForm,
			'error' => isset($error) ? $error : false,
			'id' => $id,
		]);
	}

	public function deleteAction() {
		$id = $this->params()->fromRoute('id');

		/**
		 * @var HouseMapper $mapper
		 */
		$mapper = $this->getServiceLocator()->get('HouseMapper');
		$mapper->delete([
			'id' => $id,
		]);

		$this->redirect()->toRoute('house');

		return new ViewModel([
			'id' => $this->params()->fromRoute('id'),
		]);
	}
}
