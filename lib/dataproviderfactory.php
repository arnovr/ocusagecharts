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

use OC\AppFramework\DependencyInjection\DIContainer;
use OCA\ocUsageCharts\DataProviders\StorageUsageCurrentProvider;
use OCA\ocUsageCharts\DataProviders\StorageUsageLastMonthProvider;
use OCA\ocUsageCharts\DataProviders\StorageUsagePerMonthProvider;
use OCA\ocUsageCharts\Entity\ChartConfig;
use OCA\ocUsageCharts\Exception\ChartDataProviderException;

/**
 * @author Arno van Rossum <arno@van-rossum.com>
 */
class DataProviderFactory
{
    public function getByConfig(DIContainer $container, ChartConfig $config)
    {
        $method = 'get' . $config->getChartType() . 'Provider';
        if ( !method_exists($this, $method) )
        {
            throw new ChartDataProviderException("DataProvider for " . $config->getChartType() . ' does not exist.');
        }
        return $this->$method($container, $config);
    }
    public function getStorageUsageCurrentProvider(DIContainer $container, ChartConfig $config)
    {
        return new StorageUsageCurrentProvider($container, $config);
    }

    public function getStorageUsageLastMonthProvider(DIContainer $container, ChartConfig $config)
    {
        return new StorageUsageLastMonthProvider($container, $config);
    }

    public function getStorageUsagePerMonthProvider(DIContainer $container, ChartConfig $config)
    {
        return new StorageUsagePerMonthProvider($container, $config);
    }
}
