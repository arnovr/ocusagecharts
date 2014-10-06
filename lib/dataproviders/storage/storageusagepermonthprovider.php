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

namespace OCA\ocUsageCharts\DataProviders\Storage;

use OCA\ocUsageCharts\DataProviders\ChartUsageHelper;
use OCA\ocUsageCharts\DataProviders\DataProviderInterface;
use OCA\ocUsageCharts\Entity\ChartConfig;
use OCA\ocUsageCharts\Entity\Storage\StorageUsageRepository;
use OCA\ocUsageCharts\Owncloud\Storage;
use OCA\ocUsageCharts\Owncloud\User;

/**
 * @author Arno van Rossum <arno@van-rossum.com>
 */
class StorageUsagePerMonthProvider extends StorageUsageBase implements DataProviderInterface
{
    /**
     * @var ChartUsageHelper
     */
    private $chartUsageHelper;

    /**
     * @param ChartConfig $chartConfig
     * @param StorageUsageRepository $repository
     * @param User $user
     * @param Storage $storage
     * @param ChartUsageHelper $chartUsageHelper
     */
    public function __construct(ChartConfig $chartConfig, StorageUsageRepository $repository, User $user, Storage $storage, ChartUsageHelper $chartUsageHelper)
    {
        parent::__construct($chartConfig, $repository, $user, $storage);
        $this->chartUsageHelper = $chartUsageHelper;
    }

    /**
     * Return the chart data you want to return based on the ChartConfig
     *
     * @return mixed
     */
    public function getChartUsage()
    {
        return $this->chartUsageHelper->getChartUsage($this->user, $this->repository, $this->chartConfig);
    }
}