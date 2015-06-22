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

use Arnovr\Statistics\ApiConnection;
use Arnovr\Statistics\ContentStatisticsClient;
use OCA\ocUsageCharts\ChartTypeAdapterFactory;
use OCA\ocUsageCharts\Controller\ChartApiController;
use OCA\ocUsageCharts\Controller\ChartController;
use OCA\ocUsageCharts\DataProviderFactory;
use OCA\ocUsageCharts\DataProviders\ChartUsageHelper;
use OCA\ocUsageCharts\Entity\Activity\ActivityUsageRepository;
use OCA\ocUsageCharts\Entity\ChartConfigRepository;
use OCA\ocUsageCharts\Entity\Storage\StorageUsageRepository;
use OCA\ocUsageCharts\Hooks\FileHooks;
use OCA\ocUsageCharts\Owncloud\Storage;
use OCA\ocUsageCharts\Owncloud\User;
use OCA\ocUsageCharts\Owncloud\Users;
use OCA\ocUsageCharts\Service\ChartConfigService;
use OCA\ocUsageCharts\Service\ChartCreator;
use OCA\ocUsageCharts\Service\ChartDataProvider;
use OCA\ocUsageCharts\Service\ChartService;
use OCA\ocUsageCharts\Service\ChartUpdaterService;
use OCA\ocUsageCharts\Service\ContentStatisticsUpdater;
use \OCP\AppFramework\App;
use OCP\AppFramework\IAppContainer;


/**
 * @author Arno van Rossum <arno@van-rossum.com>
 */
class Chart extends App
{
    /**
     * @var IAppContainer
     */
    private $container;

    public function __construct(array $urlParams = array())
    {
        parent::__construct('ocusagecharts', $urlParams);
        $this->container = $this->getContainer();
        $this->registerRepositories();
        $this->registerOwncloudDependencies();
        $this->registerVarious();
        $this->registerServices();
        $this->registerUsageChartsApi();
    }

    /**
     * Owncloud dependencies, cause i don't want them in my code
     * @return void
     */
    private function registerOwncloudDependencies()
    {
        // Plural form
        $this->container->registerService('OwncloudUsers', function() {
            return new Users();
        });
        $this->container->registerService('OwncloudUser', function() {
            return new User();
        });
        $this->container->registerService('OwncloudStorage', function($c) {
            return new Storage(
                $c->query('OwncloudUser')
            );
        });
    }

    /**
     * Register all repositories
     * @return void
     */
    private function registerRepositories()
    {
        $this->container->registerService('ActivityUsageRepository', function($c) {
            return new ActivityUsageRepository(
                $c->query('ServerContainer')->getDb()
            );
        });

        $this->container->registerService('StorageUsageRepository', function($c) {
            return new StorageUsageRepository(
                $c->query('ServerContainer')->getDb()
            );
        });
        $this->container->registerService('ChartConfigRepository', function($c) {
            return new ChartConfigRepository(
                $c->query('ServerContainer')->getDb()
            );
        });
    }

    /**
     * @return void
     */
    private function registerVarious()
    {
        $this->container->registerService('ChartController', function($c) {
            return new ChartController(
                $c->query('AppName'),
                $c->query('Request'),
                $c->query('ChartService'),
                $c->query('ChartConfigService'),
                $c->query('ChartCreator'),
                $c->query('OwncloudUser'),
                $c->query('ServerContainer')->getConfig()->getAppValue(
                    $c->query('AppName'),
                    'useapi'
                )
            );
        });
        $this->container->registerService('ChartApiController', function($c) {
            return new ChartApiController(
                $c->query('AppName'),
                $c->query('Request'),
                $c->query('ChartService')
            );
        });

        $this->container->registerService('ChartTypeAdapterFactory', function($c) {
            return new ChartTypeAdapterFactory(
                $c->query('OwncloudUser')
            );
        });
        $this->container->registerService('DataProviderFactory', function($c) {
            return new DataProviderFactory(
                $c->query('StorageUsageRepository'),
                $c->query('ActivityUsageRepository'),
                $c->query('OwncloudUser'),
                $c->query('OwncloudStorage'),
                $c->query('ChartUsageHelper'),
                $c->query('OwncloudUsers')
            );
        });
        $this->container->registerService('ChartUsageHelper', function() {
            return new ChartUsageHelper();
        });

    }

    /**
     * Register all services
     * @return void
     */
    private function registerServices()
    {
        $this->container->registerService('ChartUpdaterService', function($c) {
            return new ChartUpdaterService(
                $c->query('ChartDataProvider'),
                $c->query('ChartConfigService'),
                $c->query('OwncloudUsers')
            );
        });
        $this->container->registerService('ChartCreator', function($c) {
            return new ChartCreator(
                $c->query('ChartConfigRepository'),
                $c->query('OwncloudUsers')
            );
        });
        $this->container->registerService('ChartConfigService', function($c) {
            return new ChartConfigService(
                $c->query('ChartConfigRepository'),
                $c->query('OwncloudUser')
            );
        });
        $this->container->registerService('ChartService', function($c) {
            return new ChartService(
                $c->query('ChartDataProvider'),
                $c->query('ChartConfigService'),
                $c->query('ChartTypeAdapterFactory')
            );
        });
        $this->container->registerService('ChartDataProvider', function($c) {
            return new ChartDataProvider(
                $c->query('DataProviderFactory'),
                $c->query('ChartTypeAdapterFactory')
            );
        });
    }

    private function registerUsageChartsApi()
    {
        $this->container->registerService('ContentStatisticsClient', function($c) {
            return new ContentStatisticsClient(
                new ApiConnection(
                    new \GuzzleHttp\Client(),
                    $c->query('ServerContainer')->getConfig()->getAppValue(
                        $c->query('AppName'),
                        'username'
                    ),
                    $c->query('ServerContainer')->getConfig()->getAppValue(
                        $c->query('AppName'),
                        'password'
                    )
                ),
                $c->query('ServerContainer')->getConfig()->getAppValue(
                    $c->query('AppName'),
                    'url'
                )
            );
        });

        $useApi = $this->container->query('ServerContainer')->getConfig()->getAppValue(
            $this->container->query('AppName'),
            'useapi'
        );
        $this->container->registerService('FileHooks', function($c) use (&$useApi) {
            return new FileHooks(
                $c->query('ContentStatisticsClient'),
                $useApi
            );
        });

        $this->container->registerService('ContentStatisticsUpdater', function($c) {
           return new ContentStatisticsUpdater(
               $c->query('ContentStatisticsClient'),
               $c->query('DataProviderFactory'),
               $c->query('OwncloudUsers')
           );
        });
    }
}