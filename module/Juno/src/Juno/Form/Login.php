<?php

namespace Juno\Form;

use Zend\Form\Form;
use Juno\Filter\LoginFilter;

class Login extends Form {
	public function __construct() {
		parent::__construct('login');

		$this->setAttribute('action', '/login');
		$this->setAttribute('method', 'post');
		$this->setAttribute('class', 'form-signin');
		$this->setInputFilter(new LoginFilter());

		$this->add([
			'name' => 'login',
			'attributes' => [
				'type' => 'text',
				'class' => 'form-control',
				'placeholder' => 'Login',
				'autofocus' => true,
			],
			'options' => [
				'label' => 'Login'
			],
		]);

		$this->add([
			'name' => 'password',
			'attributes' => [
				'type' => 'password',
				'class' => 'form-control',
				'placeholder' => 'Password',
			],
			'options' => [
				'label' => 'Password'
			],
		]);

		$this->add([
			'name' => 'submit',
			'attributes' => [
				'type' => 'submit',
				'value' => 'Submit',
				'class' => 'btn btn-lg btn-primary btn-block',
			],
		]);
	}
}
