<?php

namespace Config\Entity;

use Config\Library\EntityBase;

class ProductProperties extends EntityBase {
	protected $id;
	protected $name;
	protected $type;

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

	public function setType($type) {
		$this->type = $type;
	}

	public function getType() {
		return $this->type;
	}
}
