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
use OCA\ocUsageCharts\ChartTypeAdapterFactory;

/**
 * @author Arno van Rossum <arno@van-rossum.com>
 */
class ChartTypeAdapterFactoryTest extends \PHPUnit_Framework_TestCase
{
    private $config;
    private $user;

    public function setUp()
    {
        $this->config = $this
            ->getMockBuilder('OCA\ocUsageCharts\Entity\ChartConfig')
            ->disableOriginalConstructor()
            ->getMock();

        $this->user = $this
            ->getMockBuilder('\OCA\ocUsageCharts\Owncloud\User')
            ->disableOriginalConstructor()
            ->getMock();

        $this->config->expects($this->exactly(5))->method('getChartProvider')->willReturn('c3js');
    }
    public function testGetChartTypeAdapterByConfigTest()
    {
        $factory = new ChartTypeAdapterFactory($this->user);

        $mock1 = clone $this->config;
        $mock1->expects($this->once())->method('getChartType')->willReturn("StorageUsageCurrent");
        $adapter = $factory->getChartTypeAdapterByConfig($mock1);
        $this->assertInstanceOf('OCA\ocUsageCharts\Adapters\c3js\Storage\StorageUsageCurrentAdapter', $adapter);


        $mock2 = clone $this->config;
        $mock2->expects($this->once())->method('getChartType')->willReturn("StorageUsageLastMonth");
        $adapter = $factory->getChartTypeAdapterByConfig($mock2);
        $this->assertInstanceOf('OCA\ocUsageCharts\Adapters\c3js\Storage\StorageUsageLastMonthAdapter', $adapter);


        $mock3 = clone $this->config;
        $mock3->expects($this->once())->method('getChartType')->willReturn("StorageUsagePerMonth");
        $adapter = $factory->getChartTypeAdapterByConfig($mock3);
        $this->assertInstanceOf('OCA\ocUsageCharts\Adapters\c3js\Storage\StorageUsagePerMonthAdapter', $adapter);

        $mock4 = clone $this->config;
        $mock4->expects($this->once())->method('getChartType')->willReturn("ActivityUsageLastMonth");
        $adapter = $factory->getChartTypeAdapterByConfig($mock4);
        $this->assertInstanceOf('OCA\ocUsageCharts\Adapters\c3js\Activity\ActivityUsageLastMonthAdapter', $adapter);

        $mock5 = clone $this->config;
        $mock5->expects($this->once())->method('getChartType')->willReturn("ActivityUsagePerMonth");
        $adapter = $factory->getChartTypeAdapterByConfig($mock5);
        $this->assertInstanceOf('OCA\ocUsageCharts\Adapters\c3js\Activity\ActivityUsagePerMonthAdapter', $adapter);

        $this->setExpectedException('OCA\ocUsageCharts\Exception\ChartTypeAdapterException');
        $mock6 = clone $this->config;
        $mock6->expects($this->exactly(2))->method('getChartType')->willReturn("undefined");
        $factory->getChartTypeAdapterByConfig($mock6);
    }
}
