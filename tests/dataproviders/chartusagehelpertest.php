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


use OCA\ocUsageCharts\DataProviders\ChartUsageHelper;

class ChartUsageHelperTest extends \PHPUnit_Framework_TestCase
{
    private $user;
    private $config;
    private $repository;
    private $helper;

    public function setUp()
    {
        $this->config = $this
            ->getMockBuilder('OCA\ocUsageCharts\Entity\ChartConfig')
            ->disableOriginalConstructor()
            ->getMock();

        $this->user = $this
            ->getMockBuilder('OCA\ocUsageCharts\Owncloud\User')
            ->disableOriginalConstructor()
            ->getMock();

        $this->repository = $this
            ->getMockBuilder('OCA\ocUsageCharts\Entity\Storage\StorageUsageRepository')
            ->disableOriginalConstructor()
            ->getMock();

        $this->helper = new ChartUsageHelper();
    }

    public function testGetChartUsageRegularUser()
    {

        $username = 'test1';
        $data = array('test1' => array('1'), 'test2' => array('3'));
        $this->repository->expects($this->once())->method('findAllPerMonth')->willReturn($data);

        $this->user->expects($this->once())->method('getSignedInUsername')->willReturn($username);
        $this->user->expects($this->once())->method('isAdminUser')->willReturn(false);
        $this->config->expects($this->once())->method('getUsername')->willReturn($username);

        $return = $this->helper->getChartUsage($this->user, $this->repository, $this->config);

        $this->assertEquals($return, $data);
    }
    public function testGetChartUsageAdminUser()
    {

        $username = 'test1';
        $data = array('test1' => array('1'), 'test2' => array('3'));
        $this->repository->expects($this->once())->method('findAllPerMonth')->willReturn($data);

        $this->user->expects($this->once())->method('getSignedInUsername')->willReturn($username);
        $this->user->expects($this->once())->method('isAdminUser')->willReturn(true);
        $return = $this->helper->getChartUsage($this->user, $this->repository, $this->config);

        $this->assertEquals($return, $data);
    }
}
