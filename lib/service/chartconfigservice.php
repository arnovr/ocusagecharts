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

/**
 * @author Arno van Rossum <arno@van-rossum.com>
 */
class ChartConfigService
{
    /**
     * @var ChartConfigRepository
     */
    private $repository;

    public function __construct(ChartConfigRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return string
     */
    private function getUsername()
    {
        return \OCP\User::getUser();
    }

    /**
     * Get all charts
     *
     * @return array|\OCA\ocUsageCharts\Entity\ChartConfig[]
     */
    public function getCharts()
    {
        return $this->repository->findByUsername($this->getUsername());
    }

    /**
     * Get a config by id
     *
     * @param integer $id
     * @return ChartConfig
     */
    public function getChartConfigById($id)
    {
        $configs = $this->repository->findByUsername($this->getUsername());
        foreach($configs as $config)
        {
            if ( $config->getId() == $id )
            {
                return $config;
            }
        }
        return $configs;
    }

    /**
     * Create default config for a user
     */
    public function createDefaultConfig()
    {
        $config = new ChartConfig(
            null,
            new \DateTime(),
            $this->getUsername(),
            'StorageUsageCurrent',
            'c3js'
        );
        $this->repository->save($config);

        $config = new ChartConfig(
            null,
            new \DateTime(),
            $this->getUsername(),
            'StorageUsageLastMonth',
            'c3js'
        );
        $this->repository->save($config);

        $config = new ChartConfig(
            null,
            new \DateTime(),
            $this->getUsername(),
            'StorageUsagePerMonth',
            'c3js'
        );
        $this->repository->save($config);
    }
}