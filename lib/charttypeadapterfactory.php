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
use OCA\ocUsageCharts\Owncloud\User;

/**
 * @author Arno van Rossum <arno@van-rossum.com>
 */
class ChartTypeAdapterFactory
{
    /**
     * @var User
     */
    private $user;

    /**
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
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
                $adapter = $this->getStorageUsageCurrentAdapter($config->getChartProvider(), $config);
                break;
            case 'StorageUsageLastMonth':
                $adapter = $this->getStorageUsageLastMonthAdapter($config->getChartProvider(), $config);
                break;
            case 'StorageUsagePerMonth':
                $adapter = $this->getStorageUsagePerMonthAdapter($config->getChartProvider(), $config);
                break;
            case 'ActivityUsageLastMonth':
                $adapter = $this->getActivityUsageLastMonthAdapter($config->getChartProvider(), $config);
                break;
            case 'ActivityUsagePerMonth':
                $adapter = $this->getActivityUsagePerMonthAdapter($config->getChartProvider(), $config);
                break;
            default:
                throw new ChartTypeAdapterException("ChartType Adapter for " . $config->getChartType() . ' does not exist.');
        }
        return $adapter;
    }

    /**
     * @param string $provider
     * @param ChartConfig $config
     * @return Adapters\c3js\Storage\StorageUsageCurrentAdapter
     */
    private function getStorageUsageCurrentAdapter($provider, ChartConfig $config)
    {
        switch($provider)
        {
            case 'c3js':
            default:
                $adapter = new Adapters\c3js\Storage\StorageUsageCurrentAdapter($config, $this->user);
        }
        return $adapter;
    }

    /**
     * @param string $provider
     * @param ChartConfig $config
     * @return Adapters\c3js\Storage\StorageUsageLastMonthAdapter
     */
    private function getStorageUsageLastMonthAdapter($provider, ChartConfig $config)
    {
        switch($provider)
        {
            case 'c3js':
            default:
                $adapter = new Adapters\c3js\Storage\StorageUsageLastMonthAdapter($config, $this->user);
        }
        return $adapter;
    }

    /**
     * @param string $provider
     * @param ChartConfig $config
     * @return Adapters\c3js\Storage\StorageUsagePerMonthAdapter
     */
    private function getStorageUsagePerMonthAdapter($provider, ChartConfig $config)
    {
        switch($provider)
        {
            case 'c3js':
            default:
                $adapter = new Adapters\c3js\Storage\StorageUsagePerMonthAdapter($config, $this->user);
        }
        return $adapter;
    }

    /**
     * @param string $provider
     * @param ChartConfig $config
     * @return Adapters\c3js\Activity\ActivityUsageLastMonthAdapter
     */
    private function getActivityUsageLastMonthAdapter($provider, ChartConfig $config)
    {
        switch($provider)
        {
            case 'c3js':
            default:
                $adapter = new Adapters\c3js\Activity\ActivityUsageLastMonthAdapter($config, $this->user);
        }
        return $adapter;
    }

    /**
     * @param string $provider
     * @param ChartConfig $config
     * @return Adapters\c3js\Activity\ActivityUsagePerMonthAdapter
     */
    private function getActivityUsagePerMonthAdapter($provider, ChartConfig $config)
    {
        switch($provider)
        {
            case 'c3js':
            default:
                $adapter = new Adapters\c3js\Activity\ActivityUsagePerMonthAdapter($config, $this->user);
        }
        return $adapter;
    }
}
