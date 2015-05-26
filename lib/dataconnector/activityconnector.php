<?php
/**
 * Copyright (c) 2015 - Arno van Rossum <arno@van-rossum.com>
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

namespace OCA\ocUsageCharts\DataConnector;

use OCA\ocUsageCharts\Dto\ActivityInformation;

class ActivityConnector
{
    /**
     * @var \GuzzleHttp\Client
     */
    private $httpClient;

    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @param \GuzzleHttp\Client $httpClient
     * @param string $url
     * @param string $username
     * @param string $password
     */
    public function __construct(\GuzzleHttp\Client $httpClient, $url, $username, $password)
    {
        $this->httpClient = $httpClient;
        $this->url = $url;
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * @param ActivityInformation $activityInformation
     */
    public function activity(ActivityInformation $activityInformation)
    {
        $url = $this->url . '/api/create/activity';
        $options = [
            'auth' => [$this->username, $this->password],
            'json' => $activityInformation
        ];
        $this->httpClient->post($url, $options);
    }
}
