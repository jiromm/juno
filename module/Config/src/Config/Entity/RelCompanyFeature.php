<?php

namespace Config\Entity;

use Config\Library\EntityBase;

class RelCompanyFeature extends EntityBase {
	protected $id;
	protected $company_id;
	protected $feature_id;

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

	public function setFeatureId($feature_id) {
		$this->feature_id = $feature_id;
	}

	public function getFeatureId() {
		return $this->feature_id;
	}
}
