<?php

namespace Juno\Controller;

use Config\Library\CommonController;
use Zend\View\Model\ViewModel;

class IndexController extends CommonController {
    public function indexAction() {
        return new ViewModel();
    }
}
