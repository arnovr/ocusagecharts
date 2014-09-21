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

namespace OCA\ocUsageCharts;

use OCA\ocUsageCharts\Adapters as Adapters;
use OCA\ocUsageCharts\Adapters\ChartTypeAdapterInterface;
use OCA\ocUsageCharts\Entity\ChartConfig;
use OCA\ocUsageCharts\Exception\ChartTypeAdapterException;
use OCA\ocUsageCharts\Service\AppConfigService;

/**
 * @author Arno van Rossum <arno@van-rossum.com>
 */
class ChartTypeAdapterFactory
{
    /**
     * @var Service\AppConfigService
     */
    private $appConfigService;

    /**
     * Config service is used to pick up what size is defined by the admin
     * @param AppConfigService $appConfigService
     */
    public function __construct(AppConfigService $appConfigService)
    {
        $this->appConfigService = $appConfigService;
    }

    /**
     * Return the proper adapter based on the chartconfig given
     *
     * @param ChartConfig $config
     * @return ChartTypeAdapterInterface
     * @throws ChartTypeAdapterException
     */
    public function getChartTypeAdapterByConfig(ChartConfig $config)
    {
        switch($config->getChartType())
        {
            case 'StorageUsageCurrent':
                return $this->getStorageUsageCurrentAdapter($config->getChartProvider(), $config);
                break;
            case 'StorageUsageLastMonth':
                return $this->getStorageUsageLastMonthAdapter($config->getChartProvider(), $config);
                break;
            case 'StorageUsagePerMonth':
                return $this->getStorageUsagePerMonthAdapter($config->getChartProvider(), $config);
                break;
            default:
                throw new ChartTypeAdapterException("ChartType Adapter for " . $config->getChartType() . ' does not exist.');
                break;
        }
    }

    /**
     * @param string $provider
     * @param ChartConfig $config
     * @return Adapters\c3js\StorageUsageCurrentAdapter
     */
    public function getStorageUsageCurrentAdapter($provider, ChartConfig $config)
    {
        switch($provider)
        {
            case 'c3js':
            default:
                $adapter = new Adapters\c3js\StorageUsageCurrentAdapter($config);
        }
        return $adapter;
    }

    /**
     * @param string $provider
     * @param ChartConfig $config
     * @return Adapters\c3js\StorageUsageLastMonthAdapter
     */
    public function getStorageUsageLastMonthAdapter($provider, ChartConfig $config)
    {
        switch($provider)
        {
            case 'c3js':
            default:
                $adapter = new Adapters\c3js\StorageUsageLastMonthAdapter($config, $this->appConfigService->getUserValue('size'));
        }
        return $adapter;
    }

    /**
     * @param string $provider
     * @param ChartConfig $config
     * @return Adapters\c3js\StorageUsagePerMonthAdapter
     */
    public function getStorageUsagePerMonthAdapter($provider, ChartConfig $config)
    {
        switch($provider)
        {
            case 'c3js':
            default:
                $adapter = new Adapters\c3js\StorageUsagePerMonthAdapter($config);
        }
        return $adapter;
    }
}
