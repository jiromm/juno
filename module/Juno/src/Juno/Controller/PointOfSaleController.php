<?php

namespace Juno\Controller;

use Config\Library\CommonController;
use Config\Service\PointOfSale as PointOfSaleService;
use Config\Mapper\PointOfSale as PointOfSaleMapper;
use Config\Entity\PointOfSale as PointOfSaleEntity;
use Juno\Form\PointOfSale as PointOfSaleForm;
use Zend\Http\Request;
use Zend\View\Model\ViewModel;

class PointOfSaleController extends CommonController {
	public function init() {
		/**
		 * @todo: security checks
		 */
	}

	public function indexAction() {
		/**
		 * @var PointOfSaleService $service
		 */
		$service = $this->getServiceLocator()->get('PointOfSaleService');
		$result = $service->getPointsOfSale();

		return new ViewModel([
			'data' => $result,
		]);
	}

	public function addAction() {
		/**
		 * @var Request $request
		 * @var PointOfSaleMapper $mapper
		 * @var PointOfSaleEntity|bool $result
		 */
		$request = $this->getRequest();

		$mapper = $this->getServiceLocator()->get('PointOfSaleMapper');

		$form = new PointOfSaleForm($this->getServiceLocator(), $this->url()->fromRoute('point-of-sale/add'));
		$form->prepare();

		if ($request->isPost()) {
			$form->setData($request->getPost());

			if ($form->isValid()) {
				$entity = new PointOfSaleEntity();
				$entity->setName($request->getPost('name'));
				$entity->setAddress($request->getPost('address'));
				$entity->setCompanyId($this->getCompanyId());

				try {
					$mapper->insert($entity);
					$pointOfSaleId = $mapper->lastInsertValue;

					$this->redirect()->toRoute('point-of-sale/manage', ['id' => $pointOfSaleId]);
					return $this->getResponse();
				} catch (\Exception $ex) {
					$this->flashMessenger()->addErrorMessage('Something went wrong. Please try again later!');
				}
			} else {
				$this->flashMessenger()->addErrorMessage('Form is not valid!');
				$form->populateValues($request->getPost());
			}

			$this->redirect()->toRoute('point-of-sale/add');
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
		 * @var PointOfSaleMapper $mapper
		 * @var PointOfSaleEntity|bool $result
		 */
		$request = $this->getRequest();
		$pointOfSaleId = $this->params()->fromRoute('id');

		$mapper = $this->getServiceLocator()->get('PointOfSaleMapper');
		$result = $mapper->fetchOne([
			'id' => $pointOfSaleId,
		]);

		$form = new PointOfSaleForm($this->getServiceLocator(), $this->url()->fromRoute('point-of-sale/manage', [
			'id' => $pointOfSaleId,
		]));
		$form->prepare();

		if ($request->isPost()) {
			$form->setData($request->getPost());

			if ($form->isValid()) {
				$entity = new PointOfSaleEntity();
				$entity->setName($request->getPost('name'));
				$entity->setAddress($request->getPost('address'));

				try {
					$mapper->update($entity, ['id' => $pointOfSaleId]);
					$this->flashMessenger()->addSuccessMessage($request->getPost('name') . ' has been successfully modified!');
				} catch (\Exception $ex) {
					$this->flashMessenger()->addErrorMessage('Something went wrong. Please try again later!');
				}

			} else {
				$this->flashMessenger()->addErrorMessage('Form is not valid!');
				$form->populateValues($request->getPost());
			}

			$this->redirect()->toRoute('point-of-sale/manage', ['id' => $pointOfSaleId]);
		} else {
			$form->populateValues(
				$result->exchangeArray()
			);
		}

		return new ViewModel([
			'form' => $form,
			'id' => $pointOfSaleId,
		]);
	}

	public function deleteAction() {
		/**
		 * @var PointOfSaleMapper $mapper
		 */
		$mapper = $this->getServiceLocator()->get('PointOfSaleMapper');

		try {
			$mapper->delete(['id' => $this->params()->fromRoute('id')]);
			$this->flashMessenger()->addSuccessMessage('Point of Sale has been successfully removed!');
		} catch (\Exception $ex) {
			$this->flashMessenger()->addErrorMessage('Something went wrong. Please try again later!');
		}

		$this->redirect()->toRoute('point-of-sale');

		return new ViewModel([
			'id' => $this->params()->fromRoute('id'),
		]);
	}
}
