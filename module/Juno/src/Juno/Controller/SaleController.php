<?php

namespace Juno\Controller;

use Config\Constant\Common;
use Config\Constant\DBTable;
use Config\Library\CommonController;
use Config\Service\Property as PropertyService;
use Config\Mapper\Sale as SaleMapper;
use Config\Mapper\RelProductPointOfSale as RelProductPointOfSaleMapper;
use Config\Mapper\RelProductWarehouse as RelProductWarehouseMapper;
use Config\Entity\Product as ProductEntity;
use Juno\Form\ProductAdd as ProductAddForm;
use Juno\Form\Product as ProductManageForm;
use Zend\Debug\Debug;
use Zend\Http\Request;
use Zend\View\Model\ViewModel;
use Config\Entity;

class SaleController extends CommonController {
	public function init() {
		/**
		 * @todo: security checks
		 */
	}

	public function indexAction() {
		/**
		 * @var SaleMapper $mapper
		 */
		$mapper = $this->getServiceLocator()->get('SaleMapper');
		$result = $mapper->fetchAll();

		return new ViewModel([
			'data' => $result,
		]);
	}

	public function addAction() {
		/**
		 * @var Request $request
		 * @var ProductMapper $mapper
		 * @var RelProductPointOfSaleMapper $relPOSMapper
		 * @var RelProductWarehouseMapper $relWHMapper
		 * @var PropertyService $propertyService
		 * @var ProductEntity|bool $result
		 */
		$request = $this->getRequest();
		$mapper = $this->getServiceLocator()->get('ProductMapper');
		$propertyService = $this->getServiceLocator()->get('PropertyService');

		$form = new ProductAddForm($this->getServiceLocator(), $this->url()->fromRoute('product/add'), $this->getCompanyId());
		$form->prepare();

		$companyProperties = $propertyService->getCompanyProperties($this->getCompanyId());

		if ($request->isPost()) {
			$form->setData($request->getPost());

			if ($form->isValid()) {
				$direction = $request->getPost('direction');

				if (strpos($direction, '-') !== false) {
					list($directionName, $directionId) = explode('-', $direction);

					if (in_array($directionName, [DBTable::POINT_OF_SALE, DBTable::WAREHOUSE])) {
						$entity = new ProductEntity();
						$entity->setName($request->getPost('name'));
						$entity->setQuantity($request->getPost('quantity'));
						$entity->setProductTypeId($request->getPost('product_type_id'));

						for ($i = 1; $i <= Common::PROPERTY_TYPE_COUNT; $i++) {
							$postedProperty = $request->getPost("property{$i}", null);

							if (!is_null($postedProperty)) {
								$methodName = "setProperty{$i}";
								$entity->$methodName($postedProperty);
							}
						}

						try {
							$mapper->beginTransaction();

							$mapper->insert($entity);
							$productId = $mapper->lastInsertValue;

							if ($directionName == DBTable::POINT_OF_SALE) {
								$relPOSMapper = $this->getServiceLocator()->get('RelProductPointOfSaleMapper');

								$relPOSEntity = new Entity\RelProductPointOfSale();
								$relPOSEntity->setPointOfSaleId($directionId);
								$relPOSEntity->setProductId($productId);

								$relPOSMapper->insert($relPOSEntity);
							} else {
								$relWHMapper = $this->getServiceLocator()->get('RelProductWarehouseMapper');

								$relWHEntity = new Entity\RelProductWarehouse();
								$relWHEntity->setWarehouseId($directionId);
								$relWHEntity->setProductId($productId);

								$relWHMapper->insert($relWHEntity);
							}

							$mapper->commit();

							$this->redirect()->toRoute('product/manage', ['id' => $productId]);

							return $this->getResponse();
						} catch (\Exception $ex) {
							$mapper->rollback();

							$this->flashMessenger()->addErrorMessage('Something went wrong. Please try again later!');
						}
					}
				}

				$this->redirect()->toRoute('home');
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
			'companyProperties' => $companyProperties,
			'error' => isset($error) ? $error : false,
		]);
	}

	public function manageAction() {
		/**
		 * @var Request $request
		 * @var ProductMapper $mapper
		 * @var PropertyService $propertyService
		 * @var ProductEntity|bool $result
		 */
		$request = $this->getRequest();
		$productId = $this->params()->fromRoute('id');

		$mapper = $this->getServiceLocator()->get('ProductMapper');
		$propertyService = $this->getServiceLocator()->get('PropertyService');
		$result = $mapper->fetchOne([
			'id' => $productId,
		]);

		$form = new ProductManageForm($this->getServiceLocator(), $this->url()->fromRoute('product/manage', [
			'id' => $productId,
		]), $this->getCompanyId());
		$form->prepare();

		$productProperties = $propertyService->getProductProperties($result->getProductTypeId());

		if ($request->isPost()) {
			$form->setData($request->getPost());

			if ($form->isValid()) {
				$entity = new ProductEntity();
				$entity->setName($request->getPost('name'));
				$entity->setQuantity($request->getPost('quantity'));

				for ($i = 1; $i <= Common::PROPERTY_TYPE_COUNT; $i++) {
					$postedProperty = $request->getPost("property{$i}", null);

					if (!is_null($postedProperty)) {
						$methodName = "setProperty{$i}";
						$entity->$methodName($postedProperty);
					}
				}

				$mapper->update($entity, ['id' => $productId]);

				$this->redirect()->toRoute('product/manage', ['id' => $productId]);

				return $this->getResponse();
			} else {
				$this->flashMessenger()->addErrorMessage('Form is not valid!');
				$form->populateValues($request->getPost());
			}

			$this->redirect()->toRoute('product/add');
		} else {
			$form->populateValues(
				$result->exchangeArray()
			);
		}

		return new ViewModel([
			'form' => $form,
			'id' => $productId,
			'productProperties' => $productProperties,
		]);
	}

	public function deleteAction() {
		/**
		 * @var ProductMapper $mapper
		 */
		$mapper = $this->getServiceLocator()->get('ProductMapper');

		try {
			$mapper->beginTransaction();

			// First of all, you need to delete relations
			$mapper->delete(['id' => $this->params()->fromRoute('id')]);
			$this->flashMessenger()->addSuccessMessage('Product has been successfully removed!');

			$mapper->commit();
		} catch (\Exception $ex) {
			$mapper->rollback();
			$this->flashMessenger()->addErrorMessage('Something went wrong. Please try again later!');
		}

		$this->redirect()->toRoute('product');

		return new ViewModel([
			'id' => $this->params()->fromRoute('id'),
		]);
	}
}
