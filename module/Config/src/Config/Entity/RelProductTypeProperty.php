<?php

namespace Config\Entity;

use Config\Library\EntityBase;

class RelProductTypeProperty extends EntityBase {
	protected $id;
	protected $product_type_id;
	protected $property_id;

	public function setId($id) {
		$this->id = $id;
	}

	public function getId() {
		return $this->id;
	}

	public function setProductTypeId($product_type_id) {
		$this->product_type_id = $product_type_id;
	}

	public function getProductTypeId() {
		return $this->product_type_id;
	}

	public function setPropertyId($property_id) {
		$this->property_id = $property_id;
	}

	public function getPropertyId() {
		return $this->property_id;
	}
}
