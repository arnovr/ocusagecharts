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

namespace OCA\ocUsageCharts\Chart;

use OCA\ocUsageCharts\Entity\ChartConfig;
use OCA\ocUsageCharts\Entity\User;
use OCA\ocUsageCharts\Storage\DataConverters\DataConverterInterface;
use OCA\ocUsageCharts\Storage\Storage;

/**
 * A chart is a graphical representation of data, in which
 * "the data is represented by symbols, such as bars in a bar chart, lines in a line chart, or slices in a pie chart."
 *
 * This class represents it's graphical counterfeit
 *
 * Class Chart
 * @package OCA\ocUsageCharts\Chart
 */
class Chart
{
    /**
     * @var Storage
     */
    private $storage;

    /**
     * @param Storage $storage
     */
    public function __construct(Storage $storage)
    {
        $this->storage = $storage;
    }

    /**
     * Probably needs to be refactored when activities want to use this method
     *
     * @param User $user
     * @param DataConverterInterface $dataConverterInterface
     * @param ChartConfig $chartConfig
     * @return \JsonSerializable
     */
    public function getStorage(User $user, DataConverterInterface $dataConverterInterface, ChartConfig $chartConfig)
    {
        if (!$user->isLoggedIn())
        {
            throw new \InvalidArgumentException("User is not logged in");
        }
        return $this->storage->getUsage($dataConverterInterface);
    }
}
