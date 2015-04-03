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
use OCA\ocUsageCharts\Entity\Storage\StorageUsageRepository;
use OCA\ocUsageCharts\Storage\Converters\AverageStorageUsagePerMonth;
use OCA\ocUsageCharts\Storage\Repository\StorageRepository;
use OCA\ocUsageCharts\Storage\StorageUsage;
use PHPUnit_Framework_Assert as PHPUnit;

class AverageStorageUsagePerMonthContext extends FeatureContext
{
    /**
     * @var StorageUsageService
     */
    private $storageUsageService;

    /**
     * @var string
     */
    private $response;

    /**
     * @When /^i try to retrieve Average storage usage per month$/
     */
    public function iTryToRetrieveAverageStorageUsagePerMonth()
    {
        $repository = new StorageUsageRepository(Mockery::mock('\OCP\IDb'));
        $this->storageUsageService = new StorageUsage($repository);
        $this->response = $this->storageUsageService->getStorage(new AverageStorageUsagePerMonth());
    }

    /**
     * @Then /^I see json data with the average storage usage per month for user$/
     */
    public function iSeeJsonDataWithTheAverageStorageUsagePerMonthForUser()
    {
        $expectedResponse = file_get_contents('features/bootstrap/responses/AverageStoragePerMonth.json');
        PHPUnit::assertEquals($expectedResponse, $this->response);
    }

    /**
     * @Then /^I get an access denied$/
     */
    public function iGetAnAccessDenied()
    {
        throw new PendingException();
    }

}