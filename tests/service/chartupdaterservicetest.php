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

class ChartUpdaterServiceTest extends \PHPUnit_Framework_TestCase
{

    private $container;
    private $configService;
    private $dataProvider;
    /**
     * @var ChartUpdaterService
     */
    private $chartUpdaterService;
    private $configMock;
    private $owncloudUser;


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
        $owncloudUser = $this->owncloudUser = $this->getMock('\OCA\ocUsageCharts\Owncloud\User');
        $this->container->registerService('OwncloudUser', function($c) use ($owncloudUser) {
            return $owncloudUser;
        });

        $this->configMock = new \OCA\ocUsageCharts\Entity\ChartConfig(100, new \DateTime(), 'test1', 'StorageUsageCurrent', 'c3js');
        $this->chartUpdaterService = new ChartUpdaterService($this->dataProvider, $this->configService, $this->owncloudUser);
    }

    /**
     * No asserts, but do check on what calls to expect
     */
    public function testUpdateChart()
    {
        $users = array('test1', 'test2', 'test3');
        $this->owncloudUser->expects($this->once())->method('getSystemUsers')->willReturn($users);
        $this->configService->expects($this->exactly(count($users)))->method('getChartsByUsername')->willReturn(array($this->configMock));
        $this->dataProvider->expects($this->exactly(count($users)))->method('isAllowedToUpdate')->willReturn(true);
        $this->dataProvider->expects($this->exactly(count($users)))->method('getChartUsageForUpdate')->willReturn(array('bogusdata'));
        $this->dataProvider->expects($this->exactly(count($users)))->method('save')->with($this->configMock, array('bogusdata'));

        $this->chartUpdaterService->updateChartsForUsers();

        // Nothing to assert... Just for sake of it...
        $this->assertTrue(true);
    }

    /**
     * No asserts, but do check on what calls to expect
     */
    public function testNotUpdatingChart()
    {
        $users = array('test1', 'test2', 'test3');
        $this->owncloudUser->expects($this->once())->method('getSystemUsers')->willReturn($users);
        $this->configService->expects($this->exactly(count($users)))->method('getChartsByUsername')->willReturn(array($this->configMock));
        $this->dataProvider->expects($this->exactly(count($users)))->method('isAllowedToUpdate')->willReturn(false);

        $this->chartUpdaterService->updateChartsForUsers();

        // Nothing to assert... Just for sake of it...
        $this->assertTrue(true);
    }
}