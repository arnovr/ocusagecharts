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

class ChartServiceTest extends \PHPUnit_Framework_TestCase
{
    private $container;
    private $configService;
    private $dataProvider;
    /**
     * @var ChartService
     */
    private $chartService;
    private $configMock;

    public function setUp()
    {
        $app = new \OCA\ocUsageCharts\AppInfo\Chart();
        $this->container = $app->getContainer();
        $this->configService = $configService = $this->getMockBuilder('\OCA\ocUsageCharts\Service\ChartConfigService')
            ->disableOriginalConstructor()
            ->getMock();

        $this->container->registerService('ChartConfigService', function($c) use ($configService) {
            return $configService;
        });

        $this->dataProvider = $dataProvider = $this->getMockBuilder('\OCA\ocUsageCharts\Service\ChartDataProvider')
        ->disableOriginalConstructor()
        ->getMock();

        $this->container->registerService('ChartDataProvider', function($c) use ($dataProvider) {
            return $dataProvider;
        });

        $user = $this->getMock('\OCA\ocUsageCharts\Owncloud\User');


        $this->configMock = new \OCA\ocUsageCharts\Entity\ChartConfig(100, new \DateTime(), 'test1', 'StorageUsageCurrent', 'c3js');
        $this->chartService = new ChartService($this->dataProvider, $this->configService, new ChartTypeAdapterFactory($user));
    }

    public function testGetCharts()
    {
        $configs = array(
            $this->configMock,
            new \OCA\ocUsageCharts\Entity\ChartConfig(101, new \DateTime(), 'test1', 'StorageUsageCurrent', 'c3js')
        );
        $this->configService->method('getCharts')->willReturn($configs);
        $charts = $this->chartService->getCharts();
        $this->assertCount(count($configs), $charts);
        foreach($charts as $adapter)
        {
            $this->assertInstanceOf('OCA\ocUsageCharts\Adapters\ChartTypeAdapterInterface', $adapter);
        }
    }
    public function testGetChart()
    {
        $this->configService->method('getChartConfigById')->willReturn($this->configMock);
        $adapter = $this->chartService->getChart($this->configMock->getId());
        $this->assertInstanceOf('OCA\ocUsageCharts\Adapters\ChartTypeAdapterInterface', $adapter);

    }
    public function testGetChartByConfig()
    {
        $adapter = $this->chartService->getChartByConfig($this->configMock);
        $this->assertInstanceOf('OCA\ocUsageCharts\Adapters\ChartTypeAdapterInterface', $adapter);
    }

    public function testGetChartUsage()
    {
        $return = array('1');
        $this->dataProvider->method('getChartUsage')->with($this->configMock)->willReturn($return);
        $data = $this->chartService->getChartUsage($this->configMock);
        $this->assertEquals($return, $data);
    }
}