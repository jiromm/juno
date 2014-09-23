<?php

namespace Juno\Controller;

use Config\Library\CommonController;
use Config\Service\ProductType as ProductTypeService;
use Config\Mapper\Property as PropertyMapper;
use Config\Mapper\PropertyType as PropertyTypeMapper;
use Config\Mapper\RelProductTypeProperty as RelProductTypePropertyMapper;
use Config\Mapper\ProductType as ProductTypeMapper;
use Config\Entity\ProductType as ProductTypeEntity;
use Config\Entity\Property as PropertyEntity;
use Config\Entity\PropertyType as PropertyTypeEntity;
use Config\Entity\RelProductTypeProperty as RelProductTypePropertyEntity;
use Juno\Form\ProductType as ProductTypeForm;
use Zend\Debug\Debug;
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
		 * @var ProductTypeMapper $productTypeMapper
		 * @var PropertyMapper $propertyMapper
		 * @var PropertyTypeMapper $propertyTypeMapper
		 * @var RelProductTypePropertyMapper $productTypeMapper
		 */
		$request = $this->getRequest();

		$productTypeMapper = $this->getServiceLocator()->get('ProductTypeMapper');
		$propertyMapper = $this->getServiceLocator()->get('PropertyMapper');
		$propertyTypeMapper = $this->getServiceLocator()->get('PropertyTypeMapper');
		$relProductTypePropertyMapper = $this->getServiceLocator()->get('RelProductTypePropertyMapper');

		$form = new ProductTypeForm($this->getServiceLocator(), $this->url()->fromRoute('product/type/add'), $this->getCompanyId());
		$form->prepare();

		if ($request->isPost()) {
			$form->setData($request->getPost());

			if ($form->isValid()) {
				$productTypeEntity = new ProductTypeEntity();
				$productTypeEntity->setName($request->getPost('name'));
				$productTypeEntity->setCompanyId($this->getCompanyId());

				try {
					$productTypeMapper->beginTransaction();

					$productTypeMapper->insert($productTypeEntity);
					$productTypeId = $productTypeMapper->lastInsertValue;

					$propertyTypes = $request->getPost('property_type');

					foreach ($request->getPost('property') as $index => $property) {
						if (empty($property)) {
							continue;
						}

						// Property Type
						if (!is_numeric($propertyTypes[$index])) {
							$propertyTypeResult = $propertyTypeMapper->getByName(trim($propertyTypes[$index]));

							if (!$propertyTypeResult) {
								$propertyTypeEntity = new PropertyTypeEntity();
								$propertyTypeEntity->setCompanyId($this->getCompanyId());
								$propertyTypeEntity->setName($propertyTypes[$index]);

								$propertyTypeMapper->insert($propertyTypeEntity);
								$propertyTypeId = $propertyTypeMapper->lastInsertValue;
							} else {
								$propertyTypeId = $propertyTypeResult->getId();
							}
						} else {
							$propertyTypeId = $propertyTypes[$index];
						}

						// Property
						$propertyEntity = new PropertyEntity();
						$propertyEntity->setName($property);
						$propertyEntity->setPropertyTypeId($propertyTypeId);

						$propertyMapper->insert($propertyEntity);
						$propertyId = $propertyMapper->lastInsertValue;

						// Rel Product Type & Property
						$relProductTypePropertyEntity = new RelProductTypePropertyEntity();
						$relProductTypePropertyEntity->setProductTypeId($productTypeId);
						$relProductTypePropertyEntity->setPropertyId($propertyId);

						$relProductTypePropertyMapper->insert($relProductTypePropertyEntity);
					}

					$productTypeMapper->commit();

					$this->redirect()->toRoute('product/type/manage', ['id' => $productTypeId]);
					return $this->getResponse();
				} catch (\Exception $ex) {
					$productTypeMapper->rollback();
					$this->flashMessenger()->addErrorMessage('Something went wrong. Please try again later! ' . $ex->getMessage());
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
		]), $this->getCompanyId());
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
