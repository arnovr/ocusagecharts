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

namespace OCA\ocUsageCharts\Controller;
use OCA\ocUsageCharts\Service\ChartDataProvider;
use OCA\ocUsageCharts\Service\ChartType\ChartTypeInterface;
use \OCP\AppFramework\Controller;
use OCP\AppFramework\Http\JSONResponse;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\IRequest;
use \stdClass as ChartConfig;

/**
 * Class ChartController
 * @package OCA\ocUsageCharts\Controller
 * @author Arno van Rossum <arno@van-rossum.com>
 */
class ChartController extends Controller
{
    /**
     * @var ChartDataProvider
     */
    private $chartDataProvider;

    /**
     * @var ChartTypeInterface
     */
    private $chartType;


    /**
     * @param string $appName
     * @param IRequest $request
     * @param ChartDataProvider $chartDataProvider
     * @param ChartTypeInterface $chartType
     */
    public function __construct($appName, IRequest $request, ChartDataProvider $chartDataProvider, ChartTypeInterface $chartType)
    {
        $this->chartDataProvider = $chartDataProvider;
        $this->chartType = $chartType;
        parent::__construct($appName, $request);
    }

    /**
     * JSON Ajax call
     *
     * @return array
     */
    public function loadChart()
    {
        /*
         * 1) Check configuration chart
         * 2) Load from service layer
         * 3) Add to View
         */
        // @TODO Load from given config on user interface
        $chartConfig = new ChartConfig();
        $chartConfig->type = ChartTypeInterface::CHART_GRAPH;
        $chartConfig->days = 7;
        $chartConfig->dtoType = 'StorageUsage';

        return $this->loadJsonData($chartConfig);
    }

    /**
     * Load the correct data
     * @param \stdClass $chartConfig
     * @return string
     */
    private function loadJsonData(ChartConfig $chartConfig)
    {
        return json_encode(
            $this->chartDataProvider->getUsage($chartConfig)
        );
    }



    /**
     * Default charts to load, should be changed in the future for various chart types chosen
     *
     * @return array
     */
    private function chartsToLoad()
    {
        $pieChart = clone $this->chartType;
        $pieChart->setGraphType(ChartTypeInterface::CHART_PIE);
        $pieChart->loadFrontend();

        $graphChart = clone $this->chartType;
        $graphChart->setGraphType(ChartTypeInterface::CHART_GRAPH);
        $graphChart->loadFrontend();

        return array($pieChart, $graphChart);
    }

    /**
     * Frontpage for charts
     * @return array
     */
    public function showCharts()
    {
        $chartTypes = $this->chartsToLoad();

        $chartData = array('chart' => array());
        foreach($chartTypes as $chartType)
        {
            $chartData['chart'][] = $chartType;
        }
        $templateName = 'main';  // will use templates/main.php
        return new TemplateResponse($this->appName, $templateName, $chartData);
    }
}