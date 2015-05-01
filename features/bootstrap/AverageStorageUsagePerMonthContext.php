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

use OCA\ocUsageCharts\Chart\Chart;
use OCA\ocUsageCharts\Storage\DataConverters\AverageStorageUsagePerMonthConverter;
use OCA\ocUsageCharts\Storage\Storage;
use OCA\ocUsageCharts\Entity\Storage\StorageUsage as StorageUsageEntity;
use PHPUnit_Framework_Assert as PHPUnit;
use OCA\ocUsageCharts\Entity\ChartConfig;

class AverageStorageUsagePerMonthContext extends FeatureContext
{
    /**
     * @var Chart
     */
    private $chart;
    /**
     * @var Storage
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

    /**
     * @var ChartConfig
     */
    private $chartConfig;

    /**
     * @Given /^I have a chart$/
     */
    public function iHaveAChart()
    {
        $this->setUpStorageUsage();
        $this->chartConfig = new ChartConfig(1, new DateTime(), $this->user->getName(), 'StorageUsagePerMonth', null, null);
    }

    /**
     * @When /^i try to retrieve Average storage usage per month$/
     */
    public function iTryToRetrieveAverageStorageUsagePerMonth()
    {
        try {
            $this->response = $this->chart->getStorage(new AverageStorageUsagePerMonthConverter(), $this->user);
        }
        catch(\InvalidArgumentException $e)
        {
            $this->response = null;
        }
    }

    /**
     * @Then /^I see json data with the average storage usage per month for user$/
     */
    public function iSeeJsonDataWithTheAverageStorageUsagePerMonthForUser()
    {
        PHPUnit::assertJsonStringEqualsJsonFile('features/bootstrap/responses/AverageStoragePerMonthForUser.json', $this->response);
    }

    /**
     * @Then /^I see json data with the average storage usage per month for administrator$/
     */
    public function iSeeJsonDataWithTheAverageStorageUsagePerMonthForAdministrator()
    {
        PHPUnit::assertJsonStringEqualsJsonFile('features/bootstrap/responses/AverageStoragePerMonthForAdministrator.json', $this->response);
    }

    /**
     * @Then /^I get an empty response$/
     */
    public function iGetAnEmptyResponse()
    {
        PHPUnit::assertNull($this->response);
    }

    /**
     * Setup some data entities to return
     * @return array
     */
    private function stubData()
    {
        $data = array(
            new StorageUsageEntity(DateTime::createFromFormat("Y-m-d", '2015-01-01'), 10, 'testuser1'),
            new StorageUsageEntity(DateTime::createFromFormat("Y-m-d", '2014-05-20'), 5, 'testuser2'),
            new StorageUsageEntity(DateTime::createFromFormat("Y-m-d", '2015-02-01'), 20, 'testuser3'),
            new StorageUsageEntity(DateTime::createFromFormat("Y-m-d", '2015-03-01'), 40, 'testuser4'),
        );

        if (!$this->user->isAdministrator())
        {
            return array($data[0]);
        }
        return $data;
    }

    private function setUpStorageUsage()
    {
        $this->storageUsageRepository = Mockery::mock('\OCA\ocUsageCharts\Entity\Storage\StorageUsageRepository');
        $this->storageUsageRepository
            ->shouldReceive('findAllStorageUsage')
            ->andReturn(
                $this->stubData()
            )
            ->once();

        $this->storageUsage = new Storage($this->storageUsageRepository);
        $this->chart = new Chart($this->storageUsage);
    }

}
