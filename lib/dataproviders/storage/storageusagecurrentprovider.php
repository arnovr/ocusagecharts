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
use OCA\ocUsageCharts\Entity\Storage\StorageUsage;

/**
 * @author Arno van Rossum <arno@van-rossum.com>
 */
class StorageUsageCurrentProvider extends StorageUsageBase implements DataProviderInterface, DataProviderStorageInterface
{
    /**
     * Return the chart data you want to return based on the ChartConfig
     *
     * @return mixed
     */
    public function getChartUsage()
    {
        $storageInfo = $this->storage->getCurrentStorageUsageForSignedInUser();
        $free = ceil($storageInfo['free'] / 1024 / 1024);

        if ( $this->user->isAdminUser($this->user->getSignedInUsername()) )
        {
            $data = $this->retrieveStorageUsagePerUser();
            $data['free'] = $free;
        }
        else
        {
            $used = ceil($storageInfo['used'] / 1024 / 1024);
            $data = array(
                'used' => $used,
                'free' => $free
            );
        }
        return $data;
    }

    /**
     * @return array
     */
    private function retrieveStorageUsagePerUser()
    {
        $new = array();
        $data = $this->repository->findAllWithLimit(1);
        foreach($data as $uid => $items)
        {
            $username = $this->user->getDisplayName($uid);
            /** @var StorageUsage $item */
            foreach($items as $item)
            {
                $new[$username] = ceil($item->getUsage() / 1024 / 1024);
            }
        }
        return $new;
    }
}