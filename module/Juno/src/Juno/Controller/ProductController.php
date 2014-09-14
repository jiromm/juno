<?php

namespace Juno\Controller;

use Config\Library\CommonController;
use Config\Service\Product as ProductService;
use Config\Mapper\Product as ProductMapper;
use Config\Entity\Product as ProductEntity;
use Juno\Form\ProductAdd as ProductAddForm;
use Zend\Http\Request;
use Zend\View\Model\ViewModel;

class ProductController extends CommonController {
	public function init() {
		/**
		 * @todo: security checks
		 */
	}

	public function indexAction() {
		/**
		 * @var ProductService $mapper
		 */
		$service = $this->getServiceLocator()->get('ProductService');
		$result = $service->getProducts($this->getCompanyId());

		return new ViewModel([
			'data' => [],
		]);
	}

	public function addAction() {
		/**
		 * @var Request $request
		 * @var ProductMapper $mapper
		 * @var ProductEntity|bool $result
		 */
		$request = $this->getRequest();
		$mapper = $this->getServiceLocator()->get('ProductMapper');

		$form = new ProductAddForm($this->getServiceLocator(), $this->url()->fromRoute('product/add'));
		$form->prepare();

		if ($request->isPost()) {
			$form->setData($request->getPost());

			if ($form->isValid()) {
				$entity = new ProductEntity();
				$entity->setName($request->getPost('name'));
				$entity->setQuantity($request->getPost('quantity'));
				$entity->setProductTypeId($request->getPost('product_type_id'));
				$entity->setDescription($request->getPost('description'));

				try {
					$mapper->insert($entity);
					$warehouseId = $mapper->lastInsertValue;

					$this->redirect()->toRoute('product/manage', ['id' => $warehouseId]);
					return $this->getResponse();
				} catch (\Exception $ex) {
					$this->flashMessenger()->addErrorMessage('Something went wrong. Please try again later!');
				}
			} else {
				$this->flashMessenger()->addErrorMessage('Form is not valid!');
				$form->populateValues($request->getPost());
			}

			$this->redirect()->toRoute('product/add');
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
		 * @var ProductMapper $mapper
		 * @var WarehouseEntity|bool $result
		 */
		$request = $this->getRequest();
		$warehouseId = $this->params()->fromRoute('id');

		$mapper = $this->getServiceLocator()->get('ProductMapper');
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
		 * @var ProductMapper $mapper
		 */
		$mapper = $this->getServiceLocator()->get('ProductMapper');

		try {
			$mapper->delete(['id' => $this->params()->fromRoute('id')]);
			$this->flashMessenger()->addSuccessMessage('Product has been successfully removed!');
		} catch (\Exception $ex) {
			$this->flashMessenger()->addErrorMessage('Something went wrong. Please try again later!');
		}

		$this->redirect()->toRoute('product');

		return new ViewModel([
			'id' => $this->params()->fromRoute('id'),
		]);
	}
}
