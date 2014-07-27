<?php

namespace Juno\View\Helper;

use Zend\Form\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceLocatorInterface;

class BreadcrumbHelper extends AbstractHelper {
	/**
	 * @var ServiceLocatorInterface $serviceLocator
	 */
	protected $serviceLocator;

	public function __construct($serviceLocator) {
		$this->serviceLocator = $serviceLocator;
	}

	public function __invoke(array $items) {
		$viewHelperManager = $this->serviceLocator->get('viewhelpermanager');
		$urlHelper = $viewHelperManager->get('url');

		$breadcrumb = '
			        <ol class="breadcrumb">
				        <li><a href="' . $urlHelper('home') . '">Home</a></li>
        ';

		if (count($items)) {
			foreach ($items as $k => $item) {
				$class = '';

				if ($k == count($items) - 1) {
					$class = ' class="active"';
				}

				if (is_array($item)) {
					if (count($item) > 1) {
						$breadcrumbPart = '<li' . $class . '><a href="' . $item[1] . '">' . $item[0] . '</a></li>' . PHP_EOL;
					} else {
						$breadcrumbPart = '<li' . $class . '>' . $item[0] . '</li>' . PHP_EOL;
					}
				} else {
					throw new \Exception('Invalid breadcrumb structure.');
				}

				$breadcrumb .= $breadcrumbPart;
			}
		}

		$breadcrumb .= '</ol>';

		return $breadcrumb;
	}
}
