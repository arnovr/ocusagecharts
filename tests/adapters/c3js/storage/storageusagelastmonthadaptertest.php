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

namespace OCA\ocUsageCharts\Tests\Adapters\c3js\Storage;
use OCA\ocUsageCharts\Adapters\c3js\Storage\StorageUsageLastMonthAdapter;
use OCA\ocUsageCharts\Entity\Storage\StorageUsage;
use OCA\ocUsageCharts\Tests\Adapters\c3js\c3jsBaseTest;


/**
 * @author Arno van Rossum <arno@van-rossum.com>
 */
class StorageUsageLastMonthAdapterTest extends c3jsBaseTest
{
    /**
     * @var StorageUsageLastMonthAdapter
     */
    protected $adapter;
    public function setUp()
    {
        parent::setUp();
        $this->adapter = new StorageUsageLastMonthAdapter($this->config, $this->user);
    }
    public function testFormatData()
    {
        $this->user->expects($this->once())->method('getDisplayName')->willReturn('test1');
        $data = array('test1' => array(
            new StorageUsage(new \DateTime("-1 day"), 234234, 'test1'),
            new StorageUsage(new \DateTime(), 23423422, 'test1')
        ));
        $result = $this->adapter->formatData($data);
        $this->assertArrayHasKey('x', $result);
        $this->assertArrayHasKey('test1', $result);
        $this->assertCount(2, $result['x']);
        $this->assertCount(2, $result['test1']);
    }
}
