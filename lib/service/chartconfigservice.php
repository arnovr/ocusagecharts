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

use OCA\ocUsageCharts\Entity\ChartConfigRepository;
use OCA\ocUsageCharts\Entity\ChartConfig;
use OCA\ocUsageCharts\Exception\ChartConfigServiceException;
use OCA\ocUsageCharts\Owncloud\User;

/**
 * @author Arno van Rossum <arno@van-rossum.com>
 */
class ChartConfigService
{
    /**
     * @var ChartConfigRepository
     */
    private $repository;

    /**
     * @var User
     */
    private $user;

    public function __construct(ChartConfigRepository $repository, User $user)
    {
        $this->repository = $repository;
        $this->user = $user;
    }

    /**
     * Get all charts for the loggedin user
     *
     * @return array|\OCA\ocUsageCharts\Entity\ChartConfig[]
     */
    public function getCharts()
    {
        return $this->repository->findByUsername($this->user->getSignedInUsername());
    }

    /**
     * Get all charts by username
     *
     * @param string $userName
     * @return array|\OCA\ocUsageCharts\Entity\ChartConfig[]
     */
    public function getChartsByUsername($userName)
    {
        return $this->repository->findByUsername($userName);
    }

    /**
     * Get a config by id
     *
     * @param integer $id
     * @return ChartConfig
     * @throws ChartConfigServiceException
     */
    public function getChartConfigById($id)
    {
        $configs = $this->repository->findByUsername($this->user->getSignedInUsername());
        foreach($configs as $config)
        {
            if ( $config->getId() == $id )
            {
                return $config;
            }
        }

        throw new ChartConfigServiceException("No config found for given id " . $id);
    }

    /**
     * @TODO Should be converted to something smarter.
     * Method is untested, because i want this to be better
     *
     * Create default config for a user
     */
    public function setDefaultConfigs()
    {
        $types = array(
            'StorageUsageCurrent' => '',
            'StorageUsageLastMonth' => json_encode(array('size' => 'gb')),
            'StorageUsagePerMonth' => json_encode(array('size' => 'gb')),
            'ActivityUsagePerMonth' => '',
            'ActivityUsageLastMonth' => ''
        );
        $charts = $this->getCharts();
        foreach($charts as $chart)
        {
            $type = $chart->getChartType();
            if ( in_array($type, array_keys($types)))
            {
                unset($types[$type]);
            }
        }
        foreach($types as $type => $metaData)
        {
            $config = new ChartConfig(
                null,
                new \DateTime(),
                $this->user->getSignedInUsername(),
                $type,
                'c3js',
                $metaData
            );
            $this->repository->save($config);
        }
    }

    /**
     * @param ChartConfig $config
     * @return boolean
     */
    public function save(ChartConfig $config)
    {
        return $this->repository->save($config);
    }
}