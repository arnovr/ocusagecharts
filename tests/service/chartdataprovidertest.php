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

class ChartDataProviderTest extends \PHPUnit_Framework_TestCase
{

    private $container;
    private $dataProvider;
    private $configMock;

    public function setUp()
    {
        $app = new \OCA\ocUsageCharts\AppInfo\Chart();
        $this->container = $app->getContainer();
        $this->dataProvider = new ChartDataProvider($this->container);
        $this->configMock = new \OCA\ocUsageCharts\Entity\ChartConfig(100, new \DateTime(), 'test1', 'StorageUsageLastMonth', 'c3js');
    }

    /*
    public function testGetChartUsageForUpdate()
    {
        // @TODO
        //$result = $this->dataProvider->getChartUsageForUpdate($this->configMock);
    }
    */

    public function testSaveDataUsage()
    {
        $usageNumber = 2324235;
        $created = new \DateTime();
        $username = 'test1';
        $usage = $this->getMock('\OCA\ocUsageCharts\Entity\StorageUsage', array(), array(
                $created,
                $usageNumber,
                $username
            ));

        $usage->method('getUsage')->willReturn(2324235);
        $usage->method('getDate')->willReturn($created);
        $usage->method('getUsername')->willReturn($username);
        $saved = $this->dataProvider->save($this->configMock, $usage);
        $this->assertTrue($saved);
    }

    public function testGetChartUsage()
    {
        $data = $this->dataProvider->getChartUsage($this->configMock);
        $this->assertArrayHasKey('x', $data);
        $this->assertArrayHasKey($this->configMock->getUsername(), $data);
    }
}