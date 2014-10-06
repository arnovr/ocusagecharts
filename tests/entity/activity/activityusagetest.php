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

namespace OCA\ocUsageCharts\Tests\Entity;


use OCA\ocUsageCharts\Entity\Activity\ActivityUsage;

class ActivityUsageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ActivityUsage
     */
    private $activityUsage;

    /**
     * @var \DateTime
     */
    private $date;

    public function setUp()
    {
        $this->date = new \DateTime();

        $this->activityUsage = new ActivityUsage($this->date, "created_from", 'test1');
    }
    public function testGetDate()
    {
        $this->assertEquals($this->date, $this->activityUsage->getDate());
    }

    public function testGetSubject()
    {
        $this->assertEquals('created_from', $this->activityUsage->getSubject());
    }
    public function testFromRow()
    {
        $row = array(
            'timestamp' => $this->date->getTimestamp(),
            'user' => 'test1',
            'subject' => 'created_from'
        );
        $activity = ActivityUsage::fromRow($row);
        $this->assertInstanceOf('OCA\ocUsageCharts\Entity\Activity\ActivityUsage', $activity);

        $this->assertEquals("created_from", $activity->getSubject());
        $this->assertEquals($this->date, $activity->getDate());
    }
}
