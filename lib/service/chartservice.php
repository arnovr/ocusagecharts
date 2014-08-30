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

use OCA\ocUsageCharts\ChartType\c3js;
use OCA\ocUsageCharts\ChartType\ChartTypeAdapterInterface;
use OCA\ocUsageCharts\Entity\ChartConfig;
use OCA\ocUsageCharts\Exception\ChartServiceException;

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
     * @var ChartConfigService
     */
    private $config;

    /**
     * @var string
     */
    private $currentUserName;

    /**
     * What providers are already loaded
     * @var array
     */
    private $isLoaded = array();

    /**
     * @param ChartDataProvider $provider
     * @param ChartConfigService $config
     * @param string $currentUserName
     *
     * @throws ChartServiceException
     */
    public function __construct(ChartDataProvider $provider, ChartConfigService $config, $currentUserName)
    {
        $this->provider = $provider;
        $this->config = $config;
        if ( empty($currentUserName) )
        {
            throw new ChartServiceException("Invalid user given");
        }
        $this->currentUserName = $currentUserName;
    }

    /**
     * Return all charts
     * @return array
     */
    public function getCharts()
    {
        $charts = array();
        $chartConfigs = $this->config->getCharts();
        foreach($chartConfigs as $config)
        {
            $charts[] = $this->getChartByConfig($config);
        }
        return $charts;
    }

    /**
     * @param $id
     * @return mixed
     * @throws \OCA\ocUsageCharts\Exception\ChartServiceException
     */
    public function getChart($id)
    {
        $config = $this->config->getChartConfigById($id);
        return $this->getChartByConfig($config);
    }

    /**
     * @param ChartConfig $config
     * @return mixed
     * @throws \OCA\ocUsageCharts\Exception\ChartServiceException
     */
    public function getChartByConfig(ChartConfig $config)
    {
        $adapter = '\OCA\ocUsageCharts\ChartType\\' .  $config->getChartProvider() . '\Adapters\\' . $config->getChartType() . 'Adapter';

        if ( !class_exists($adapter) )
        {
            throw new ChartServiceException("Adapter for " . $config->getChartType() . ' does not exist.');
        }
        $chartAdapter = new $adapter($config);

        if ( !in_array($config->getChartProvider(), $this->isLoaded) )
        {
            $chartAdapter->loadFrontend();
            $this->isLoaded[] = $config->getChartProvider();
        }

        return $chartAdapter;
    }

    /**
     * @param ChartTypeAdapterInterface $chart
     *
     * @return array
     */
    public function getUsage(ChartTypeAdapterInterface $chart)
    {
        return $this->provider->getUsage($chart->getConfig());
    }
}
