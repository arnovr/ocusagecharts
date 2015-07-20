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

use OCA\ocUsageCharts\Entity\ChartConfig;
use OCA\ocUsageCharts\Entity\ChartConfigRepository;
use OCA\ocUsageCharts\Owncloud\Users;

/**
 * @author Arno van Rossum <arno@van-rossum.com>
 */
class ChartCreator
{
    /**
     * @var ChartConfigRepository
     */
    private $chartConfigRepository;

    /**
     * @var Users
     */
    private $users;

    /**
     * @param ChartConfigRepository $repository
     * @param Users $users
     */
    public function __construct(ChartConfigRepository $repository, Users $users)
    {
        $this->chartConfigRepository = $repository;
        $this->users = $users;
    }

    /**
     * Create the default charts for all system users
     */
    public function createDefaultChartsForSystemUsers()
    {
        $users = $this->users->getSystemUsers();

        foreach($users as $username)
        {
            $this->createDefaultConfigFor($username);
        }
    }

    /**
     * Create default config for a user
     *
     * @param string $username
     */
    public function createDefaultConfigFor($username)
    {
        $charts = $this->getChartsFor($username);

        $types = $this->removeChartsThatAreAlreadySet($charts);

        $this->createDefaultConfigForChartsGiven($username, $types);
    }

    /**
     * @param string $username
     * @return array|\OCA\ocUsageCharts\Entity\ChartConfig[]
     */
    private function getChartsFor($username)
    {
        return $this->chartConfigRepository->findByUsername($username);
    }

    /**
     * @param array $charts
     * @return array
     */
    private function removeChartsThatAreAlreadySet($charts)
    {
        $types = array(
            'StorageUsageCurrent' => '',
            'StorageUsageLastMonth' => json_encode(array('size' => 'gb')),
            'StorageUsagePerMonth' => json_encode(array('size' => 'gb')),
            'ActivityUsagePerMonth' => '',
            'ActivityUsageLastMonth' => ''
        );
        foreach ($charts as $chart) {
            $type = $chart->getChartType();
            if (in_array($type, array_keys($types))) {
                unset($types[$type]);
            }
        }
        return $types;
    }

    /**
     * @param string $username
     * @param array $types
     */
    private function createDefaultConfigForChartsGiven($username, $types)
    {
        foreach ($types as $type => $metaData) {
            $config = new ChartConfig(
                null,
                new \DateTime(),
                $username,
                $type,
                'c3js',
                $metaData
            );
            $this->chartConfigRepository->save($config);
        }
    }
}
