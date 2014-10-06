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

namespace OCA\ocUsageCharts\Tests\Service;

use OCA\ocUsageCharts\Service\ChartConfigService;

class ChartConfigServiceTest extends \PHPUnit_Framework_TestCase
{
    private $container;
    private $configService;
    private $configRepository;
    private $user;

    public function setUp()
    {
        $app = new \OCA\ocUsageCharts\AppInfo\Chart();
        $this->container = $app->getContainer();
        $this->configRepository = $chartConfigRepository = $this->getMockBuilder('\OCA\ocUsageCharts\Entity\ChartConfigRepository')
            ->disableOriginalConstructor()
            ->getMock();

        $this->container->registerService('ChartConfigRepository', function($c) use ($chartConfigRepository) {
            return $chartConfigRepository;
        });

        $this->user = $this->getMock('\OCA\ocUsageCharts\Owncloud\User');
        $this->configService = new ChartConfigService($this->configRepository, $this->user);
    }

    /**
     * @expectedException OCA\ocUsageCharts\Exception\ChartConfigServiceException
     */
    public function testGetChartConfigByIdException()
    {
        $this->user->expects($this->once())->method('getSignedInUsername');
        $this->configRepository->method('findByUsername')->willReturn(array());
        $this->configService->getChartConfigById(1);
    }

    /**
     * @expectedException OCA\ocUsageCharts\Exception\ChartConfigServiceException
     */
    public function testGetChartConfigByIdFailedToFindConfig()
    {
        $this->user->expects($this->once())->method('getSignedInUsername')->willReturn('test1');
        $configMock = new \OCA\ocUsageCharts\Entity\ChartConfig(100, new \DateTime(), 'test1', 'StorageUsageCurrent', 'c3js');
        $this->configRepository->method('findByUsername')->willReturn(array($configMock));
        $this->configService->getChartConfigById(1);
    }

    public function testGetChartConfigByIdFoundConfig()
    {
        $this->user->expects($this->once())->method('getSignedInUsername')->willReturn('test1');
        $configMock = new \OCA\ocUsageCharts\Entity\ChartConfig(100, new \DateTime(), 'test1', 'StorageUsageCurrent', 'c3js');

        $this->configRepository->method('findByUsername')->willReturn(array($configMock));
        $chartConfig = $this->configService->getChartConfigById(100);
        $this->assertInstanceOf('\OCA\ocUsageCharts\Entity\ChartConfig', $chartConfig);
    }


    public function testGetChartsByUsername()
    {
        $configMock = new \OCA\ocUsageCharts\Entity\ChartConfig(100, new \DateTime(), 'test1', 'StorageUsageCurrentProvider', 'c3js');
        $return = array($configMock);
        $this->configRepository->method('findByUsername')->willReturn($return);


        $result = $this->configService->getChartsByUsername('test1');
        $this->assertCount(1, $result);
    }

    public function testSaveSucces()
    {
        $configMock = $this
            ->getMockBuilder('OCA\ocUsageCharts\Entity\ChartConfig')
            ->disableOriginalConstructor()
            ->getMock();
        $this->configRepository->method('save')->willReturn(true);
        $this->assertTrue($this->configService->save($configMock));
    }
    public function testSaveFailed()
    {
        $configMock = $this
            ->getMockBuilder('OCA\ocUsageCharts\Entity\ChartConfig')
            ->disableOriginalConstructor()
            ->getMock();
        $this->configRepository->expects($this->once())->method('save')->willReturn(false);

        $this->assertFalse($this->configService->save($configMock));
    }
}