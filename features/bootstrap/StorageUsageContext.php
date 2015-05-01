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

use Behat\Behat\Tester\Exception\PendingException;
use OCA\ocUsageCharts\Storage\DataConverters\AveragePerMonthConverter;
use OCA\ocUsageCharts\Storage\DataConverters\DateSorter;
use OCA\ocUsageCharts\Storage\DataConverters\FromDateConverter;
use PHPUnit_Framework_Assert as PHPUnit;

class StorageUsageContext extends FeatureContext
{
    /**
     * @When i try to retrieve Average storage usage per month
     */
    public function iTryToRetrieveAverageStorageUsagePerMonth()
    {
        $this->retrieveStorage(new AveragePerMonthConverter(new DateSorter()));
    }

    /**
     * @When i try to retrieve the storage usage for the last month
     */
    public function iTryToRetrieveTheStorageUsageForTheLastMonth()
    {
        $date = new DateTime("2015-05-01");
        $this->retrieveStorage(new FromDateConverter($date));
    }

    /**
     * @Then I see json data with the average storage usage per month for user
     */
    public function iSeeJsonDataWithTheAverageStorageUsagePerMonthForUser()
    {
        PHPUnit::assertJsonStringEqualsJsonFile('features/bootstrap/responses/AverageStoragePerMonth/ForUser.json', $this->response);
    }

    /**
     * @Then I see json data with the average storage usage per month for administrator
     */
    public function iSeeJsonDataWithTheAverageStorageUsagePerMonthForAdministrator()
    {
        PHPUnit::assertJsonStringEqualsJsonFile('features/bootstrap/responses/AverageStoragePerMonth/ForAdministrator.json', $this->response);
    }

    /**
     * @Then I get an empty response
     */
    public function iGetAnEmptyResponse()
    {
        PHPUnit::assertNull($this->response);
    }

    /**
     * @Then I see my personal json data for storage usage for the last month
     */
    public function iSeeMyPersonalJsonDataForStorageUsageForTheLastMonth()
    {
        throw new PendingException();
    }

    /**
     * @Then I see json data for all users with the storage usage for the last month
     */
    public function iSeeJsonDataForAllUsersWithTheStorageUsageForTheLastMonth()
    {
        throw new PendingException();
    }
}
