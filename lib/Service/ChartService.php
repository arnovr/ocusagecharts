<?php
namespace OCA\ocUsageCharts\Service;

use OCA\ocUsageCharts\Dto\UsageStat;
use OCA\ocUsageCharts\Service\ChartType\ChartTypeInterface;

/**
 * @author    Arno van Rossum <arno@van-rossum.com>
 * @copyright 2014 Arno van Rossum
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 */
class ChartService
{
    /**
     * @var ChartDataProvider
     */
    private $provider;

    /**
     * @param ChartDataProvider $provider
     */
    public function __construct(ChartDataProvider $provider)
    {
        $this->provider = $provider;
    }

    /**
     * @param ChartTypeInterface $chartType
     *
     * @return array
     */
    public function loadChart(ChartTypeInterface $chartType)
    {
        // Load chart with DTO or something?
        $chartType->loadChart();
        return array();
    }
}