<?php

namespace Config\Entity;

use Config\Library\EntityBase;

class Property extends EntityBase {
	protected $id;
	protected $property_type_id;
	protected $name;

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

	public function setPropertyTypeId($property_type_id) {
		$this->property_type_id = $property_type_id;
	}

	public function getPropertyTypeId() {
		return $this->property_type_id;
	}
}
