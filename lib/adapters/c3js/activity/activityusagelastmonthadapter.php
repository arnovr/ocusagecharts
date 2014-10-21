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

namespace OCA\ocUsageCharts\Adapters\c3js\Activity;

use OCA\ocUsageCharts\Adapters\ChartTypeAdapterInterface;
use OCA\ocUsageCharts\Adapters\c3js\c3jsBase;
use OCA\ocUsageCharts\Entity\Activity\Collections\ActivityDayCollection;

/**
 * @author Arno van Rossum <arno@van-rossum.com>
 */
class ActivityUsageLastMonthAdapter extends c3jsBase implements ChartTypeAdapterInterface
{
    /**
     * @param array $data
     * @return array
     */
    public function formatData($data)
    {
        $x = array();
        $result = array();

        /** @var ActivityDayCollection $collection */
        foreach(array_values($data) as $collection)
        {
            $x = $this->getRowX($x, $collection);
        }
        /** @var ActivityDayCollection $collection */
        foreach($data as $uid => $collection)
        {
            $username = $this->user->getDisplayName($uid);
            $row = $this->getRowData($x, $collection);
            $result[$username] = $row;
        }
        $result["x"] = $x;
        $result = array_reverse($result);

        return $result;
    }

    /**
     * Get a data row for the X line
     *
     * @param array $x
     * @param ActivityDayCollection $collection
     * @return array
     */
    private function getRowX(array $x, $collection)
    {
        foreach($collection as $date => $subjectCollection)
        {
            if ( !in_array($date, $x) )
            {
                $x[] = $date;
            }
        }
        return $x;
    }

    /**
     * get Row Data for X/Y axes
     *
     * @param array $x
     * @param ActivityDayCollection $collection
     * @return array
     */
    private function getRowData($x, $collection)
    {
        $row = array();
        $total = count($x);
        for($i = 0; $i < $total; $i++)
        {
            $row[$i] = $this->calculateTotalCountByCollection(new \DateTime($x[$i]), $collection);
        }
        return $row;
    }

    /**
     * @param \DateTime $date
     * @param ActivityDayCollection $collection
     * @return integer
     */
    private function calculateTotalCountByCollection(\DateTime $date, $collection)
    {
        $return = 0;
        $subjectCollection = $collection->getByDate($date);
        if ( !empty($subjectCollection) )
        {
            $return = $subjectCollection->totalCount();
        }

        return $return;
    }
}
