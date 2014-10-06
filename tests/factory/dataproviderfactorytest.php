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

namespace OCA\ocUsageCharts\Tests\Factory;

use OCA\ocUsageCharts\DataProviderFactory;
use OCA\ocUsageCharts\DataProviders\ChartUsageHelper;
use OCA\ocUsageCharts\Entity\Activity\ActivityUsageRepository;
use OCA\ocUsageCharts\Entity\ChartConfig;
use OCA\ocUsageCharts\Entity\Storage\StorageUsageRepository;
use OCA\ocUsageCharts\Owncloud\Storage;
use OCA\ocUsageCharts\Owncloud\User;

/**
 * @author Arno van Rossum <arno@van-rossum.com>
 */
class DataProviderFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ChartConfig
     */
    protected $config;

    /**
     * @var StorageUsageRepository
     */
    protected $repository;

    /**
     * @var ActivityUsageRepository
     */
    protected $activityRepository;
    /**
     * @var User
     */
    protected $user;

    /**
     * @var Storage
     */
    protected $storage;

    /**
     * @var DataProviderFactory
     */
    protected $factory;

    /**
     * @var ChartUsageHelper
     */
    protected $helper;

    public function setUp()
    {
        $this->config = $this
            ->getMockBuilder('OCA\ocUsageCharts\Entity\ChartConfig')
            ->disableOriginalConstructor()
            ->getMock();


        $this->repository = $this
            ->getMockBuilder('OCA\ocUsageCharts\Entity\Storage\StorageUsageRepository')
            ->disableOriginalConstructor()
            ->getMock();

        $this->activityRepository = $this
            ->getMockBuilder('OCA\ocUsageCharts\Entity\Activity\ActivityUsageRepository')
            ->disableOriginalConstructor()
            ->getMock();


        $this->config = $this
            ->getMockBuilder('\OCA\ocUsageCharts\Entity\ChartConfig')
            ->disableOriginalConstructor()
            ->getMock();

        $this->user = $this
            ->getMockBuilder('\OCA\ocUsageCharts\Owncloud\User')
            ->disableOriginalConstructor()
            ->getMock();

        $this->storage = $this
            ->getMockBuilder('\OCA\ocUsageCharts\Owncloud\Storage')
            ->disableOriginalConstructor()
            ->getMock();
        $this->helper = $this
            ->getMockBuilder('\OCA\ocUsageCharts\DataProviders\ChartUsageHelper')
            ->disableOriginalConstructor()
            ->getMock();



        $this->factory = new DataProviderFactory($this->repository, $this->activityRepository, $this->user, $this->storage, $this->helper);
    }

    public function testGetDataProviderByConfig()
    {
        $mock1 = clone $this->config;
        $mock1->expects($this->once())->method('getChartType')->willReturn("StorageUsageCurrent");
        $provider = $this->factory->getDataProviderByConfig($mock1);
        $this->assertInstanceOf('OCA\ocUsageCharts\DataProviders\Storage\StorageUsageCurrentProvider', $provider);

        $mock2 = clone $this->config;
        $mock2->expects($this->once())->method('getChartType')->willReturn("StorageUsageLastMonth");
        $provider = $this->factory->getDataProviderByConfig($mock2);
        $this->assertInstanceOf('OCA\ocUsageCharts\DataProviders\Storage\StorageUsageLastMonthProvider', $provider);


        $mock3 = clone $this->config;
        $mock3->expects($this->once())->method('getChartType')->willReturn("StorageUsagePerMonth");
        $provider = $this->factory->getDataProviderByConfig($mock3);
        $this->assertInstanceOf('OCA\ocUsageCharts\DataProviders\Storage\StorageUsagePerMonthProvider', $provider);

        $mock4 = clone $this->config;
        $mock4->expects($this->once())->method('getChartType')->willReturn("ActivityUsageLastMonth");
        $provider = $this->factory->getDataProviderByConfig($mock4);
        $this->assertInstanceOf('OCA\ocUsageCharts\DataProviders\Activity\ActivityUsageLastMonthProvider', $provider);

        $mock5 = clone $this->config;
        $mock5->expects($this->once())->method('getChartType')->willReturn("ActivityUsagePerMonth");
        $provider = $this->factory->getDataProviderByConfig($mock5);
        $this->assertInstanceOf('OCA\ocUsageCharts\DataProviders\Activity\ActivityUsagePerMonthProvider', $provider);


        $this->setExpectedException('OCA\ocUsageCharts\Exception\ChartDataProviderException');
        $mock6 = clone $this->config;
        $mock6->expects($this->exactly(2))->method('getChartType')->willReturn("undefined");
        $this->factory->getDataProviderByConfig($mock6);
    }
}
