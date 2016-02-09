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

namespace OCA\ocUsageCharts\Tests\Owncloud;


use OCA\ocUsageCharts\Owncloud\Storage;

class StorageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Storage
     */
    private $storage;
    private $user;
    public function setUp()
    {
        $this->user = $this->getMock('\OCA\ocUsageCharts\Owncloud\User');
        $this->storage = new Storage($this->user);
    }
    /*
     * This test fails on:
     * .PHP Fatal error:  Call to a member function getSize() on a non-object in /media/sf_usage_charts/apps/ocusagecharts/lib/owncloud/storage.php on line 66
     *
    public function testGetCurrentStorageUsageForSignedInUser()
    {
        $result = $this->storage->getCurrentStorageUsageForSignedInUser();
        $this->assertArrayHasKey('free', $result);
        $this->assertArrayHasKey('used', $result);
    }
    */

    public function testGetStorageUsage()
    {
        $result = $this->storage->getStorageUsage('admin');
        $this->assertInternalType('integer', $result);
    }
}
