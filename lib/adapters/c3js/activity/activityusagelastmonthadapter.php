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
use OCA\ocUsageCharts\Entity\Activity\Collections\ActivitySubjectCollection;

/**
 * @author Arno van Rossum <arno@van-rossum.com>
 */
class ActivityUsageLastMonthAdapter extends c3jsBase implements ChartTypeAdapterInterface
{
    public function formatData($data)
    {
        $x = array();
        $result = array();

        /** @var ActivityDayCollection $collection */
        foreach($data as $username => $collection)
        {
            // For the first item, add to X
            if ( count($x) === 0 )
            {
                $x = $this->getRowX($collection);
            }
            $row = $this->getRowData($collection);

            $result[$username] = $row;
        }
        $result["x"] = $x;
        $result = array_reverse($result);

        return $result;
    }

    /**
     * Get a data row for the X line
     *
     * @param ActivityDayCollection $collection
     * @return array
     */
    private function getRowX($collection)
    {
        $x = array();
        foreach($collection as $date => $subjectCollection)
        {
            $x[] = $date;
        }
        return $x;
    }

    /**
     * get Row Data for X/Y axes
     *
     * @param ActivityDayCollection $collection
     * @return array
     */
    private function getRowData($collection)
    {
        $row = array();
        /** @var ActivitySubjectCollection $subjectCollection */
        foreach($collection as $date => $subjectCollection)
        {
            $row[] = $subjectCollection->count();
        }
        return $row;
    }
}
