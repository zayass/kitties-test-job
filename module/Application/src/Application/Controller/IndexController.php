<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Application\Model\KittyService;
use Exception;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;

class IndexController extends AbstractActionController {

    public function indexAction() {
        if ($this->zfcUserAuthentication()->hasIdentity()) {
            $this->redirect()->toRoute('kitties');
        }

        $this->appendScript('js/jquery.simplemodal-1.4.3.js');
        $this->appendScript('js/main-page.js');

        return array(
            'kitty' => $this->getKittyService()->getBigRandom()
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
        if ($this->getRequest()->isPost()) {
            $res = $this->addKitty();

            if ($this->getRequest()->isXmlHttpRequest()) {
                return new JsonModel($res);
            }
        }

        $this->appendScript('js/jquery.masonry.min.js');
        $this->appendScript('js/kitties.js');

        return array(
            'kitties' => $this->getKittyService()->getRandomSet()
        );
    }

    public function myKittiesAction() {
        $this->appendScript('js/jquery.masonry.min.js');
        $this->appendScript('js/kitties.js');


        return array(
            'kitties' => $this->getKittyService()->getUserKitties()
        );
    }

    private function addKitty() {
        $width  = $this->params()->fromPost('width', null);
        $height = $this->params()->fromPost('height', null);

        $res = array(
            'success' => false
        );

        if (is_null($width) || is_null($height)) {
            return $res;
        }

        $kittiesTable = $this->getServiceLocator()->get('Application\Model\KittyTable');

        try {
            $kitty = $this->getKittyService()->createForUser();

            $kitty->setWidth($width)
                  ->setHeight($height);

            $id = $kittiesTable->save($kitty);
        } catch (\Exception $exc) {
            return $res;
        }

        return array(
            'id' => $id,
            'success' => true
        );
    }

    /**
     * @return KittyService
     */
    private function getKittyService() {
        return $this->getServiceLocator()->get('Application\Model\KittyService');
    }
}
