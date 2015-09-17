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

namespace OCA\ocUsageCharts\ocUsageCharts\Measurement;

class MeasurementResults
{
    /**
     * @var array
     */
    private $measurement;

    /**
     * @param Measurement $measurement
     * @param mixed $value
     */
    public function setForType(Measurement $measurement, $value)
    {
        $this->measurement[$measurement->getName()] = [
            'measurement' => $measurement,
            'value' => $value
        ];
    }

    /**
     * @param Measurement $measurement
     * @return mixed
     * @throws MeasurementException
     */
    public function forUser(Measurement $measurement)
    {
        if (empty($this->measurement[$measurement->getName()]['value'])) {
            throw new MeasurementException('Could not find measurement for ' . $measurement->getName());
        }
        return $this->measurement[$measurement->getName()]['value'];
    }

    /**
     * @return Measurement
     * @throws MeasurementException
     */
    public function remaining()
    {
        $freeSpaceName = 'free';
        if (empty($this->measurement[$freeSpaceName]['value'])) {
            throw new MeasurementException('Could not find measurement for ' . $freeSpaceName);
        }
        return $this->measurement[$freeSpaceName]['value'];
    }
}
