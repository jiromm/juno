<?php

namespace Juno\Controller;

use Juno\Filter\HouseFilter;
use Juno\Form\House;
use Juno\Mapper\House as HouseMapper;
use Juno\Entity\House as HouseEntity;
use Zend\Debug\Debug;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class HouseController extends AbstractActionController {
    public function indexAction() {
	    /**
	     * @var HouseMapper $mapper
	     */
	    $mapper = $this->getServiceLocator()->get('HouseMapper');
	    $result = $mapper->fetchAll();

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

				if ($_FILES['image']['name']) {
					if (!$_FILES['image']['error']) {
						$newFileName = $dir . '/big.jpeg';

						if ($_FILES['image']['size'] > (1024000)) {
							$error = 'Oops! Your file\'s size is to large.';
						} else {
							if (in_array(strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION)), ['jpg', 'jpeg'])) {
								move_uploaded_file($_FILES['image']['tmp_name'], $newFileName);
							} else {
								$error = 'Ooops! JPEG please.';
							}
						}
					} else {
						$error = 'Ooops! Your upload triggered the following error: ' . $_FILES['image']['error'];
					}
				}

				if ($_FILES['icon']['name']) {
					if (!$_FILES['icon']['error']) {
						$newFileName = $dir . '/icon.jpeg';

						if ($_FILES['icon']['size'] > (1024000)) {
							$error = 'Oops! Your file\'s size is to large.';
						} else {
							if (in_array(strtolower(pathinfo($_FILES['icon']['name'], PATHINFO_EXTENSION)), ['jpg', 'jpeg'])) {
								move_uploaded_file($_FILES['icon']['tmp_name'], $newFileName);
							} else {
								$error = 'Ooops! JPEG please.';
							}
						}
					} else {
						$error = 'Ooops! Your upload triggered the following error: ' . $_FILES['icon']['error'];
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

				if ($_FILES['image']['name']) {
					if (!$_FILES['image']['error']) {
						$newFileName = $dir . '/big.jpeg';

						if ($_FILES['image']['size'] > (1024000)) {
							$error = 'Oops! Your file\'s size is to large.';
						} else {
							if (in_array(strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION)), ['jpg', 'jpeg'])) {
								move_uploaded_file($_FILES['image']['tmp_name'], $newFileName);
							} else {
								$error = 'Ooops! JPEG please.';
							}
						}
					} else {
						$error = 'Ooops! Your upload triggered the following error: ' . $_FILES['image']['error'];
					}
				}

				if ($_FILES['icon']['name']) {
					if (!$_FILES['icon']['error']) {
						$newFileName = $dir . '/icon.jpeg';

						if ($_FILES['icon']['size'] > (1024000)) {
							$error = 'Oops! Your file\'s size is to large.';
						} else {
							if (in_array(strtolower(pathinfo($_FILES['icon']['name'], PATHINFO_EXTENSION)), ['jpg', 'jpeg'])) {
								move_uploaded_file($_FILES['icon']['tmp_name'], $newFileName);
							} else {
								$error = 'Ooops! JPEG please.';
							}
						}
					} else {
						$error = 'Ooops! Your upload triggered the following error: ' . $_FILES['icon']['error'];
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
