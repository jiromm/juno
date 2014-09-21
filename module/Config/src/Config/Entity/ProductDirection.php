<?php

namespace Config\Entity;

use Config\Library\EntityBase;

class ProductDirection extends EntityBase {
	protected $id;
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

	public function setDirection($direction) {
		$this->direction = $direction;
	}

	public function getDirection() {
		return $this->direction;
	}
}
