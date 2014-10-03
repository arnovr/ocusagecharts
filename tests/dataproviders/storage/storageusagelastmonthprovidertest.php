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


use OCA\ocUsageCharts\DataProviders\Storage\StorageUsageLastMonthProvider;

class StorageUsageLastMonthProviderTest extends StorageUsageBaseTest
{
    /**
     * @var StorageUsageLastMonthProvider
     */
    private $provider;

    public function setUp()
    {
        parent::setUp();
        $this->provider = new StorageUsageLastMonthProvider($this->config, $this->repository, $this->user, $this->storage);
    }

    public function testGetChartUsageForAdminUser()
    {
        $username = 'test1';
        $data = array('test1' => array('1'), 'test2' => array('3'));
        $this->repository->expects($this->once())->method('findAllAfterCreated')->willReturn($data);

        $this->user->expects($this->once())->method('getSignedInUsername')->willReturn($username);
        $this->user->expects($this->once())->method('isAdminUser')->willReturn(true);

        $return = $this->provider->getChartUsage();

        $this->assertEquals($return, $data);
    }

    public function testGetChartUsageForRegularUser()
    {

        $username = 'test1';
        $data = array('test1' => array('2', '1'));
        $this->repository->expects($this->once())->method('findAfterCreated')->willReturn($data);

        $this->user->expects($this->once())->method('getSignedInUsername')->willReturn($username);
        $this->user->expects($this->once())->method('isAdminUser')->willReturn(false);

        $this->config->expects($this->exactly(2))->method('getUsername')->willReturn($username);

        $return = $this->provider->getChartUsage();

        $this->assertEquals(array($username => $data), $return);

    }
}