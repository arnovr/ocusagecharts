<?php

namespace Domain;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Tester\Exception\PendingException;

class CurrentStorageUsageContext implements Context, SnippetAcceptingContext
{
    /**
     * @Given /^user "([^"]*)" uses "([^"]*)" GB of storage$/
     */
    public function userUsesGBOfStorage($userName, $gbOfStorage)
    {
        throw new PendingException();
    }

    /**
     * @Given /^there is "(\d+)" GB of free storage$/
     */
    public function thereIsGBOfFreeStorage($gbOfStorage)
    {
        throw new PendingException();
    }

    /**
     * @When /^i add "([^"]*)" to the chart$/
     */
    public function iAddToTheChart($userName)
    {
        throw new PendingException();
    }

    /**
     * @Then /^the chart slice "([^"]*)" should be "([^"]*)" with "([^"]*)" GB used storage$/
     */
    public function theChartSliceShouldBeWithGBUsedStorage($userName, $percentage, $gbOfStorage)
    {
        throw new PendingException();
    }
}