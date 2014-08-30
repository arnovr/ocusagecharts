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

namespace OCA\ocUsageCharts\ChartType\c3js\Adapters;

use OCA\ocUsageCharts\ChartType\c3js\c3jsBase;
use OCA\ocUsageCharts\ChartType\ChartTypeAdapterInterface;
use OCA\ocUsageCharts\Entity\ChartConfig;

class StorageUsageLastMonthAdapter extends c3jsBase implements ChartTypeAdapterInterface
{
    private $allowedSizes = array('kb', 'mb', 'gb');

    public function __construct(ChartConfig $config)
    {
        parent::__construct($config);
    }

    /**
     * Parse the usage given to a chosen format
     * @param $usage
     * @return float
     */
    protected function parseUsage($usage)
    {
        $size = 'kb';
        if ( in_array($_GET['size'], $this->allowedSizes) )
        {
            $size = $_GET['size'];
        }
        switch($size)
        {
            case 'gb':
                $usage = $usage / 1024;
            case 'mb':
                $usage = $usage / 1024;
            case 'kb':
                $usage = $usage / 1024;
                break;
        }
        return round($usage, 2);
    }

    public function formatData($data)
    {
        $x = array();
        $result = array();
        $first = true;
        foreach($data as $username => $items )
        {
            $row = array();
            for($i = 0; $i < count($items); $i++)
            {
                if ( $first )
                {
                    $x[] = $items[$i]->getDate()->format('Y-m-d');
                }
                $row[] = $this->parseUsage($items[$i]->getUsage());
            }
            $first = false;
            $row = array_reverse($row);
            $row = array_reverse($row);
            $result[$username] = $row;
        }
        $result["x"] = $x;
        $result = array_reverse($result);

        return $result;
    }
}
