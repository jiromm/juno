<?php

namespace Config\Entity;

use Config\Library\EntityBase;

class Company extends EntityBase {
	protected $id;
	protected $plan_id;
	protected $name;
	protected $status;

	public function setId($id) {
		$this->id = $id;
	}

	public function getId() {
		return $this->id;
	}

	public function setPlanId($plan_id) {
		$this->plan_id = $plan_id;
	}

	public function getPlanId() {
		return $this->plan_id;
	}

	public function setName($name) {
		$this->name = $name;
	}

	public function getName() {
		return $this->name;
	}

	public function setStatus($status) {
		$this->status = $status;
	}

	public function getStatus() {
		return $this->status;
	}
}
