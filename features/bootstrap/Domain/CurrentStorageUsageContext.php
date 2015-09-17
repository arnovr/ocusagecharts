<?php

namespace Domain;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use OCA\ocUsageCharts\ocUsageCharts\Measurement\Calculator\Percentage;
use OCA\ocUsageCharts\ocUsageCharts\Measurement\MeasurementResults;
use OCA\ocUsageCharts\ocUsageCharts\Owncloud;
use OCA\ocUsageCharts\ValueObject\Measurements\GigaByteMetric;
use PHPUnit_Framework_Assert;

class CurrentStorageUsageContext implements Context, SnippetAcceptingContext
{
    /**
     * @var MeasurementResults
     */
    private $measurementResults;

    /**
     * CurrentStorageUsageContext constructor.
     */
    public function __construct()
    {
        $this->owncloud = new Owncloud();
    }

    /**
     * @Given /^an owncloud user named "([^"]*)" was added to owncloud$/
     */
    public function anOwncloudUserNamedWasAddedToOwncloud($name)
    {
        $owncloudUser = Owncloud\User::named($name);
        $this->owncloud->add($owncloudUser);
    }

    /**
     * @Given /^"([^"]*)" GB owncloud storage was measured by owncloud user "([^"]*)"$/
     * @param $name
     */
    public function gbOwncloudStorageWasMeasuredByOwncloudUser($gbOfStorage, $name)
    {
        $GBs = GigaByteMetric::gigabytes($gbOfStorage);
        $owncloudUser = $this->owncloud->getUserByName($name);
        $owncloudStorage = new Owncloud\Storage($GBs);
        $owncloudUser->measure($owncloudStorage);
    }

    /**
     * @Given /^owncloud has "([^"]*)" GB of free storage space left$/
     */
    public function owncloudHasGBOfFreeStorageSpaceLeft($gbOfStorage)
    {
        $GBs = GigaByteMetric::gigabytes($gbOfStorage);

        $this->owncloud->hasFreeStorageSpaceLeft(
            new Owncloud\FreeSpace(
                new Owncloud\Storage($GBs)
            )
        );
    }

    /**
     * @When /^calculating storage usage in percentages$/
     */
    public function calculatingStorageUsageInPercentages()
    {
        $this->measurementResults = $this->owncloud->calculateStorageUsageIn(new Percentage());
    }

    /**
     * @Then /^the percentage for owncloud user "([^"]*)" should be "([^"]*)"%$/
     */
    public function thePercentageForOwncloudUserShouldBe($name, $percentage)
    {
        $owncloudUser = $this->owncloud->getUserByName($name);
        $this->shouldBe(
            $this->measurementResults->forUser($owncloudUser),
            $percentage
        );
    }

    /**
     * @Given /^the remaining percentage should be "([^"]*)"%$/
     */
    public function theRemainingPercentageShouldBe($percentage)
    {
        $this->shouldBe(
            $this->measurementResults->remaining(),
            $percentage
        );
    }

    /**
     * @param $expectedPercentage
     * @param $percentage
     */
    private function shouldBe($expectedPercentage, $percentage)
    {
        PHPUnit_Framework_Assert::assertEquals(
            $expectedPercentage,
            $percentage
        );
    }
}