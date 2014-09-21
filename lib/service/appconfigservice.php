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
use OCP\IConfig;

/**
 * @author Arno van Rossum <arno@van-rossum.com>
 */
class AppConfigService
{
    /**
     * @var IConfig
     */
    private $config;

    /**
     * @var string
     */
    private $appName;

    /**
     * @var string
     */
    private $username;

    /**
     * @param IConfig $config
     * @param string $appName
     * @param string $currentUserName
     * @throws \OCA\ocUsageCharts\Exception\ChartConfigServiceException
     */
    public function __construct(IConfig $config, $appName, $currentUserName)
    {
        if ( empty($currentUserName) )
        {
            throw new ChartConfigServiceException("Invalid user given");
        }

        $this->config = $config;
        $this->appName = $appName;
        $this->username = $currentUserName;
    }

    /**
     * Get configuration values for this application
     * @param $key
     * @return string
     */
    public function getAppValue($key)
    {
        return $this->config->getAppValue($this->appName, $key);
    }

    /**
     * Set configuration values for this application
     *
     * @param $key
     * @param $value
     */
    public function setAppValue($key, $value)
    {
        $this->config->setAppValue($this->appName, $key, $value);
    }

    /**
     * Retrieve configuration values for a user
     * @param string $key
     * @return string
     */
    public function getUserValue($key)
    {
        return $this->config->getUserValue($this->username, $this->appName, $key);
    }

    /**
     * Set configuration values for a user
     *
     * @param string $key
     * @param string $value
     */
    public function setUserValue($key, $value)
    {
        $this->config->setUserValue($this->username, $this->appName, $key, $value);
    }
}
