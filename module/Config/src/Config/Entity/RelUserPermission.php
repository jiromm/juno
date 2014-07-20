<?php

namespace Config\Entity;

use Config\Library\EntityBase;

class RelUserPermission extends EntityBase {
	protected $id;
	protected $user_id;
	protected $permission_id;

	public function setId($id) {
		$this->id = $id;
	}

	public function getId() {
		return $this->id;
	}

	public function setPermissionId($permission_id) {
		$this->permission_id = $permission_id;
	}

	public function getPermissionId() {
		return $this->permission_id;
	}

	public function setUserId($user_id) {
		$this->user_id = $user_id;
	}

	public function getUserId() {
		return $this->user_id;
	}
}
