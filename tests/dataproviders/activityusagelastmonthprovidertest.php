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

namespace OCA\ocUsageCharts\Tests\DataProviders;

use OCA\ocUsageCharts\AppInfo\Chart;
use OCA\ocUsageCharts\DataProviders\Activity\ActivityUsageLastMonthProvider;
use OCA\ocUsageCharts\Entity\Activity\ActivityUsageRepository;
use OCA\ocUsageCharts\Entity\ChartConfig;
use OCA\ocUsageCharts\Owncloud\User;

class ActivityUsageLastMonthProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ChartConfig
     */
    private $config;

    /**
     * @var ActivityUsageRepository
     */
    private $repository;

    /**
     * @var User
     */
    private $user;

    /**
     * @var ActivityUsageLastMonthProvider
     */
    private $provider;

    public function setUp()
    {
        $app = new Chart();
        $this->container = $app->getContainer();

        $this->repository = $this
            ->getMockBuilder('OCA\ocUsageCharts\Entity\Activity\ActivityUsageRepository')
            ->disableOriginalConstructor()
            ->getMock();


        $this->config = $this
            ->getMockBuilder('OCA\ocUsageCharts\Entity\ChartConfig')
            ->disableOriginalConstructor()
            ->getMock();

        $this->user = $this
            ->getMockBuilder('OCA\ocUsageCharts\Owncloud\User')
            ->disableOriginalConstructor()
            ->getMock();

        $this->provider = new ActivityUsageLastMonthProvider($this->config, $this->repository, $this->user);
    }

    public function testGetChartUsageRegularUser()
    {
        $mock = $this
            ->getMockBuilder('\OCA\ocUsageCharts\Entity\Activity\ActivityUsage')
            ->disableOriginalConstructor()
            ->getMock();
        $mock->method('getDate')->willReturn(new \DateTime());
        $data = array(
            $mock,
            clone $mock,
            clone $mock
        );
        $username = 'test1';
        $this->config->expects($this->once())->method('getUsername')->willReturn($username);
        $this->user->expects($this->once())->method('getSignedInUsername')->willReturn($username);
        $this->user->expects($this->once())->method('isAdminUser')->willReturn(false);
        $this->repository->expects($this->once())->method('findAfterCreated')->willReturn($data);

        $result = $this->provider->getChartUsage();

        $this->assertArrayHasKey($username, $result);
        $this->assertInstanceOf('\OCA\ocUsageCharts\Entity\Activity\Collections\ActivityDayCollection', $result[$username]);
    }
    public function testGetChartUsageAdminUser()
    {
        $mock = $this
            ->getMockBuilder('\OCA\ocUsageCharts\Entity\Activity\ActivityUsage')
            ->disableOriginalConstructor()
            ->getMock();
        $mock->method('getDate')->willReturn(new \DateTime());
        $data = array(
            $mock,
            clone $mock,
            clone $mock
        );
        $username = 'test1';
        $this->config->expects($this->once())->method('getUsername')->willReturn($username);
        $this->user->expects($this->once())->method('getSignedInUsername')->willReturn($username);
        $this->user->expects($this->once())->method('isAdminUser')->willReturn(true);
        $this->repository->expects($this->once())->method('findAfterCreated')->willReturn($data);

        $result = $this->provider->getChartUsage();

        $this->assertArrayHasKey('', $result);
        $this->assertInstanceOf('\OCA\ocUsageCharts\Entity\Activity\Collections\ActivityDayCollection', $result['']);
    }

    public function testGetChartUsageForUpdate()
    {
        // Should return null, activity is updated through activity App
        $this->assertNull($this->provider->getChartUsageForUpdate());
    }
    public function testIsAllowedToUpdate()
    {
        // Should return false, activity is updated through activity App
        $this->assertFalse($this->provider->isAllowedToUpdate());
    }
    public function testSave()
    {
        $usage = $this
            ->getMockBuilder('\OCA\ocUsageCharts\Entity\Activity\ActivityUsage')
            ->disableOriginalConstructor()
            ->getMock();

        // Should return false, activity is updated through activity App
        $this->assertFalse($this->provider->save($usage));
    }
}