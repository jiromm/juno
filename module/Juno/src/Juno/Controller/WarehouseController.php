<?php

namespace Juno\Controller;

use Config\Library\CommonController;
use Config\Service\Warehouse as WarehouseService;
use Config\Mapper\Warehouse as WarehouseMapper;
use Config\Entity\Warehouse as WarehouseEntity;
use Juno\Form\Warehouse as WarehouseForm;
use Zend\Http\Request;
use Zend\View\Model\ViewModel;

class WarehouseController extends CommonController {
	public function init() {
		/**
		 * @todo: security checks
		 */
	}

	public function indexAction() {
		/**
		 * @var WarehouseService $service
		 */
		$service = $this->getServiceLocator()->get('WarehouseService');
		$result = $service->getWarehouses();

		return new ViewModel([
			'data' => $result,
		]);
	}

	public function addAction() {
		/**
		 * @var Request $request
		 * @var WarehouseMapper $mapper
		 * @var WarehouseEntity|bool $result
		 */
		$request = $this->getRequest();

		$mapper = $this->getServiceLocator()->get('WarehouseMapper');

		$form = new WarehouseForm($this->getServiceLocator(), $this->url()->fromRoute('warehouse/add'));
		$form->prepare();

		if ($request->isPost()) {
			$form->setData($request->getPost());

			if ($form->isValid()) {
				$entity = new WarehouseEntity();
				$entity->setName($request->getPost('name'));
				$entity->setAddress($request->getPost('address'));
				$entity->setCompanyId($this->getCompanyId());

				try {
					$mapper->insert($entity);
					$warehouseId = $mapper->lastInsertValue;

					$this->redirect()->toRoute('warehouse/manage', ['id' => $warehouseId]);
					return $this->getResponse();
				} catch (\Exception $ex) {
					$this->flashMessenger()->addErrorMessage('Something went wrong. Please try again later!');
				}
			} else {
				$this->flashMessenger()->addErrorMessage('Form is not valid!');
				$form->populateValues($request->getPost());
			}

			$this->redirect()->toRoute('warehouse/add');
		} else {
			$form->populateValues(
				$request->getPost()
			);
		}

		return new ViewModel([
			'form' => $form,
			'error' => isset($error) ? $error : false,
		]);
	}

	public function manageAction() {
		/**
		 * @var Request $request
		 * @var WarehouseMapper $mapper
		 * @var WarehouseEntity|bool $result
		 */
		$request = $this->getRequest();
		$warehouseId = $this->params()->fromRoute('id');

		$mapper = $this->getServiceLocator()->get('WarehouseMapper');
		$result = $mapper->fetchOne([
			'id' => $warehouseId,
		]);

		$form = new WarehouseForm($this->getServiceLocator(), $this->url()->fromRoute('warehouse/manage', [
			'id' => $warehouseId,
		]));
		$form->prepare();

		if ($request->isPost()) {
			$form->setData($request->getPost());

			if ($form->isValid()) {
				$entity = new WarehouseEntity();
				$entity->setName($request->getPost('name'));
				$entity->setAddress($request->getPost('address'));

				try {
					$mapper->update($entity, ['id' => $warehouseId]);
					$this->flashMessenger()->addSuccessMessage($request->getPost('name') . ' has been successfully modified!');
				} catch (\Exception $ex) {
					$this->flashMessenger()->addErrorMessage('Something went wrong. Please try again later!');
				}

			} else {
				$this->flashMessenger()->addErrorMessage('Form is not valid!');
				$form->populateValues($request->getPost());
			}

			$this->redirect()->toRoute('warehouse/manage', ['id' => $warehouseId]);
		} else {
			$form->populateValues(
				$result->exchangeArray()
			);
		}

		return new ViewModel([
			'form' => $form,
			'id' => $warehouseId,
		]);
	}

	public function deleteAction() {
		/**
		 * @var WarehouseMapper $mapper
		 */
		$mapper = $this->getServiceLocator()->get('WarehouseMapper');

		$mapper->beginTransaction();

		try {
			$mapper->delete(['id' => $this->params()->fromRoute('id')]);
			$this->flashMessenger()->addSuccessMessage('Warehouse has been successfully removed!');
		} catch (\Exception $ex) {
			$this->flashMessenger()->addErrorMessage('Something went wrong. Please try again later!');
		}

		$this->redirect()->toRoute('warehouse');

		return new ViewModel([
			'id' => $this->params()->fromRoute('id'),
		]);
	}
}
