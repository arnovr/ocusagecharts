<?php
namespace OCA\ocUsageCharts\Service\ChartType;

/**
 * @author    Arno van Rossum <arno@van-rossum.com>
 * @copyright 2014 Arno van Rossum
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 */
interface ChartTypeInterface
{
    public function loadChart();
}