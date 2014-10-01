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

namespace OCA\ocUsageCharts;

use OCA\ocUsageCharts\DataProviders\Activity\ActivityUsageLastMonthProvider;
use OCA\ocUsageCharts\DataProviders\Activity\ActivityUsagePerMonthProvider;
use OCA\ocUsageCharts\DataProviders\ChartUsageHelper;
use OCA\ocUsageCharts\DataProviders\Storage\StorageUsageCurrentProvider;
use OCA\ocUsageCharts\DataProviders\Storage\StorageUsageLastMonthProvider;
use OCA\ocUsageCharts\DataProviders\Storage\StorageUsagePerMonthProvider;
use OCA\ocUsageCharts\Entity\Activity\ActivityUsageRepository;
use OCA\ocUsageCharts\Entity\ChartConfig;
use OCA\ocUsageCharts\Entity\Storage\StorageUsageRepository;
use OCA\ocUsageCharts\Exception\ChartDataProviderException;
use OCA\ocUsageCharts\Owncloud\Storage;
use OCA\ocUsageCharts\Owncloud\User;

/**
 * @author Arno van Rossum <arno@van-rossum.com>
 */
class DataProviderFactory
{
    /**
     * @var StorageUsageRepository
     */
    private $repository;

    /**
     * @var ActivityUsageRepository
     */
    private $activityUsageRepository;

    /**
     * @var User
     */
    private $user;

    /**
     * @var Storage
     */
    private $storage;

    /**
     * @var ChartUsageHelper
     */
    private $chartUsageHelper;

    /**
     * @param StorageUsageRepository $repository
     * @param ActivityUsageRepository $activityUsageRepository
     * @param User $user
     * @param Storage $storage
     * @param ChartUsageHelper $chartUsageHelper
     */
    public function __construct(StorageUsageRepository $repository, ActivityUsageRepository $activityUsageRepository, User $user, Storage $storage, ChartUsageHelper $chartUsageHelper)
    {
        $this->repository = $repository;
        $this->user = $user;
        $this->storage = $storage;
        $this->activityUsageRepository = $activityUsageRepository;
        $this->chartUsageHelper = $chartUsageHelper;
    }

    /**
     * @param ChartConfig $config
     * @return StorageUsageCurrentProvider|StorageUsageLastMonthProvider|StorageUsagePerMonthProvider
     * @throws Exception\ChartDataProviderException
     */
    public function getDataProviderByConfig(ChartConfig $config)
    {
        switch($config->getChartType())
        {
            case 'StorageUsageCurrent':
                $provider = new StorageUsageCurrentProvider($config, $this->repository, $this->user, $this->storage);
                break;
            case 'StorageUsageLastMonth':
                $provider = new StorageUsageLastMonthProvider($config, $this->repository, $this->user, $this->storage);
                break;
            case 'StorageUsagePerMonth':
                $provider = new StorageUsagePerMonthProvider($config, $this->repository, $this->user, $this->storage, $this->chartUsageHelper);
                break;
            case 'ActivityUsageLastMonth':
                $provider = new ActivityUsageLastMonthProvider($config, $this->activityUsageRepository, $this->user);
                break;
            case 'ActivityUsagePerMonth':
                $provider = new ActivityUsagePerMonthProvider($config, $this->activityUsageRepository, $this->user, $this->chartUsageHelper);
                break;
            default:
                throw new ChartDataProviderException("DataProvider for " . $config->getChartType() . ' does not exist.');
        }
        return $provider;
    }
}
