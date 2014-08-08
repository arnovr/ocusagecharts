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

namespace OCA\ocUsageCharts\Service;

use OCA\ocUsageCharts\Service\ChartType\c3js;
use OCA\ocUsageCharts\Service\ChartType\ChartTypeInterface;

/**
 * @author Arno van Rossum <arno@van-rossum.com>
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
     * @return array
     */
    public function getChartUsage()
    {
        return $this->provider->getUsage(new \stdClass());
    }

    /**
     * @todo This should be loaded from the config
     *
     *
     * return list of default charts
     *
     * @return array
     */
    public function getCharts()
    {
        $pieChart = new c3js();
        $pieChart->setGraphType(ChartTypeInterface::CHART_PIE);
        $pieChart->loadFrontend();

        $graphChart = new c3js();
        $graphChart->setGraphType(ChartTypeInterface::CHART_GRAPH);

        return array($pieChart, $graphChart);
    }
}