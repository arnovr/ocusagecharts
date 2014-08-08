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

namespace OCA\ocUsageCharts\Service\ChartType;

use OCA\ocUsageCharts\Service\ChartDataProvider;
use \stdClass as ChartDataConfig;

/**
 * @author Arno van Rossum <arno@van-rossum.com>
 */
class c3js implements ChartTypeInterface
{
    private $templateName = 'c3js';

    private $id;

    /**
     * @var array
     */
    private $usage;

    /**
     * @var string
     */
    private $graphType;

    /**
     * @var ChartDataProvider
     */
    private $provider;

    public function __construct($id, ChartDataProvider $provider)
    {
        $this->id = $id;
        $this->provider = $provider;
    }

    /**
     * @param string $graphType
     */
    public function setGraphType($graphType)
    {
        $this->graphType = $graphType;
    }

    public function getGraphType()
    {
        return $this->graphType;
    }

    /**
     * Load the frontend files needed
     */
    public function loadFrontend()
    {
        \OCP\Util::addStyle('ocUsageCharts', 'c3js/c3');
        \OCP\Util::addScript('ocUsageCharts', 'c3js/d3.min');
        \OCP\Util::addScript('ocUsageCharts', 'c3js/c3.min');
    }

    public function loadChart(array $usage)
    {
        $this->usage = $usage;
    }

    /**
     * Template name
     * @return string
     */
    public function getTemplateName()
    {
        return $this->templateName;
    }


    public function getId()
    {
        return $this->id;
    }

    /**
     * Return the usage used
     *
     * @return mixed
     */
    public function getUsage()
    {
        //@TODO Retrieve correct config here
        $config = new ChartDataConfig();
        $config->type = 'storage';
        $config->id = $this->getId();
        $config->user = 'arno';
        return $this->parse($this->provider->getUsage($config));
    }

    /**
     * @param mixed $data
     * @return mixed
     */
    private function parse($data)
    {
        $adapt = new c3jsAdapter();
        return $adapt->parseData($data);
    }

}