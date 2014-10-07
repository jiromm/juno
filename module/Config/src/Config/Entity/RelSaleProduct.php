<?php

namespace Config\Entity;

use Config\Library\EntityBase;

class RelSaleProduct extends EntityBase {
	protected $id;
	protected $sale_id;
	protected $product_id;

	public function setId($id) {
		$this->id = $id;
	}

	public function getId() {
		return $this->id;
	}

	public function setSaleId($sale_id) {
		$this->sale_id = $sale_id;
	}

	public function getSaleId() {
		return $this->sale_id;
	}

	public function setProductId($product_id) {
		$this->product_id = $product_id;
	}

	public function getProductId() {
		return $this->product_id;
	}
}
