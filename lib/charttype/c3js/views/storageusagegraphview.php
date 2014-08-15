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

namespace OCA\ocUsageCharts\ChartType\c3js\Views;

use OCA\ocUsageCharts\ChartType\c3js\c3jsBase;
use OCA\ocUsageCharts\ChartType\ChartTypeViewInterface;

class StorageUsageGraphView extends c3jsBase implements ChartTypeViewInterface
{
    public function __construct(\stdClass $config)
    {
        parent::__construct($config);
    }

    public function show($data)
    {
        $x = array('x');
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
                $row[] = $items[$i]->getUsage();
            }
            $first = false;
            $row = array_reverse($row);
            $row[] = $username;
            $row = array_reverse($row);
            $result[] = $row;
        }
        $result[] = $x;
        $result = array_reverse($result);
        return $result;
    }

    public function getTemplateName()
    {
        return 'c3js_storage_usage_graph_view';
    }
}
