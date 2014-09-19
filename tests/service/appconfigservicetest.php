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

use OCA\ocUsageCharts\Exception\ChartConfigServiceException;

class AppConfigServiceTest extends \PHPUnit_Framework_TestCase
{
    private $IConfig;
    /**
     * @var AppConfigService
     */
    private $appConfigService;
    private $appName = 'ocusagecharts';
    private $username = 'test1';

    public function setUp()
    {
        $app = new \OCA\ocUsageCharts\AppInfo\Chart();
        $this->container = $app->getContainer();
        $this->IConfig = $this->getMock('OCP\IConfig');
        $this->appConfigService = new AppConfigService($this->IConfig, $this->appName, $this->username);
    }

    /**
     * @expectedException ChartConfigServiceException
     */
    public function testEmptyUsername()
    {
        $this->appConfigService = new AppConfigService($this->IConfig, $this->appName, '');
    }

    public function testGetAppValue()
    {
        $returnValue = 'randomValue';
        $this->IConfig->method('getAppValue')->with($this->appName, 'key')->willReturn($returnValue);
        $this->assertEquals($this->appConfigService->getAppValue('key'), $returnValue);
    }

    public function testSetAppValue()
    {
        $setValue = 'randomValue';
        $this->IConfig->expects($this->once())->method('setAppValue')->with($this->appName, 'key', $setValue);
        $this->appConfigService->setAppValue('key', $setValue);
    }

    public function testGetUserValue()
    {
        $returnValue = 'randomValue';
        $this->IConfig->method('getSystemValue')->with($this->username, $this->appName, 'key')->willReturn($returnValue);
        $this->assertEquals($this->appConfigService->getAppValue('key'), $returnValue);
    }

    public function testSetUserValue()
    {
        $setValue = 'randomValue';
        $this->IConfig->expects($this->once())->method('setSystemValue')->with($this->username, $this->appName, 'key', $setValue);
        $this->appConfigService->setAppValue('key', $setValue);
    }
}