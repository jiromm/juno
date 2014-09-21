<?php

namespace Juno\Controller;

use Config\Library\CommonController;
use Config\Service\ProductType as ProductTypeService;
use Config\Mapper\ProductType as ProductTypeMapper;
use Config\Entity\ProductType as ProductTypeEntity;
use Juno\Form\ProductType as ProductTypeForm;
use Zend\Http\Request;
use Zend\View\Model\ViewModel;

class ProductTypeController extends CommonController {
	public function init() {
		/**
		 * @todo: security checks
		 */
	}

	public function indexAction() {
		/**
		 * @var ProductTypeService $service
		 */
		$service = $this->getServiceLocator()->get('ProductTypeService');
		$result = $service->getCompanyProductTypes($this->getCompanyId());

		return new ViewModel([
			'data' => $result,
		]);
	}

	public function addAction() {
		/**
		 * @var Request $request
		 * @var ProductTypeMapper $mapper
		 * @var ProductTypeEntity|bool $result
		 */
		$request = $this->getRequest();

		$mapper = $this->getServiceLocator()->get('ProductTypeMapper');

		$form = new ProductTypeForm($this->getServiceLocator(), $this->url()->fromRoute('product/type/add'));
		$form->prepare();

		if ($request->isPost()) {
			$form->setData($request->getPost());

			if ($form->isValid()) {
				$entity = new ProductTypeEntity();
				$entity->setName($request->getPost('name'));
				$entity->setCompanyId($this->getCompanyId());

				try {
					$mapper->insert($entity);
					$warehouseId = $mapper->lastInsertValue;

					$this->redirect()->toRoute('product/type/manage', ['id' => $warehouseId]);
					return $this->getResponse();
				} catch (\Exception $ex) {
					$this->flashMessenger()->addErrorMessage('Something went wrong. Please try again later!' . $ex->getMessage());
				}
			} else {
				$this->flashMessenger()->addErrorMessage('Form is not valid!');
				$form->populateValues($request->getPost());
			}

			$this->redirect()->toRoute('product/type/add');
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
		 * @var ProductTypeMapper $mapper
		 * @var ProductTypeEntity|bool $result
		 */
		$request = $this->getRequest();
		$productTypeId = $this->params()->fromRoute('id');

		$mapper = $this->getServiceLocator()->get('ProductTypeMapper');
		$result = $mapper->fetchOne([
			'id' => $productTypeId,
		]);

		$form = new ProductTypeForm($this->getServiceLocator(), $this->url()->fromRoute('product/type/manage', [
			'id' => $productTypeId,
		]));
		$form->prepare();

		if ($request->isPost()) {
			$form->setData($request->getPost());

			if ($form->isValid()) {
				$entity = new ProductTypeEntity();
				$entity->setName($request->getPost('name'));

				try {
					$mapper->update($entity, ['id' => $productTypeId]);
					$this->flashMessenger()->addSuccessMessage($request->getPost('name') . ' has been successfully modified!');
				} catch (\Exception $ex) {
					$this->flashMessenger()->addErrorMessage('Something went wrong. Please try again later!');
				}
			} else {
				$this->flashMessenger()->addErrorMessage('Form is not valid!');
				$form->populateValues($request->getPost());
			}

			$this->redirect()->toRoute('product/type/manage', ['id' => $productTypeId]);
		} else {
			$form->populateValues(
				$result->exchangeArray()
			);
		}

		return new ViewModel([
			'form' => $form,
			'id' => $productTypeId,
		]);
	}

	public function deleteAction() {
		/**
		 * @var ProductTypeMapper $mapper
		 */
		$mapper = $this->getServiceLocator()->get('ProductTypeMapper');

		try {
			$mapper->delete(['id' => $this->params()->fromRoute('id')]);
			$this->flashMessenger()->addSuccessMessage('Product Type has been successfully removed!');
		} catch (\Exception $ex) {
			$this->flashMessenger()->addErrorMessage('Something went wrong. Please try again later!');
		}

		$this->redirect()->toRoute('product/type');

		return new ViewModel([
			'id' => $this->params()->fromRoute('id'),
		]);
	}
}
