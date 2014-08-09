<?php

namespace Config\Entity;

use Config\Library\EntityBase;

class Product extends EntityBase {
	protected $id;
	protected $product_type_id;
	protected $quantity;
	protected $name;
	protected $description;
	protected $property1;
	protected $property2;
	protected $property3;
	protected $property4;
	protected $property5;
	protected $property6;
	protected $property7;
	protected $property8;
	protected $property9;
	protected $property10;

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

	public function setDescription($description) {
		$this->description = $description;
	}

	public function getDescription() {
		return $this->description;
	}

	public function setProductTypeId($product_type_id) {
		$this->product_type_id = $product_type_id;
	}

	public function getProductTypeId() {
		return $this->product_type_id;
	}

	public function setQuantity($quantity) {
		$this->quantity = $quantity;
	}

	public function getQuantity() {
		return $this->quantity;
	}

	public function setProperty1($property1) {
		$this->property1 = $property1;
	}

	public function getProperty1() {
		return $this->property1;
	}

	public function setProperty2($property2) {
		$this->property2 = $property2;
	}

	public function getProperty2() {
		return $this->property2;
	}

	public function setProperty3($property3) {
		$this->property3 = $property3;
	}

	public function getProperty3() {
		return $this->property3;
	}

	public function setProperty4($property4) {
		$this->property4 = $property4;
	}

	public function getProperty4() {
		return $this->property4;
	}

	public function setProperty5($property5) {
		$this->property5 = $property5;
	}

	public function getProperty5() {
		return $this->property5;
	}

	public function setProperty6($property6) {
		$this->property6 = $property6;
	}

	public function getProperty6() {
		return $this->property6;
	}

	public function setProperty7($property7) {
		$this->property7 = $property7;
	}

	public function getProperty7() {
		return $this->property7;
	}

	public function setProperty8($property8) {
		$this->property8 = $property8;
	}

	public function getProperty8() {
		return $this->property8;
	}

	public function setProperty9($property9) {
		$this->property9 = $property9;
	}

	public function getProperty9() {
		return $this->property9;
	}

	public function setProperty10($property10) {
		$this->property10 = $property10;
	}

	public function getProperty10() {
		return $this->property10;
	}
}
