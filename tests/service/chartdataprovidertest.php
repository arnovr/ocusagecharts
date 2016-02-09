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

use OCA\ocUsageCharts\AppInfo\Chart;
use OCA\ocUsageCharts\ChartTypeAdapterFactory;
use OCA\ocUsageCharts\DataProviderFactory;
use OCA\ocUsageCharts\Entity\ChartConfig;
use OCA\ocUsageCharts\Entity\Storage\StorageUsageRepository;

class ChartDataProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var DataProviderFactory
     */
    private $dataProviderFactory;
    private $container;
    /**
     * @var ChartDataProvider
     */
    private $dataProvider;
    /**
     * @var StorageUsageRepository
     */
    private $storageUsageRepository;

    /**
     * @var ChartTypeAdapterFactory
     */
    private $chartTypeAdapterFactory;

    private $configMock;

    public function setUp()
    {
        $app = new Chart();
        $this->container = $app->getContainer();

        $this->storageUsageRepository = $this
            ->getMockBuilder('OCA\ocUsageCharts\Entity\Storage\StorageUsageRepository')
            ->disableOriginalConstructor()->getMock();


        $this->dataProviderFactory = $this
            ->getMockBuilder('\OCA\ocUsageCharts\DataProviderFactory')
            ->disableOriginalConstructor()
            ->getMock();

        $this->chartTypeAdapterFactory = $this->getMockBuilder('\OCA\ocUsageCharts\ChartTypeAdapterFactory')
            ->disableOriginalConstructor()
            ->getMock();

        $this->dataProvider = new ChartDataProvider($this->dataProviderFactory, $this->chartTypeAdapterFactory);
        $this->configMock = new ChartConfig(100, new \DateTime(), 'test1', 'StorageUsageLastMonth', 'c3js');
    }

    public function testGetChartUsageForUpdate()
    {
        $data = array('x' => 1, 'test1' => 333);

        $provider = $this
            ->getMockBuilder('OCA\ocUsageCharts\DataProviders\Storage\StorageUsageCurrentProvider')
            ->disableOriginalConstructor()->getMock();
        $provider
            ->expects($this->once())
            ->method('getChartUsageForUpdate')
            ->willReturn($data);
        $this->dataProviderFactory->method('getDataProviderByConfig')->willReturn($provider);

        $usageNumber = 2324235;
        $created = new \DateTime();
        $username = 'test1';
        $usage = $this->getMock('\OCA\ocUsageCharts\Entity\Storage\StorageUsage', array(), array(
                $created,
                $usageNumber,
                $username
            ));

        $result = $this->dataProvider->getChartUsageForUpdate($this->configMock);
        $this->assertEquals($data, $result);
    }

    public function testSaveDataUsage()
    {
        $provider = $this
            ->getMockBuilder('OCA\ocUsageCharts\DataProviders\Storage\StorageUsageCurrentProvider')
            ->disableOriginalConstructor()
            ->getMock();
        $provider
            ->expects($this->once())
            ->method('save')->willReturn(true);
        $this->dataProviderFactory->method('getDataProviderByConfig')->willReturn($provider);

        $usageNumber = 2324235;
        $created = new \DateTime();
        $username = 'test1';
        $usage = $this->getMock('\OCA\ocUsageCharts\Entity\Storage\StorageUsage', array(), array(
                $created,
                $usageNumber,
                $username
            ));

        $saved = $this->dataProvider->save($this->configMock, $usage);
        $this->assertTrue($saved);
    }

    public function testGetChartUsage()
    {
        $data = array(
            'x' => array(111,112),
            $this->configMock->getUsername() => array(145,454646)
        );

        $provider = $this
            ->getMockBuilder('OCA\ocUsageCharts\DataProviders\Storage\StorageUsageCurrentProvider')
            ->disableOriginalConstructor()
            ->getMock();
        $provider
            ->expects($this->once())
            ->method('getChartUsage')->willReturn($data);
        $this->dataProviderFactory->method('getDataProviderByConfig')->willReturn($provider);


        $adapter = $this
            ->getMockBuilder('OCA\ocUsageCharts\Adapters\c3js\Storage\StorageUsageLastMonthAdapter')
            ->disableOriginalConstructor()
            ->getMock();

        $adapter
            ->expects($this->once())
            ->method('formatData')
            ->willReturn($data);

        $this->chartTypeAdapterFactory
            ->expects($this->once())
            ->method('getChartTypeAdapterByConfig')
            ->willReturn($adapter);


        $data = $this->dataProvider->getChartUsage($this->configMock);

        $this->assertArrayHasKey('x', $data);
        $this->assertArrayHasKey($this->configMock->getUsername(), $data);

    }

    public function testIsAllowedToUpdateFailed()
    {
        $provider = $this
            ->getMockBuilder('OCA\ocUsageCharts\DataProviders\Storage\StorageUsageCurrentProvider')
            ->disableOriginalConstructor()
            ->getMock();
        $provider
            ->expects($this->once())
            ->method('isAllowedToUpdate')->willReturn(false);

        $this->dataProviderFactory->method('getDataProviderByConfig')->willReturn($provider);

        $this->assertFalse($this->dataProvider->isAllowedToUpdate($this->configMock));
    }

    public function testIsAllowedToUpdateSucceeded()
    {
        $provider = $this
            ->getMockBuilder('OCA\ocUsageCharts\DataProviders\Storage\StorageUsageCurrentProvider')
            ->disableOriginalConstructor()
            ->getMock();
        $provider
            ->expects($this->once())
            ->method('isAllowedToUpdate')->willReturn(true);

        $this->dataProviderFactory->method('getDataProviderByConfig')->willReturn($provider);

        $this->assertTrue($this->dataProvider->isAllowedToUpdate($this->configMock));
    }
}