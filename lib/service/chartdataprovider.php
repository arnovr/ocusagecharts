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

use OC\AppFramework\DependencyInjection\DIContainer;
use OCA\ocUsageCharts\Adapters\ChartTypeAdapterInterface;
use OCA\ocUsageCharts\Entity\ChartConfig;
use OCA\ocUsageCharts\Exception\ChartDataProviderException;
use OCA\ocUsageCharts\DataProviders\DataProviderInterface;

/**
 * @author Arno van Rossum <arno@van-rossum.com>
 */
class ChartDataProvider
{
    /**
     * DiContainer is used for dataproviders to grep their repository needed
     * @var DiContainer
     */
    private $container;

    public function __construct(DIContainer $container)
    {
        $this->container = $container;
    }
    /**
     * @param ChartConfig $config
     * @return DataProviderInterface
     *
     * @throws \OCA\ocUsageCharts\Exception\ChartDataProviderException
     */
    private function getDataProviderByConfig(ChartConfig $config)
    {
        $dataProvider = '\OCA\ocUsageCharts\DataProviders\\' .  $config->getChartType() . 'Provider';

        if ( !class_exists($dataProvider) )
        {
            throw new ChartDataProviderException("DataProvider for " . $config->getChartType() . ' does not exist.');
        }
        return new $dataProvider($this->container, $config);
    }

    /**
     * This returns the data adapter for a specific provider
     * This is use full when you want to implement alternatives for the current
     * chart providers
     *
     * @TODO Same code is used in chartservice, fix this.....
     *
     * @param ChartConfig $config
     * @return ChartTypeAdapterInterface
     *
     * @throws \OCA\ocUsageCharts\Exception\ChartDataProviderException
     */
    private function getProviderAdapterByConfig(ChartConfig $config)
    {
        // If an adapter has been defined, format the data, else just return data parsed by the system
        $adapter = '\OCA\ocUsageCharts\Adapters\\' .  $config->getChartProvider() . '\\' . $config->getChartType() . 'Adapter';
        if ( !class_exists($adapter) )
        {
            throw new ChartDataProviderException("Adapter for " . $config->getChartType() . ' does not exist.');
        }
        return new $adapter($config);
    }

    /**
     * Get the current usage for a chart given
     *
     * @param ChartConfig $chartConfig
     * @return mixed
     */
    public function getChartUsageForUpdate(ChartConfig $chartConfig)
    {
        $provider = $this->getDataProviderByConfig($chartConfig);
        return $provider->getChartUsageForUpdate();
    }

    /**
     * Save a particular usage
     *
     * @param ChartConfig $chartConfig
     * @param mixed $usage
     */
    public function save(ChartConfig $chartConfig, $usage)
    {
        $provider = $this->getDataProviderByConfig($chartConfig);
        $provider->save($usage);
    }

    /**
     * This method returns all usage for a chart based on the chartconfig given
     *
     * @param ChartConfig $chartConfig
     * @return \OCA\ocUsageCharts\Adapters\ChartTypeAdapterInterface
     */
    public function getChartUsage(ChartConfig $chartConfig)
    {
        $provider = $this->getDataProviderByConfig($chartConfig);
        $data = $provider->getChartUsage();

        $adapter = $this->getProviderAdapterByConfig($chartConfig);
        return $adapter->formatData($data);
    }
}
