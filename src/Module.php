<?php
/**
 * Module.php - Module Class
 *
 * Module Class File for Article Filter Plugin
 *
 * @category Config
 * @package Article\Filter
 * @author Verein onePlace
 * @copyright (C) 2020  Verein onePlace <admin@1plc.ch>
 * @license https://opensource.org/licenses/BSD-3-Clause
 * @version 1.0.0
 * @since 1.0.0
 */

namespace OnePlace\Article\Filter;

use Application\Controller\CoreEntityController;
use Laminas\Mvc\MvcEvent;
use Laminas\EventManager\EventInterface as Event;
use Laminas\ModuleManager\ModuleManager;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\Db\Adapter\AdapterInterface;
use OnePlace\Article\Filter\Controller\FilterController;

class Module {
    /**
     * Module Version
     *
     * @since 1.0.0
     */
    const VERSION = '1.0.2.1';

    /**
     * Load module config file
     *
     * @since 1.0.0
     * @return array
     */
    public function getConfig() : array {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function onBootstrap(Event $e)
    {
        // This method is called once the MVC bootstrapping is complete
        $application = $e->getApplication();
        $container    = $application->getServiceManager();
        $oDbAdapter = $container->get(AdapterInterface::class);
        $tableGateway = $container->get(\OnePlace\Article\Model\ArticleTable::class);

        # Register Filter Plugin Hook
        CoreEntityController::addHook('article-index-before-paginator',(object)['sFunction'=>'filterIndexByState','oItem'=>new FilterController($oDbAdapter,$tableGateway,$container)]);
    }

    /**
     * Load Models
     */

    /**
     * Load Controllers
     */
    public function getControllerConfig() : array {
        return [
            'factories' => [
                Controller\FilterController::class => function($container) {
                    $oDbAdapter = $container->get(AdapterInterface::class);
                    $tableGateway = $container->get(\OnePlace\Article\Model\ArticleTable::class);

                    # hook start
                    # hook end
                    return new Controller\FilterController(
                        $oDbAdapter,
                        $container->get(\OnePlace\Article\Model\ArticleTable::class),
                        $container
                    );
                },
                # Installer
                Controller\InstallController::class => function($container) {
                    $oDbAdapter = $container->get(AdapterInterface::class);
                    return new Controller\InstallController(
                        $oDbAdapter,
                        $container->get(Model\FilterTable::class),
                        $container
                    );
                },
            ],
        ];
    } # getControllerConfig()
}
