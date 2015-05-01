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

namespace OCA\ocUsageCharts\Storage\DataConverters;

use JsonSerializable;
use OCA\ocUsageCharts\Entity\Storage\StorageUsage;

class AveragePerMonthConverter implements DataConverterInterface
{
    /**
     * @var DateSorter
     */
    private $dateSorter;

    /**
     * @param DateSorter $dateSorter
     */
    public function __construct(DateSorter $dateSorter)
    {
        $this->dateSorter = $dateSorter;
    }

    /**
     * @param [Storage]
     * @return JsonSerializable
     */
    public function convert(array $storageEntities)
    {
        $dataSequences = array(
            'title' => 'Average Storage Usage Per Month',
            'x' => 'date',
            'y' => 'usage',
            'datasequences' => array()
        );

        $filledArray = array();

        /* @var StorageUsage $storageUsage */
        foreach($storageEntities as $storageUsage)
        {
            $key = $storageUsage->getDate()->format("Y-m") . '_' . $storageUsage->getUsername();
            if ( !isset($filledArray[$key]))
            {
                $filledArray[$key] = array();
            }
            $filledArray[$key][] = $storageUsage;
        }

        $dataSequences['datasequences'] = $this->dateSorter->sort(
            $this->calculateAveragePerMonth($filledArray)
        );

        return json_encode($dataSequences);
    }

    /**
     * @param $filledArray
     * @return array
     */
    private function calculateAveragePerMonth($filledArray)
    {
        $return = array();

        foreach($filledArray as $date_username => $storageUsages)
        {
            list($date, $username) = explode('_', $date_username);

            $usage = $this->getAverageUsage($storageUsages);

            $return[] = array(
                'title' => $username,
                'date' => $date,
                'usage' => $usage
            );
        }
        return $return;
    }

    private function getAverageUsage($storageUsages)
    {
        $usage = 0;
        /* @var StorageUsage $storageUsage */
        foreach($storageUsages as $storageUsage)
        {
            $usage += $storageUsage->getUsage();
        }

        return round(($usage / count($storageUsages)), 2);
    }
}
