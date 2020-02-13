<?php
/**
 * module.config.php - Filter Config
 *
 * Main Config File for Article Filter Plugin
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

use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\Factory\InvokableFactory;

return [
    # Filter Module - Routes
    'router' => [
        'routes' => [
            'article-filter-setup' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/article/filter/setup',
                    'defaults' => [
                        'controller' => Controller\InstallController::class,
                        'action'     => 'checkdb',
                    ],
                ],
            ],
        ],
    ], # Routes

    # View Settings
    'view_manager' => [
        'template_path_stack' => [
            'article-filter' => __DIR__ . '/../view',
        ],
    ],
];
