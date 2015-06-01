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

use OCA\ocUsageCharts\ChartTypeAdapterFactory;
use OCA\ocUsageCharts\DataProviderFactory;
use OCA\ocUsageCharts\Entity\ChartConfig;

/**
 * @author Arno van Rossum <arno@van-rossum.com>
 */
class ChartDataProvider
{
    /**
     * @var DataProviderFactory
     */
    private $dataProviderFactory;

    /**
     * @var ChartTypeAdapterFactory
     */
    private $chartTypeAdapterFactory;

    /**
     * @param DataProviderFactory $dataProviderFactory
     * @param ChartTypeAdapterFactory $chartTypeAdapterFactory
     */
    public function __construct(DataProviderFactory $dataProviderFactory, ChartTypeAdapterFactory $chartTypeAdapterFactory)
    {
        $this->dataProviderFactory = $dataProviderFactory;
        $this->chartTypeAdapterFactory = $chartTypeAdapterFactory;
    }

    /**
     * Get the current usage for a chart given
     *
     * @param ChartConfig $chartConfig
     * @return boolean
     */
    public function isAllowedToUpdate(ChartConfig $chartConfig)
    {
        $provider = $this->dataProviderFactory->getDataProviderByConfig($chartConfig);
        return $provider->isAllowedToUpdate();
    }

    /**
     * Get the current usage for a chart given
     *
     * @param ChartConfig $chartConfig
     * @return mixed
     */
    public function getChartUsageForUpdate(ChartConfig $chartConfig)
    {
        $provider = $this->dataProviderFactory->getDataProviderByConfig($chartConfig);
        return $provider->getChartUsageForUpdate();
    }

    /**
     * Save a particular usage
     *
     * @param ChartConfig $chartConfig
     * @param mixed $usage
     * @return boolean
     */
    public function save(ChartConfig $chartConfig, $usage)
    {
        $provider = $this->dataProviderFactory->getDataProviderByConfig($chartConfig);
        return $provider->save($usage);
    }

    /**
     * This method returns all usage for a chart based on the chartconfig given
     *
     * @param ChartConfig $chartConfig
     * @return array
     */
    public function getChartUsage(ChartConfig $chartConfig)
    {
        $provider = $this->dataProviderFactory->getDataProviderByConfig($chartConfig);
        $data = $provider->getChartUsage();

        $adapter = $this->chartTypeAdapterFactory->getChartTypeAdapterByConfig($chartConfig);
        return $adapter->formatData($data);
    }
}
