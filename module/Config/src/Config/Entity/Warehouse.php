<?php

namespace Config\Entity;

use Config\Library\EntityBase;

class Warehouse extends EntityBase {
	protected $id;
	protected $company_id;
	protected $name;
	protected $address;

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

	public function setAddress($address) {
		$this->address = $address;
	}

	public function getAddress() {
		return $this->address;
	}

	public function setCompanyId($company_id) {
		$this->company_id = $company_id;
	}

	public function getCompanyId() {
		return $this->company_id;
	}
}
