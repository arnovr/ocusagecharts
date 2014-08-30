<?php
/**
 * Copyright (c) 2014 - Arno van Rossum <arno@van-rossum.com>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace OCA\ocUsageCharts\AppInfo;

use OCA\ocUsageCharts\Controller\ChartApiController;
use OCA\ocUsageCharts\Controller\ChartController;
use OCA\ocUsageCharts\Entity\ChartConfigRepository;
use OCA\ocUsageCharts\Entity\StorageUsageRepository;
use OCA\ocUsageCharts\Service\ChartConfigService;
use OCA\ocUsageCharts\Service\ChartDataProvider;
use OCA\ocUsageCharts\Service\ChartService;
use OCA\ocUsageCharts\Service\ChartStorageUpdater;
use \OCP\AppFramework\App;

/**
 * Class Chart
 * @package OCA\ocUsageCharts\AppInfo
 * @author Arno van Rossum <arno@van-rossum.com>
 */
class Chart extends App
{
    public function __construct(array $urlParams = array())
    {
        parent::__construct('ocusagecharts', $urlParams);
        $this->registerServices();
    }

    private function registerServices()
    {
        $container = $this->getContainer();

        $container->registerService('StorageUsageRepository', function($c) {
            return new StorageUsageRepository(
                $c->query('ServerContainer')->getDb()
            );
        });
        $container->registerService('ChartConfigRepository', function($c) {
                return new ChartConfigRepository(
                    $c->query('ServerContainer')->getDb()
                );
            });
        $container->registerService('ChartDataProvider', function($c) {
            return new ChartDataProvider(
                $c->query('StorageUsageRepository')
            );
        });

        $container->registerService('ChartStorageUpdater', function($c) {
            return new ChartStorageUpdater(
                $c->query('ChartDataProvider')
            );
        });
        $container->registerService('ChartConfigService', function($c) {
            return new ChartConfigService(
                $c->query('ChartConfigRepository')
            );
        });
        $container->registerService('ChartService', function($c) {
                return new ChartService(
                    $c->query('ChartDataProvider'),
                    $c->query('ChartConfigService'),
                    \OCP\User::getUser()
                );
            });

        /**
         * Controllers
         */
        $container->registerService('ChartController', function($c) {
            return new ChartController(
                $c->query('AppName'),
                $c->query('Request'),
                $c->query('ChartService'),
                $c->query('ChartConfigService')
            );
        });
        $container->registerService('ChartApiController', function($c) {
            return new ChartApiController(
                $c->query('AppName'),
                $c->query('Request'),
                $c->query('ChartService')
            );
        });
    }
}