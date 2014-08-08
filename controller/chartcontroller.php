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
use OCA\ocUsageCharts\Service\ChartService;
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
     * @var ChartService
     */
    private $chartService;


    /**
     * @param string $appName
     * @param IRequest $request
     * @param ChartService $chartService
     */
    public function __construct($appName, IRequest $request, ChartService $chartService)
    {
        $this->chartService = $chartService;
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
        // TODO
        return json_encode($chartConfig
            //$this->chartDataProvider->getUsage($chartConfig)
        );
    }






    /**
     * Frontpage for charts
     * @return TemplateResponse
     */
    public function showCharts()
    {
        $chartTypes = $this->chartService->getCharts();

        $chartData = array('chart' => array());
        foreach($chartTypes as $chartType)
        {
            $chartData['chart'][] = $chartType;
        }
        $templateName = 'main';  // will use templates/main.php
        return new TemplateResponse($this->appName, $templateName, $chartData);
    }
}