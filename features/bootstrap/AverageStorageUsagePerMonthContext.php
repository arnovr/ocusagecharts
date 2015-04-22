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
use OCA\ocUsageCharts\Storage\DataConverters\AverageStorageUsagePerMonthConverter;
use OCA\ocUsageCharts\Storage\StorageUsage;
use OCA\ocUsageCharts\Entity\Storage\StorageUsage as StorageUsageEntity;
use PHPUnit_Framework_Assert as PHPUnit;

class AverageStorageUsagePerMonthContext extends FeatureContext
{
    /**
     * @var StorageUsage
     */
    private $storageUsage;

    /**
     * @var string
     */
    private $response;

    /**
     * @var \OCA\ocUsageCharts\Entity\Storage\StorageUsageRepository|Mockery\MockInterface
     */
    private $storageUsageRepository;

    private function stubData()
    {
        return array(
            new StorageUsageEntity(DateTime::createFromFormat("Y-m-d", '2015-01-01'), 10, 'testuser1'),
            new StorageUsageEntity(DateTime::createFromFormat("Y-m-d", '2014-05-20'), 5, 'testuser2'),
            new StorageUsageEntity(DateTime::createFromFormat("Y-m-d", '2015-02-01'), 20, 'testuser3'),
            new StorageUsageEntity(DateTime::createFromFormat("Y-m-d", '2015-03-01'), 40, 'testuser4'),
        );
    }

    /**
     * @When /^i try to retrieve Average storage usage per month$/
     */
    public function iTryToRetrieveAverageStorageUsagePerMonth()
    {
        $this->storageUsageRepository = Mockery::mock('\OCA\ocUsageCharts\Entity\Storage\StorageUsageRepository');
        $this->storageUsageRepository
            ->shouldReceive('findAllStorageUsage')
            ->andReturn(
                $this->stubData()
            )
            ->once();

        $this->storageUsage = new StorageUsage($this->storageUsageRepository);
        $this->response = $this->storageUsage->getStorage(new AverageStorageUsagePerMonthConverter());
    }

    /**
     * @Then /^I see json data with the average storage usage per month for user$/
     */
    public function iSeeJsonDataWithTheAverageStorageUsagePerMonthForUser()
    {
        PHPUnit::assertJsonStringEqualsJsonFile('features/bootstrap/responses/AverageStoragePerMonth.json', $this->response);
    }

    /**
     * @Then /^I get an access denied$/
     */
    public function iGetAnAccessDenied()
    {
        throw new PendingException();
    }

}