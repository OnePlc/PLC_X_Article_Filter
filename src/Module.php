<?php
/**
 * Module.php - Module Class
 *
 * Module Class File for Article Module
 *
 * @category Config
 * @package Article
 * @author Verein onePlace
 * @copyright (C) 2020  Verein onePlace <admin@1plc.ch>
 * @license https://opensource.org/licenses/BSD-3-Clause
 * @version 1.0.0
 * @since 1.0.0
 */

namespace OnePlace\Article\Filter;

use Laminas\Mvc\MvcEvent;
use Laminas\ModuleManager\ModuleManager;

class Module {
    /**
     * Module Version
     *
     * @since 1.0.0
     */
    const VERSION = '1.0.0';

    /**
     * Load module config file
     *
     * @since 1.0.0
     * @return array
     */
    public function getConfig() : array {
        return include __DIR__ . '/../config/module.config.php';
    }

    /**
     * Load Controllers
     */
    public function getControllerConfig() : array {
        return [
            'factories' => [
                # Plugin Example Controller
                Controller\FilterController::class => function($container) {
                    $oDbAdapter = $container->get(AdapterInterface::class);
                    $tableGateway = $container->get(\OnePlace\Article\Model\ArticleTable::class);

                    # hook start
                    CoreEntityController::addHook('article-index-before-paginator',(object)['sFunction'=>'filterIndexByState','oItem'=>new Controller\FilterController($oDbAdapter,$tableGateway,$container)]);
                    # hook end
                    return new Controller\FilterController(
                        $oDbAdapter,
                        $container->get(\OnePlace\Article\Model\ArticleTable::class),
                        $container
                    );
                },
            ],
        ];
    }
}
