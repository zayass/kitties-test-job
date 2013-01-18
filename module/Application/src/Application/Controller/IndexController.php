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
        if ($this->zfcUserAuthentication()->hasIdentity()) {
            $this->redirect()->toRoute('kitties');
        }

        $this->appendScript('js/jquery.simplemodal-1.4.3.js');
        $this->appendScript('js/main-page.js');

        $kittyService = $this->getServiceLocator()->get('Application\Model\KittyService');

        return array(
            'kitty' => $kittyService->getBigRandom()
        );
    }

    private function getRenderer() {
        return $this->getServiceLocator()
                    ->get('Zend\View\Renderer\RendererInterface');
    }

    private function appendScript($path) {
        $this->getRenderer()->headScript()->appendFile($path);
    }

    public function kittiesAction() {
        $this->appendScript('js/jquery.masonry.min.js');
        $this->appendScript('js/kitties.js');

        $kittyService = $this->getServiceLocator()->get('Application\Model\KittyService');

        return array(
            'kitties' => $kittyService->getRandomSet()
        );
    }

    public function myKittiesAction() {
        $kittyService = $this->getServiceLocator()->get('Application\Model\KittyService');
        $kittiesTable = $this->getServiceLocator()->get('Application\Model\KittyTable');

        $kitty = $kittyService->createForUser();

        $kitty->setWidth(100)
              ->setHeight(100);


        $kittiesTable->save($kitty);

        return array(
            'kitties' => $kittiesTable->fetchAll()
        );
    }
}
