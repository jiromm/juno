<?php

namespace Config\Entity;

use Config\Library\EntityBase;

class User extends EntityBase {
	protected $id;
	protected $company_id;
	protected $login;
	protected $password;
	protected $name;
	protected $is_active;
	protected $is_primary;

	public function setId($id) {
		$this->id = $id;
	}

	public function getId() {
		return $this->id;
	}

	public function setCompanyId($company_id) {
		$this->company_id = $company_id;
	}

	public function getCompanyId() {
		return $this->company_id;
	}

	public function setIsActive($is_active) {
		$this->is_active = $is_active;
	}

	public function getIsActive() {
		return $this->is_active;
	}

	public function setIsPrimary($is_primary) {
		$this->is_primary = $is_primary;
	}

	public function getIsPrimary() {
		return $this->is_primary;
	}

	public function setLogin($login) {
		$this->login = $login;
	}

	public function getLogin() {
		return $this->login;
	}

	public function setName($name) {
		$this->name = $name;
	}

	public function getName() {
		return $this->name;
	}

	public function setPassword($password) {
		$this->password = $password;
	}

	public function getPassword() {
		return $this->password;
	}
}
