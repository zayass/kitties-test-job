<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class IndexController extends AbstractActionController {

    public function indexAction() {
        $this->appendScript('js/jquery.simplemodal-1.4.3.js');
        $this->appendScript('js/main-page.js');

        return array(
            'kitty_url' => $this->getRandomKitty()
        );
    }


    private function getRandomKitty() {
        $width  = rand(1200, 1500);
        $height = rand(500, 700);

        return "http://placekitten.com/g/$width/$height";
    }

    private function getRenderer() {
        return $this->getServiceLocator()
                    ->get('Zend\View\Renderer\RendererInterface');
    }

    private function appendScript($path) {
        $this->getRenderer()->headScript()->appendFile($path);
    }
}
