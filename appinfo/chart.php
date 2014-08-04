<?php
namespace OCA\ocUsageCharts\AppInfo;

use OCA\ocUsageCharts\Controller\ChartController;
use OCA\ocUsageCharts\Service\ChartConfigService;
use OCA\ocUsageCharts\Service\ChartDataProvider;
use OCA\ocUsageCharts\Service\ChartService;
use OCA\ocUsageCharts\Service\ChartType\C3JS;
use OCA\ocUsageCharts\Entity\UsageChartRepository;

use \OCP\AppFramework\App;

/**
 * @author    Arno van Rossum <arno@van-rossum.com>
 * @copyright 2014 Arno van Rossum
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 */
class Chart extends App
{
    public function __construct(array $urlParams = array()){
        parent::__construct('ocUsageCharts', $urlParams);
        $this->registerServices();
    }

    private function registerServices()
    {
        $container = $this->getContainer();
        /**
         * Controllers
         */
        $container->registerService('ChartController', function($c) {
            return new ChartController(
            );
        });

        /**
         * Services
         */
        $container->registerService('c3js', function($c) {
            return new C3JS();
        });
        $container->registerService('ChartConfigService', function($c) {
            return new ChartConfigService();
        });
        $container->registerService('UsageChartRepository', function($c) {
            return new UsageChartRepository(
                $c->query('ServerContainer')->getDb()
            );
        });
        $container->registerService('ChartDataProvider', function($c) {
            return new ChartDataProvider(
                $c->query('UsageChartRepository')
            );
        });

        $container->registerService('ChartService', function($c) {
            return new ChartService(
                $c->query('ChartDataProvider')
            );
        });
    }
}