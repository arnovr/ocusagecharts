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
use OCA\ocUsageCharts\DataProviders\Storage\StorageUsageBase;
use OCA\ocUsageCharts\Entity\ChartConfig;
use OCA\ocUsageCharts\Entity\StorageUsageRepository;
use OCA\ocUsageCharts\Owncloud\Storage;
use OCA\ocUsageCharts\Owncloud\User;

class StorageUsageBaseTest extends \PHPUnit_Framework_TestCase
{
    private $container;

    /**
     * @var ChartConfig
     */
    protected $config;

    /**
     * @var StorageUsageRepository
     */
    protected $repository;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var Storage
     */
    protected $storage;

    /**
     * @var StorageUsageBase
     */
    private $abstractProvider;

    public function setUp()
    {
        $app = new Chart();
        $this->container = $app->getContainer();

        $this->repository = $this
            ->getMockBuilder('OCA\ocUsageCharts\Entity\StorageUsageRepository')
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

        $this->abstractProvider = $this->getMockForAbstractClass('OCA\ocUsageCharts\DataProviders\Storage\StorageUsageBase');
    }

    public function isAllowedToUpdateTrueTest()
    {
        $username = 'test1';
        $this->config->expects($this->once())->method('getUsername')->willReturn($username);

        $results = array('testdata');
        $this->repository->expects($this->once())->method('findAfterCreated')->willReturn($results);

        $return = $this->abstractProvider->isAllowedToUpdate();

        $this->assertTrue($return);
    }

    public function isAllowedToUpdateFalseTest()
    {
        $username = 'test1';
        $this->config->expects($this->once())->method('getUsername')->willReturn($username);

        $results = array();
        $this->repository->expects($this->once())->method('findAfterCreated')->willReturn($results);

        $return = $this->abstractProvider->isAllowedToUpdate();

        $this->assertFalse($return);
    }


    public function getChartUsageForUpdateTest()
    {
        $username = 'test1';
        $usage = 2314234;
        $this->config->expects($this->once())->method('getUsername')->willReturn($username);
        $this->storage->expects($this->once())->method('getStorageUsage')->willReturn($usage);

        $result = $this->abstractProvider->getChartUsageForUpdate();
        $this->assertInstanceOf('OCA\ocUsageCharts\Entity\StorageUsage', $result);
        $this->assertEquals($username, $result->getUsername());
        $this->assertEquals($usage, $result->getUsage());
    }

    public function saveTest()
    {
        $usageMock = $this->getMock('OCA\ocUsageCharts\Entity\StorageUsage');
        $this->repository->expects($this->once())->method('save')->willReturn(true);
        $result = $this->abstractProvider->save($usageMock);
        $this->assertTrue($result);

        $this->repository->expects($this->once())->method('save')->willReturn(false);
        $result = $this->abstractProvider->save($usageMock);
        $this->assertFalse($result);
    }
}