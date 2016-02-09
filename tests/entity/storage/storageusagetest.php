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


use OCA\ocUsageCharts\Entity\Storage\StorageUsage;

class StorageUsageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var StorageUsage
     */
    private $storageUsage;

    /**
     * @var \DateTime
     */
    private $date;

    public function setUp()
    {
        $this->date = new \DateTime();

        $this->storageUsage = new StorageUsage($this->date, 124124124, 'test1');
    }
    public function testGetDate()
    {
        $this->assertEquals($this->date, $this->storageUsage->getDate());
    }
    public function testGetUsage()
    {
        $this->assertEquals(124124124, $this->storageUsage->getUsage());
    }
    public function testGetUsername()
    {
        $this->assertEquals('test1', $this->storageUsage->getUsername());
    }
    public function testFromRow()
    {
        $row = array(
            'usage' => 124124124,
            'created' => $this->date->format('Y-m-d H:i:s'),
            'username' => 'test1',
        );
        $storage = StorageUsage::fromRow($row);
        $this->assertInstanceOf('OCA\ocUsageCharts\Entity\Storage\StorageUsage', $storage);

        $this->assertEquals(124124124, $storage->getUsage());
        $this->assertEquals($this->date, $storage->getDate());
        $this->assertEquals('test1', $storage->getUsername());
    }
}
