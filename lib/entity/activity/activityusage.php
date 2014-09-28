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

namespace OCA\ocUsageCharts\Entity\Activity;

/**
 * @author Arno van Rossum <arno@van-rossum.com>
 */
class ActivityUsage {
    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var string
     */
    private $subject;

    /**
     * @var string
     */
    private $username;

    /**
     * @param \DateTime $date
     * @param string $subject
     * @param string $username
     */
    public function __construct(\DateTime $date, $subject, $username)
    {
        $this->date = $date;
        $this->subject = $subject;
        $this->username = $username;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param array $row
     * @return ActivityUsage
     */
    public static function fromRow($row)
    {
        $created = new \DateTime();
        $created->setTimestamp($row['timestamp']);
        return new ActivityUsage($created, $row['subject'], $row['user']);
    }
}