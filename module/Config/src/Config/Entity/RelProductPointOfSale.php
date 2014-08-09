<?php

namespace Config\Entity;

use Config\Library\EntityBase;

class RelProductPointOfSale extends EntityBase {
	protected $id;
	protected $product_id;
	protected $point_of_sale_id;

	public function setId($id) {
		$this->id = $id;
	}

	public function getId() {
		return $this->id;
	}

	public function setPointOfSaleId($point_of_sale_id) {
		$this->point_of_sale_id = $point_of_sale_id;
	}

	public function getPointOfSaleId() {
		return $this->point_of_sale_id;
	}

	public function setProductId($product_id) {
		$this->product_id = $product_id;
	}

	public function getProductId() {
		return $this->product_id;
	}
}
