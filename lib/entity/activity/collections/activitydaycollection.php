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
namespace OCA\ocUsageCharts\Entity\Activity\Collections;

use \Iterator;
use OCA\ocUsageCharts\Entity\Activity\ActivityUsage;

class ActivityDayCollection implements Iterator
{
    /**
     * @var array
     */
    private $subjectCollections = array();

    /**
     * @param \DateTime $date
     * @return ActivitySubjectcollection
     */
    public function getByDate(\DateTime $date)
    {
        $key = $date->format('Y-m-d');
        return $this->subjectCollections[$key];
    }

    /**
     * @param ActivityUsage $usage
     */
    public function add(ActivityUsage $usage)
    {
        $date = $usage->getDate();
        $key = $date->format('Y-m-d');
        if ( empty($this->subjectCollections[$key]) )
        {
            $this->subjectCollections[$key] = new ActivitySubjectCollection();
        }

        $this->subjectCollections[$key]->add($usage);
    }

    public function current()
    {
        return current($this->subjectCollections);
    }

    public function next()
    {
        next($this->subjectCollections);
    }

    public function key()
    {
        return key($this->subjectCollections);
    }

    public function valid()
    {
        return $this->key() !== null;
    }

    public function rewind()
    {
        reset($this->subjectCollections);
    }
}