<?php

namespace Domain;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Tester\Exception\PendingException;
use OC\Files\Storage\OwnCloud;

class CurrentStorageUsageContext implements Context, SnippetAcceptingContext
{
    private $percentages;

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
    public function anOwncloudUserNamedWasAddedToOwncloud($userName)
    {
        $owncloudUser = Owncloud\User::named($userName);
        $this->owncloud->add($owncloudUser);
    }

    /**
     * @Given /^"([^"]*)" GB owncloud storage was measured by owncloud user "([^"]*)"$/
     */
    public function gbOwncloudStorageWasMeasuredByOwncloudUser($gbOfStorage, $userName)
    {
        $owncloudUser = Owncloud\User::named($userName);
        $owncloudStorage = new Owncloud\Storage($gbOfStorage);
        $owncloudUser->measure($owncloudStorage);
    }

    /**
     * @Given /^owncloud has "([^"]*)" GB of free storage left$/
     */
    public function owncloudHasGBOfFreeStorageLeft($gbOfStorage)
    {
        $this->owncloud->hasFreeStorageLeft(
            new OwnCloud\Storage($gbOfStorage)
        );
    }

    /**
     * @When /^calculating storage usage in percentages$/
     */
    public function calculatingStorageUsageInPercentages()
    {
        $this->percentages = $this->owncloud->calculateStorageUsageInPercentage();
    }

    /**
     * @Then /^the percentage for owncloud user "([^"]*)" should be "([^"]*)"$/
     */
    public function thePercentageForOwncloudUserShouldBe($username, $percentage)
    {
        $this->shouldBe(
            $this->percentages->percentageForOwncloudUser($username),
            $percentage
        );
    }

    /**
     * @Given /^the remaining percentage should be "([^"]*)"$/
     */
    public function theRemainingPercentageShouldBe($percentage)
    {
        $this->shouldBe(
            $this->percentages->remainingPercentage(),
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