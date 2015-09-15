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

namespace OCA\ocUsageCharts\ocUsageCharts\Owncloud;

use OCA\ocUsageCharts\ocUsageCharts\Measurement\Measurement;
use OCA\ocUsageCharts\ocUsageCharts\Measurement\MeasurementException;

class User implements Measurement
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var Storage
     */
    private $measurement;

    /**
     * User constructor.
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @param string $name
     * @return User
     */
    public static function named($name)
    {
        return new self($name);
    }

    /**
     * @param Storage $storage
     */
    public function measure(Storage $storage)
    {
        $this->measurement = $storage;
    }

    /**
     * @return Storage
     * @throws MeasurementException
     */
    public function lastlyMeasured()
    {
        if (!$this->measurement){
            throw new MeasurementException('No measurement found for user ' . $this->name);
        }
        return $this->measurement;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return integer
     */
    public function bytesForLastMeasurement()
    {
        return $this->lastlyMeasured()->getBytes();
    }
}