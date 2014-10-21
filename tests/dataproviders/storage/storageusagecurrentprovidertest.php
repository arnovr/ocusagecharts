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

use OCA\ocUsageCharts\DataProviders\Storage\StorageUsageCurrentProvider;

class StorageUsageCurrentProviderTest extends StorageUsageBaseTest
{
    /**
     * @var StorageUsageCurrentProvider
     */
    private $provider;

    public function setUp()
    {
        parent::setUp();
        $this->provider = new StorageUsageCurrentProvider($this->config, $this->repository, $this->user, $this->storage);
    }

    public function testGetChartUsageForRegularUser()
    {
        $username = 'test1';
        $storageInfo = array(
            'free' => (1024 * 1024 * 1024),
            'used' => (1024 * 1024 * 1024)
        );
        $this->storage->expects($this->once())->method('getCurrentStorageUsageForSignedInUser')->willReturn($storageInfo);

        $this->user->expects($this->once())->method('getSignedInUsername')->willReturn($username);
        $this->user->expects($this->once())->method('isAdminUser')->willReturn(false);

        $data = $this->provider->getChartUsage();
        $this->assertEquals($data['free'], (float) 1024);
        $this->assertEquals($data['used'], (float) 1024);
    }
    public function testGetChartUsageForAdminUser()
    {
        $username = 'test1';
        $storageInfo = array(
            'free' => (1024 * 1024 * 1024),
            'used' => (1024 * 1024 * 1024)
        );
        $this->storage->expects($this->once())->method('getCurrentStorageUsageForSignedInUser')->willReturn($storageInfo);

        $this->user->expects($this->once())->method('getSignedInUsername')->willReturn($username);
        $this->user->expects($this->once())->method('isAdminUser')->willReturn(true);
        $usageMock = $this
            ->getMockBuilder('OCA\ocUsageCharts\Entity\Storage\StorageUsage')
            ->disableOriginalConstructor()
            ->getMock();
        $usageMock->method('getUsage')->willReturn(1024 * 1024 * 1024);
        $returnItems = array(
            'test1' => array($usageMock),
            'test2' => array(clone $usageMock),
            'admin' => array(clone $usageMock)
        );
        $this->repository->expects($this->once())->method('findAllWithLimit')->willReturn($returnItems);

        $this->user->expects($this->at(2))->method('getDisplayName')->with('test1')->will($this->returnValue('test name1'));
        $this->user->expects($this->at(3))->method('getDisplayName')->with('test2')->will($this->returnValue('test name2'));
        $this->user->expects($this->at(4))->method('getDisplayName')->with('admin')->will($this->returnValue('administrator'));

        $data = $this->provider->getChartUsage();
        $this->assertEquals($data['free'], (float) 1024);
        $this->assertEquals($data['test name1'], (float) 1024);
        $this->assertEquals($data['test name2'], (float) 1024);
        $this->assertEquals($data['administrator'], (float) 1024);
    }
}