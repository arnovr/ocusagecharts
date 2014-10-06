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

use OCA\ocUsageCharts\DataProviders\DataProviderInterface;
use OCA\ocUsageCharts\Entity\ChartConfig;
use OCA\ocUsageCharts\Entity\Storage\StorageUsage;
use OCA\ocUsageCharts\Entity\Storage\StorageUsageRepository;
use OCA\ocUsageCharts\Owncloud\Storage;
use OCA\ocUsageCharts\Owncloud\User;

/**
 * @author Arno van Rossum <arno@van-rossum.com>
 */
abstract class StorageUsageBase implements DataProviderInterface
{
    /**
     * @var ChartConfig
     */
    protected $chartConfig;

    /**
     * @var StorageUsageRepository
     */
    protected $repository;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var Storage
     */
    protected $storage;

    /**
     * @param ChartConfig $chartConfig
     * @param StorageUsageRepository $repository
     * @param User $user
     * @param Storage $storage
     */
    public function __construct(ChartConfig $chartConfig, StorageUsageRepository $repository, User $user, Storage $storage)
    {
        $this->chartConfig = $chartConfig;
        $this->repository = $repository;
        $this->user = $user;
        $this->storage = $storage;
    }

    /**
     * Check if the cron may update.
     *
     * @return boolean
     */
    public function isAllowedToUpdate()
    {
        $userName = $this->chartConfig->getUsername();
        $created = new \DateTime();
        $created->setTime(0,0,0);
        $results = $this->repository->findAfterCreated($userName, $created);

        // The cron has already ran today, therefor ignoring a current update, only update once a day.
        if ( count($results) === 0 )
        {
            return true;
        }
        return false;
    }

    /**
     * Return a CURRENT storage usage for a user
     *
     * @return StorageUsage
     */
    public function getChartUsageForUpdate()
    {
        $userName = $this->chartConfig->getUsername();
        return new StorageUsage(new \Datetime(), $this->storage->getStorageUsage($userName), $userName);
    }

    /**
     * Save the usage
     *
     * @param StorageUsage $usage
     * @return boolean
     */
    public function save($usage)
    {
        return $this->repository->save($usage);
    }
}