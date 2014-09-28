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

namespace OCA\ocUsageCharts\DataProviders\Activity;

use OCA\ocUsageCharts\DataProviders\DataProviderInterface;
use OCA\ocUsageCharts\Entity\Activity\Collections\ActivityDayCollection;
use OCA\ocUsageCharts\Entity\Activity\ActivityUsageRepository;
use OCA\ocUsageCharts\Entity\ChartConfig;
use OCA\ocUsageCharts\Owncloud\User;

/**
 * @author Arno van Rossum <arno@van-rossum.com>
 */
class ActivityUsageLastMonthProvider implements DataProviderInterface
{
    /**
     * @var ChartConfig
     */
    protected $chartConfig;

    /**
     * @var ActivityUsageRepository
     */
    protected $repository;

    /**
     * @var User
     */
    protected $user;

    /**
     * @param ChartConfig $chartConfig
     * @param ActivityUsageRepository $repository
     * @param User $user
     */
    public function __construct(ChartConfig $chartConfig, ActivityUsageRepository $repository, User $user)
    {
        $this->chartConfig = $chartConfig;
        $this->repository = $repository;
        $this->user = $user;
    }

    /**
     * Return the chart data you want to return based on the ChartConfig
     *
     * @return array
     */
    public function getChartUsage()
    {
        $return = array();
        if ( $this->user->isAdminUser($this->user->getSignedInUsername()) )
        {
            $users = $this->user->getSystemUsers();
            foreach($users as $username)
            {
                $return[$username] = $this->getCollectionByUsername($username);
            }
        }
        else
        {
            $username = $this->chartConfig->getUsername();
            $return[$username] = $this->getCollectionByUsername($username);
        }
        return $return;
    }

    /**
     * Return a collection off 1 month ago based on the username supplied
     *
     * @param string $username
     * @return ActivityDayCollection
     */
    private function getCollectionByUsername($username)
    {
        $created = new \DateTime("-1 month");
        $data = $this->repository->findAfterCreatedByUsername($created, $username);
        $collection = new ActivityDayCollection();
        foreach($data as $activityUsage)
        {
            $collection->add($activityUsage);
        }
        return $collection;
    }

    /**
     * NEVER UPDATE, this is handled by activity app
     *
     * @return boolean
     */
    public function isAllowedToUpdate()
    {
        return false;
    }

    /**
     * See isAllowedToUpdate for more information
     *
     * @return null
     */
    public function getChartUsageForUpdate()
    {
        return null;
    }

    /**
     * See isAllowedToUpdate for more information
     *
     * @param $usage
     * @return boolean
     */
    public function save($usage)
    {
        return false;
    }
}