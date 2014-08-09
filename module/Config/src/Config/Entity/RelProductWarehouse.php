<?php

namespace Config\Entity;

use Config\Library\EntityBase;

class RelProductWarehouse extends EntityBase {
	protected $id;
	protected $product_id;
	protected $warehouse_id;

	public function setId($id) {
		$this->id = $id;
	}

	public function getId() {
		return $this->id;
	}

	public function setProductId($product_id) {
		$this->product_id = $product_id;
	}

	public function getProductId() {
		return $this->product_id;
	}

	public function setWarehouseId($warehouse_id) {
		$this->warehouse_id = $warehouse_id;
	}

	public function getWarehouseId() {
		return $this->warehouse_id;
	}
}
