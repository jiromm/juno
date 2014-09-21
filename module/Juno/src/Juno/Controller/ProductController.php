<?php

namespace Juno\Controller;

use Config\Constant\DBTable;
use Config\Library\CommonController;
use Config\Service\Product as ProductService;
use Config\Mapper\Product as ProductMapper;
use Config\Mapper\RelProductPointOfSale as RelProductPointOfSaleMapper;
use Config\Mapper\RelProductWarehouse as RelProductWarehouseMapper;
use Config\Entity\Product as ProductEntity;
use Juno\Form\ProductAdd as ProductAddForm;
use Zend\Debug\Debug;
use Zend\Http\Request;
use Zend\View\Model\ViewModel;
use Config\Entity;

class ProductController extends CommonController {
	public function init() {
		/**
		 * @todo: security checks
		 */
	}

	public function indexAction() {
		/**
		 * @var ProductService $service
		 */
		$service = $this->getServiceLocator()->get('ProductService');
		$result = $service->getProducts($this->getCompanyId());

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
		 * @var ProductEntity|bool $result
		 */
		$request = $this->getRequest();
		$mapper = $this->getServiceLocator()->get('ProductMapper');

		$form = new ProductAddForm($this->getServiceLocator(), $this->url()->fromRoute('product/add'), $this->getCompanyId());
		$form->prepare();

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
			'error' => isset($error) ? $error : false,
		]);
	}

	public function manageAction() {
		/**
		 * @var Request $request
		 * @var ProductMapper $mapper
		 * @var ProductEntity|bool $result
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
