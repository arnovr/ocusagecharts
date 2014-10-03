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

namespace OCA\ocUsageCharts\Tests\Entity;


use OCA\ocUsageCharts\Entity\ChartConfig;

class ChartConfigTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ChartConfig
     */
    private $chartConfig;
    /**
     * @var \DateTime
     */
    private $date;
    public function setUp()
    {
        $this->date = new \DateTime();
        $this->chartConfig = new ChartConfig(1, $this->date, 'test1', 'StorageUsagePerMonth', 'c3js', 'metadata');

        //public function __construct($id, \DateTime $date, $username, $chartType, $chartProvider = 'c3js', $metaData = '')
    }
    public function testGetChartProvider()
    {
        $this->assertEquals('c3js', $this->chartConfig->getChartProvider());
    }

    public function testGetDate()
    {
        $this->assertEquals($this->date, $this->chartConfig->getDate());
    }

    public function testGetChartType()
    {
        $this->assertEquals('StorageUsagePerMonth', $this->chartConfig->getChartType());
    }

    public function testGetId()
    {
        $this->assertEquals('1', $this->chartConfig->getId());
    }

    public function testSetMetaData()
    {
        $this->chartConfig->setMetaData('changed');
        $this->assertEquals('changed', $this->chartConfig->getMetaData());
    }
    public function testGetMetaData()
    {
        $this->assertEquals('metadata', $this->chartConfig->getMetaData());
    }
    public function testGetUsername()
    {
        $this->assertEquals('test1', $this->chartConfig->getUsername());
    }
    public function testFromRow()
    {
        $row = array(
            'id' => '1',
            'created' => $this->date->format('Y-m-d H:i:s'),
            'username' => 'test1',
            'charttype' => 'StorageUsagePerMonth',
            'chartprovider' => 'c3js',
            'metadata' => 'metadata',
        );
        $config = ChartConfig::fromRow($row);
        $this->assertInstanceOf('OCA\ocUsageCharts\Entity\ChartConfig', $config);

        $this->assertEquals('1', $config->getId());
        $this->assertEquals($this->date, $config->getDate());
        $this->assertEquals('test1', $config->getUsername());
        $this->assertEquals('c3js', $config->getChartProvider());
        $this->assertEquals('metadata', $config->getMetaData());
        $this->assertEquals('StorageUsagePerMonth', $config->getChartType());
    }
}
