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

namespace OCA\ocUsageCharts\Adapters\c3js\Storage;

use OCA\ocUsageCharts\Entity\ChartConfig;
use OCA\ocUsageCharts\Adapters\c3js\c3jsBase;
use OCA\ocUsageCharts\Owncloud\User;
use OCA\ocUsageCharts\ValueObject\Measurements\GigaByteMetric;
use OCA\ocUsageCharts\ValueObject\Measurements\KiloByteMetric;
use OCA\ocUsageCharts\ValueObject\Measurements\MegaByteMetric;
use OCA\ocUsageCharts\ValueObject\Measurements\TeraByteMetric;

/**
 * @author Arno van Rossum <arno@van-rossum.com>
 */
class StorageUsageLastMonthAdapter extends c3jsBase
{
    /**
     * @var array
     */
    private $allowedSizes = array('kb', 'mb', 'gb', 'tb');

    /**
     * @var string
     */
    private $size;

    /**
     * @param ChartConfig $chartConfig
     * @param User $user
     */
    public function __construct(ChartConfig $chartConfig, User $user)
    {
        $metaData = json_decode($chartConfig->getMetaData());
        if ( !empty($metaData) )
        {
            $size = $metaData->size;
        }
        if ( empty($size) || !in_array($size, $this->allowedSizes) )
        {
            $size = 'gb';
        }

        $this->size = $size;
        parent::__construct($chartConfig, $user);
    }


    /**
     * Parse the usage given to a chosen format
     *
     * @param $usage
     * @return float
     */
    protected function calculateUsage($usage)
    {
        switch($this->size)
        {
            case 'tb':
                $metric = new TeraByteMetric($usage);
                break;
            case 'gb':
                $metric = new GigaByteMetric($usage);
                break;
            case 'mb':
                $metric = new MegaByteMetric($usage);
                break;
            case 'kb':
            default:
                $metric = new KiloByteMetric($usage);
                break;
        }
        return $metric->getValue();
    }

    /**
     * Format the data given to proper usage for the C3.js provider
     *
     * @param array $data
     * @return array
     */
    public function formatData($data)
    {
        $x = array();
        $result = array();
        foreach($data as $uid => $items )
        {
            $username = $this->user->getDisplayName($uid);
            // For the first item, add to X
            if ( count($x) === 0 )
            {
                $x = $this->getRowX($items);
            }
            $row = $this->getRowData($items);

            $result[$username] = $row;
        }
        $result["x"] = $x;
        $result = array_reverse($result);

        return $result;
    }

    /**
     * Get a data row for the X line
     *
     * @param array $items
     * @return array
     */
    private function getRowX($items)
    {
        $x = array();
        $totalItems = count($items);
        for($i = 0; $i < $totalItems; $i++)
        {
            $x[] = $items[$i]->getDate()->format('Y-m-d');
        }
        return $x;
    }

    /**
     * get Row Data for X/Y axes
     *
     * @param $items
     * @return array
     */
    private function getRowData($items)
    {
        $row = array();
        $totalItems = count($items);
        for($i = 0; $i < $totalItems; $i++)
        {
            $row[] = $this->calculateUsage($items[$i]->getUsage());
        }
        return $row;
    }
}
