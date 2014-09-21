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
use OCA\ocUsageCharts\Owncloud\User;
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
     * @var User
     */
    private $user;

    /**
     * @param IConfig $config
     * @param string $appName
     * @param \OCA\ocUsageCharts\Owncloud\User $user
     */
    public function __construct(IConfig $config, $appName, User $user)
    {
        $this->config = $config;
        $this->appName = $appName;
        $this->user = $user;
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
     * Retrieve the username
     * Throw exception because:
     * AppConfigService could be injected on non logged in places,
     * This exception handles the feature toggle for getUserValue
     * @return string
     * @throws \OCA\ocUsageCharts\Exception\ChartConfigServiceException
     */
    private function getUsername()
    {
        $userName = $this->user->getSignedInUsername();
        if ( empty($userName) )
        {
            throw new ChartConfigServiceException("Invalid user given");
        }
        return $userName;
    }

    /**
     * @TODO I think the username should be set through this method, and not through
     * internal owncloud dependencies
     * Same goes for setUserValue
     *
     * Retrieve configuration values for a user
     * @param string $key
     * @throws \OCA\ocUsageCharts\Exception\ChartConfigServiceException
     * @return string
     */
    public function getUserValue($key)
    {
        return $this->config->getUserValue($this->getUsername(), $this->appName, $key);
    }

    /**
     * Set configuration values for a user
     *
     * @param string $key
     * @param string $value
     */
    public function setUserValue($key, $value)
    {
        $this->config->setUserValue($this->getUsername(), $this->appName, $key, $value);
    }
}
