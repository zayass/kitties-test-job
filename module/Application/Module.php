<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Application\Model\Kitty;
use Application\Model\KittyService;
use Application\Model\KittyTable;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Stdlib\Hydrator\Reflection as ReflectionHydrator;
use Zend\Db\TableGateway\TableGateway;
use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module {
    public function onBootstrap(MvcEvent $e) {
        $e->getApplication()->getServiceManager()->get('translator');
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $this->initUserFormsListener($eventManager);
    }

    public function getConfig() {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig() {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig() {
        return array(
            'factories' => array(
                'Application\Model\KittyTable' =>  function($sm) {
                    $tableGateway = $sm->get('KittyTableGateway');
                    $table = new KittyTable($tableGateway);
                    return $table;
                },

                'Application\Model\KittyService' => function($sm) {
                    $auth_service = $sm->get('zfcuser_auth_service');
                    $kitty_table  = $sm->get('Application\Model\KittyTable');

                    return new KittyService($auth_service, $kitty_table);
                },

                'KittyTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new HydratingResultSet(new ReflectionHydrator, new Kitty);
                    return new TableGateway('kitties', $dbAdapter, null, $resultSetPrototype);
                },
            ),
        );
    }

    private function initUserFormsListener(EventManagerInterface $eventManager) {
        $events = $eventManager->getSharedManager();

        // This function remove email field from register form and formvalidator
        $emailRemover = function($event) {
            $target = $event->getTarget();

            $target->remove('email');
        };

        $events->attach('ZfcUser\Form\Register','init', $emailRemover);
        $events->attach('ZfcUser\Form\RegisterFilter','init', $emailRemover);
    }
}
