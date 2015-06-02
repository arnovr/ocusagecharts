<?php
/**
 * Copyright (c) 2015 - Arno van Rossum <arno@van-rossum.com>
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

use Arnovr\Statistics\ContentStatisticsClient;
use Arnovr\Statistics\Dto\StorageInformation;
use OCA\ocUsageCharts\DataProviderFactory;
use OCA\ocUsageCharts\Entity\ChartConfig;
use OCA\ocUsageCharts\Entity\Storage\StorageUsage;
use OCA\ocUsageCharts\Owncloud\Users;

class ContentStatisticsUpdater
{
    /**
     * @var DataProviderFactory
     */
    private $dataProviderFactory;

    /**
     * @var Users
     */
    private $users;

    /**
     * @var ContentStatisticsClient
     */
    private $contentStatisticsClient;

    public function __construct(ContentStatisticsClient $contentStatisticsClient, DataProviderFactory $dataProviderFactory, Users $users)
    {
        $this->contentStatisticsClient = $contentStatisticsClient;
        $this->dataProviderFactory = $dataProviderFactory;
        $this->users = $users;
    }

    /**
     * Update all charts for all users
     */
    public function updateChartsForUsers()
    {
        $users = $this->users->getSystemUsers();
        foreach($users as $userName)
        {
            $this->updateChartsForUser($userName);
        }
    }

    /**
     * @param string $userName
     * @return ChartConfig
     */
    private function getDefaultChartConfig($userName)
    {
        return new ChartConfig(null, new \DateTime(), $userName, 'StorageUsageCurrent');
    }

    /**
     * Update all charts for one specific user
     * @param string $userName
     */
    private function updateChartsForUser($userName)
    {
        $provider = $this->dataProviderFactory->getDataProviderByConfig($this->getDefaultChartConfig($userName));
        $storageUsage = $provider->getChartUsageForUpdate();
        $storageInformation = $this->mapStorageUsageToStorageInformation($storageUsage);

        $this->contentStatisticsClient->store($storageInformation);
    }

    /**
     * @param StorageUsage $storageUsage
     * @return StorageInformation
     */
    private function mapStorageUsageToStorageInformation(StorageUsage $storageUsage)
    {
        return new StorageInformation(
            $storageUsage->getUsage(),
            $storageUsage->getUsername(),
            $storageUsage->getMaximumUsage()
        );
    }
}