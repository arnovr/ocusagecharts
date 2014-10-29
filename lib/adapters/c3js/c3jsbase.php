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

namespace OCA\ocUsageCharts\Adapters\c3js;

use OCA\ocUsageCharts\Entity\ChartConfig;
use OCA\ocUsageCharts\Owncloud\User;


/**
 * @author Arno van Rossum <arno@van-rossum.com>
 */
abstract class c3jsBase
{
    /**
     * @var ChartConfig
     */
    private $config;

    /**
     * @var User
     */
    protected $user;

    /**
     * @param ChartConfig $chartConfig
     * @param User $user
     */
    public function __construct(ChartConfig $chartConfig, User $user)
    {
        $this->config = $chartConfig;
        $this->user = $user;
    }

    /**
     * Load the frontend files needed
     */
    public function loadFrontend()
    {
        \OCP\Util::addStyle('ocusagecharts', 'c3/c3');
        \OCP\Util::addScript('ocusagecharts', 'd3/d3.min');
        \OCP\Util::addScript('ocusagecharts', 'c3/c3.min');
        \OCP\Util::addScript('ocusagecharts', 'c3_initGraphs');
    }

    public function getConfig()
    {
        return $this->config;
    }
}