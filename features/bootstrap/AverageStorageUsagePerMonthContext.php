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

use Behat\Behat\Tester\Exception\PendingException;
use OCA\ocUsageCharts\Owncloud\User;
use OCA\ocUsageCharts\Service\StorageUsageService;

class AverageStorageUsagePerMonthContext extends FeatureContext
{
    /**
     * @var StorageUsageService
     */
    private $storageUsageService;

    /**
     * @var
     */
    private $response;

    /**
     * @When /^i try to retrieve Average storage usage per month$/
     */
    public function iTryToRetrieveAverageStorageUsagePerMonth()
    {
        $user = new User();
        $this->storageUsageService = new StorageUsageService();
        $this->response = $this->storageUsageService->averageUsagePerMonth($user);

        PHPUnit_Framework_Assert::assertInstanceOf('', $this->response);
    }

    /**
     * @Then /^I see json data with the average storage usage per month for user$/
     */
    public function iSeeJsonDataWithTheAverageStorageUsagePerMonthForUser()
    {
        throw new PendingException();
    }

    /**
     * @Then /^I get an access denied$/
     */
    public function iGetAnAccessDenied()
    {
        throw new PendingException();
    }

}