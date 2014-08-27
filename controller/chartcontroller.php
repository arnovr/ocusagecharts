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
use OCA\ocUsageCharts\Service\ChartService;
use \OCP\AppFramework\Controller;
use OCP\AppFramework\Http\JSONResponse;
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
     * Entry point for the chart system
     *
     * @NoCSRFRequired
     * @return TemplateResponse
     */
    public function frontpage()
    {
        return $this->displayChart(1);
    }

    /**
     * Show for a single chart
     * @NoCSRFRequired
     * @param string $id
     * @return TemplateResponse
     */
    public function displayChart($id)
    {
        $charts = array($this->chartService->getChart($id));
        $templateName = 'main';  // will use templates/main.php
        return new TemplateResponse($this->appName, $templateName, array('charts' => $charts, 'requesttoken' => \OC_Util::callRegister()));

    }
}