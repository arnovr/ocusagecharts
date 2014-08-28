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
use OCA\ocUsageCharts\Service\ChartConfigService;
use OCA\ocUsageCharts\Service\ChartService;
use \OCP\AppFramework\Controller;
use OCP\AppFramework\Http\JSONResponse;
use OCP\AppFramework\Http\RedirectResponse;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\IRequest;

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
     * @var ChartConfigService
     */
    private $configService;

    /**
     * @param string $appName
     * @param IRequest $request
     * @param ChartService $chartService
     * @param ChartConfigService $configService
     */
    public function __construct($appName, IRequest $request, ChartService $chartService, ChartConfigService $configService)
    {
        $this->chartService = $chartService;
        $this->configService = $configService;
        parent::__construct($appName, $request);
    }

    /**
     * Entry point for the chart system
     *
     * @NoCSRFRequired
     * @NoAdminRequired
     * @return TemplateResponse
     */
    public function frontpage()
    {
        $charts = $this->configService->getCharts();
        if ( count($charts) == 0 )
        {
            $this->configService->createDefaultConfig();
            $charts = $this->configService->getCharts();
        }
        $id = $charts[0]->getId();
        $url = \OCP\Util::linkToRoute('ocusagecharts.chart.display_chart', array('id' => $id));
        return new RedirectResponse($url);
    }

    /**
     * Show a single chart
     *
     * @NoCSRFRequired
     * @NoAdminRequired
     * @param string $id
     * @return TemplateResponse
     */
    public function displayChart($id)
    {
        $chartConfigs = $this->configService->getCharts();
        foreach($chartConfigs as $config)
        {
            if ( $config->getId() == $id )
            {
                break;
            }
        }
        $chart = $this->chartService->getChartByConfig($config);
        $templateName = 'main';  // will use templates/main.php
        return new TemplateResponse($this->appName, $templateName, array('chart' => $chart, 'configs' => $chartConfigs, 'requesttoken' => \OC_Util::callRegister()));
    }
}