<?php

namespace Juno\View\Helper;

use Zend\Form\View\Helper\AbstractHelper;

class InfoHelper extends AbstractHelper {
	public function __invoke($info) {
		return '<i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" title="' . $info . '"></i>';
	}
}
