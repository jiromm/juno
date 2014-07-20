<?php

namespace Config\Entity;

use Config\Library\EntityBase;

class UserAdmin extends EntityBase {
	protected $id;
	protected $login;
	protected $password;
	protected $is_active;

	public function setId($id) {
		$this->id = $id;
	}

	public function getId() {
		return $this->id;
	}

	public function setLogin($login) {
		$this->login = $login;
	}

	public function getLogin() {
		return $this->login;
	}

	public function setPassword($password) {
		$this->password = $password;
	}

	public function getPassword() {
		return $this->password;
	}

	public function setIsActive($is_active) {
		$this->is_active = $is_active;
	}

	public function getIsActive() {
		return $this->is_active;
	}
}
