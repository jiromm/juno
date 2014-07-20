<?php

namespace Juno\View\Helper;

use Zend\Form\View\Helper\AbstractHelper;

class RequiredHelper extends AbstractHelper {
	public function __invoke() {
		return '<span class="element-required" data-toggle="tooltip" title="Required">*</span>';
	}
}
