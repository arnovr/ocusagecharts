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

namespace OCA\ocUsageCharts\ocUsageCharts;

use OCA\ocUsageCharts\ocUsageCharts\Measurement\Calculator\CalculatorType;
use OCA\ocUsageCharts\ocUsageCharts\Owncloud\FreeSpace;
use OCA\ocUsageCharts\ocUsageCharts\Owncloud\User;

class Owncloud
{
    /**
     * @var Array
     */
    private $users;

    /**
     * @var FreeSpace
     */
    private $freeSpace;

    /**
     * @var Calculator
     */
    private $calculator;

    /**
     * Owncloud constructor.
     */
    public function __construct()
    {
        $this->calculator = new Calculator();
    }

    /**
     * @param User $user
     */
    public function add(User $user)
    {
        $this->users[] = $user;
    }

    /**
     * @param FreeSpace $freeSpace
     */
    public function hasFreeStorageSpaceLeft(FreeSpace $freeSpace)
    {
        $this->freeSpace = $freeSpace;
    }

    /**
     * @param CalculatorType $calculatorType
     * @return Measurement\MeasurementResults
     */
    public function calculateStorageUsageIn(CalculatorType $calculatorType)
    {
        $this->users[] = $this->freeSpace;
        return $this->calculator->calculate($this->users, $calculatorType);
    }

    /**
     * @param $name
     * @return User
     */
    public function getUserByName($name)
    {
        /** @var User $user */
        foreach($this->users as $user)
        {
            if ( $user->getName() === $name)
            {
                return $user;
            }
        }
    }
}
