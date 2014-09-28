<?php

namespace Config\Entity;

use Config\Library\EntityBase;

class PropertyFull extends EntityBase {
	protected $id;
	protected $name;
	protected $property_type;
	protected $product_type_id;

	public function setId($id) {
		$this->id = $id;
	}

	public function getId() {
		return $this->id;
	}

	public function setName($name) {
		$this->name = $name;
	}

	public function getName() {
		return $this->name;
	}

	public function setPropertyType($property_type) {
		$this->property_type = $property_type;
	}

	public function getPropertyType() {
		return $this->property_type;
	}

	public function setProductTypeId($product_type_id) {
		$this->product_type_id = $product_type_id;
	}

	public function getProductTypeId() {
		return $this->product_type_id;
	}
}
