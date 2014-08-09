<?php

namespace Config\Entity;

use Config\Library\EntityBase;

class PropertyType extends EntityBase {
	protected $id;
	protected $company_id;
	protected $name;
	protected $is_verified;

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

	public function setCompanyId($company_id) {
		$this->company_id = $company_id;
	}

	public function getCompanyId() {
		return $this->company_id;
	}

	public function setIsVerified($is_verified) {
		$this->is_verified = $is_verified;
	}

	public function getIsVerified() {
		return $this->is_verified;
	}
}
