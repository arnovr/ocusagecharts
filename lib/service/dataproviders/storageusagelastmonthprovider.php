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

namespace OCA\ocUsageCharts\Service\DataProviders;


class StorageUsageLastMonthProvider implements DataProviderInterface
{
    private $repository;

    /**
     * @TODO, get ID from app, not hardcoded
     */
    public function __construct()
    {
        $container = new \OC\AppFramework\DependencyInjection\DIContainer('ocusagecharts', array());
        $this->repository = $container->query('StorageUsageRepository');
    }

    public function getUsageForUpdate()
    {
        // TODO: Implement getUsageForUpdate() method.
    }

    /**
     * Check if user is admin
     * small wrapper for owncloud methology
     * @return boolean
     */
    private function isAdminUser()
    {
        return \OC_User::isAdminUser(\OC_User::getUser());
    }

    public function getUsage()
    {
        $created = new \DateTime("-1 month");
        if ( $this->isAdminUser() )
        {
            $data = $this->repository->findAllAfterCreated($created);
        }
        else
        {
            $data = $this->repository->findAfterCreated($config->getUsername(), $created);
            $data = array($config->getUsername() => $data);
        }
        return $data;
    }
}