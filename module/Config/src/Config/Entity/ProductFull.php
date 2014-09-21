<?php

namespace Config\Entity;

use Config\Library\EntityBase;

class ProductFull extends EntityBase {
	protected $id;
	protected $type;
	protected $quantity;
	protected $name;
	protected $direction;

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

	public function setQuantity($quantity) {
		$this->quantity = $quantity;
	}

	public function getQuantity() {
		return $this->quantity;
	}

	public function setType($type) {
		$this->type = $type;
	}

	public function getType() {
		return $this->type;
	}

	public function setDirection($direction) {
		$this->direction = $direction;
	}

	public function getDirection() {
		return $this->direction;
	}
}
