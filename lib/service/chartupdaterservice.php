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

use OCA\ocUsageCharts\Entity\ChartConfig;
use OCA\ocUsageCharts\Owncloud\User;

/**
 * @author Arno van Rossum <arno@van-rossum.com>
 */
class ChartUpdaterService
{
    /**
     * @var ChartDataProvider
     */
    private $dataProvider;

    /**
     * @var ChartConfigService
     */
    private $configService;

    /**
     * @var User
     */
    private $user;

    /**
     * @param ChartDataProvider $dataProvider
     * @param ChartConfigService $configService
     * @param User $user
     */
    public function __construct(ChartDataProvider $dataProvider, ChartConfigService $configService, User $user)
    {
        $this->dataProvider = $dataProvider;
        $this->configService = $configService;
        $this->user = $user;
    }

    /**
     * Update all charts for all users
     */
    public function updateChartsForUsers()
    {
        $users = $this->user->getSystemUsers();
        foreach($users as $userName)
        {
            $this->updateChartsForUser($userName);
        }
    }

    /**
     * Update all charts for one specific user
     * @param string $userName
     */
    private function updateChartsForUser($userName)
    {
        $charts = $this->configService->getChartsByUsername($userName);
        foreach($charts as $chartConfig)
        {
            $this->updateChart($chartConfig);
        }
    }

    /**
     * Update a specific chart
     * @param ChartConfig $chartConfig
     * @return boolean
     */
    private function updateChart(ChartConfig $chartConfig)
    {
        if ( !$this->dataProvider->isAllowedToUpdate($chartConfig) )
        {
            return false;
        }
        $usage = $this->dataProvider->getChartUsageForUpdate($chartConfig);
        return $this->dataProvider->save($chartConfig, $usage);
    }
}