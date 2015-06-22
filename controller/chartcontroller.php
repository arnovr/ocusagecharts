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

use OCA\ocUsageCharts\Exception\ChartServiceException;
use OCA\ocUsageCharts\Owncloud\User;
use OCA\ocUsageCharts\Service\ChartConfigService;
use OCA\ocUsageCharts\Service\ChartCreator;
use OCA\ocUsageCharts\Service\ChartService;
use \OCP\AppFramework\Controller;
use OCP\AppFramework\Http\RedirectResponse;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\IRequest;

/**
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
     * @var ChartCreator
     */
    private $chartCreator;

    /**
     * @var User
     */
    private $user;

    /**
     * @var false|string
     */
    private $redirectUrl;

    /**
     * @param string $appName
     * @param IRequest $request
     * @param ChartService $chartService
     * @param ChartConfigService $configService
     * @param ChartCreator $chartCreator
     * @param User $user
     */
    public function __construct($appName, IRequest $request, ChartService $chartService, ChartConfigService $configService, ChartCreator $chartCreator, User $user, $redirectUrl = false)
    {
        $this->chartService = $chartService;
        $this->configService = $configService;
        $this->chartCreator = $chartCreator;
        $this->user = $user;
        $this->redirectUrl = $redirectUrl;
        parent::__construct($appName, $request);
    }

    /**
     * Entry point for the chart system
     *
     * @NoCSRFRequired
     * @NoAdminRequired
     * @return TemplateResponse|RedirectResponse
     */
    public function frontpage()
    {
        if (!empty($this->redirectUrl)&&$this->user->isAdminUser($this->user->getSignedInUsername())) {
            $templateName = 'kibana';
            return new TemplateResponse($this->appName, $templateName, array('url' => $this->redirectUrl, 'requesttoken' => \OC_Util::callRegister()));
        }
        $this->createDefaultChartsForLoggedInUser();

        $charts = $this->configService->getChartsForLoggedInUser();
        $id = $charts[0]->getId();
        $url = \OCP\Util::linkToRoute('ocusagecharts.chart.display_chart', array('id' => $id));
        return new RedirectResponse($url);
    }

    /**
     * Create default charts for the logged in user
     */
    private function createDefaultChartsForLoggedInUser()
    {
        $this->chartCreator->createDefaultConfigFor($this->user->getSignedInUsername());
    }

    /**
     * Show a single chart
     *
     * @NoCSRFRequired
     * @NoAdminRequired
     * @param string $id
     * @throws \OCA\ocUsageCharts\Exception\ChartServiceException
     *
     * @return TemplateResponse
     */
    public function displayChart($id)
    {
        $selectedConfig = null;
        $chartConfigs = $this->configService->getChartsForLoggedInUser();
        foreach($chartConfigs as $config)
        {
            if ( $config->getId() == $id )
            {
                $selectedConfig = $config;
                break;
            }
        }
        if ( is_null($selectedConfig) )
        {
            throw new ChartServiceException('No config found for selected ID');
        }

        $chart = $this->chartService->getChartByConfig($selectedConfig);
        $templateName = 'main';  // will use templates/main.php
        return new TemplateResponse($this->appName, $templateName, array('chart' => $chart, 'configs' => $chartConfigs, 'requesttoken' => \OC_Util::callRegister()));
    }
}