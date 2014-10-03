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


use OCA\ocUsageCharts\DataProviders\Storage\StorageUsagePerMonthProvider;

class StorageUsagePerMonthProviderTest extends StorageUsageBaseTest
{
    /**
     * @var StorageUsagePerMonthProvider
     */
    private $provider;

    private $chartUsageHelper;

    public function setUp()
    {
        parent::setUp();

        $this->chartUsageHelper = $this
            ->getMockBuilder('OCA\ocUsageCharts\DataProviders\ChartUsageHelper')
            ->disableOriginalConstructor()
            ->getMock();
        $this->provider = new StorageUsagePerMonthProvider($this->config, $this->repository, $this->user, $this->storage, $this->chartUsageHelper);
    }

    public function testGetChartUsage()
    {
        $data = array('xxxx');
        $this->chartUsageHelper->expects($this->once())->method('getChartUsage')->willReturn($data);
        $return = $this->provider->getChartUsage();
        $this->assertEquals($data, $return);
    }
}