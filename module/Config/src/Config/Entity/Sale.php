<?php

namespace Config\Entity;

use Config\Library\EntityBase;

class Sale extends EntityBase {
	protected $id;
	protected $company_id;
	protected $user_id;
	protected $product_id;
	protected $quantity;
	protected $timestamp;

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

	public function setProductId($product_id) {
		$this->product_id = $product_id;
	}

	public function getProductId() {
		return $this->product_id;
	}

	public function setQuantity($quantity) {
		$this->quantity = $quantity;
	}

	public function getQuantity() {
		return $this->quantity;
	}

	public function setTimestamp($timestamp) {
		$this->timestamp = $timestamp;
	}

	public function getTimestamp() {
		return $this->timestamp;
	}

	public function setUserId($user_id) {
		$this->user_id = $user_id;
	}

	public function getUserId() {
		return $this->user_id;
	}
}
