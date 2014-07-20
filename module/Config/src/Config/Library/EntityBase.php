<?php

namespace Config\Library;

use Zend\Debug\Debug;

abstract class EntityBase {
	public function exchangeArray() {
		$reflect = new \ReflectionClass($this);
		$properties = $reflect->getDefaultProperties();
		$propertiesArray = [];

		foreach ($properties as $property => $value) {
			$propertiesArray[$property] = $this->$property;
		}

		return $propertiesArray;
	}
}
