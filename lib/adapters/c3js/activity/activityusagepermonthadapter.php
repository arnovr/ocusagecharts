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

/**
 * @author Arno van Rossum <arno@van-rossum.com>
 */
class ActivityUsagePerMonthAdapter extends c3jsBase implements ChartTypeAdapterInterface
{
    /**
     * This method gives the ability to parse the data in any form you would like
     *
     * @param $data
     * @return mixed
     */
    public function formatData($data)
    {
        $x = array();
        $result = array();
        foreach($data as $date => $usernames)
        {
            if ( !in_array($date, $x) )
            {
                $x[] = $date;
            }
            $this->parseUsernamesToRow($result, $usernames);
        }

        $result["x"] = $x;
        $result = array_reverse($result);

        return $result;
    }

    /**
     * @param array $result
     * @param array $usernames
     * @return array
     */
    private function parseUsernamesToRow(&$result, array $usernames)
    {
        foreach($usernames as $uid => $count)
        {
            $username = $this->user->getDisplayName($uid);
            if ( !in_array($username, array_keys($result)) )
            {
                $result[$username] = array();
            }

            $result[$username][] = $count;
        }
    }

}
