<?php

namespace Api;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Tester\Exception\PendingException;

class CurrentStorageUsageContext implements Context, SnippetAcceptingContext
{
    /**
     * @Given an owncloud user named :arg1 was added to owncloud
     */
    public function anOwncloudUserNamedWasAddedToOwncloud($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Given :arg1 GB owncloud storage was measured by owncloud user :arg2
     */
    public function gbOwncloudStorageWasMeasuredByOwncloudUser($arg1, $arg2)
    {
        throw new PendingException();
    }

    /**
     * @Given owncloud has :arg1 GB of free storage space left
     */
    public function owncloudHasGbOfFreeStorageSpaceLeft($arg1)
    {
        throw new PendingException();
    }

    /**
     * @When calculating storage usage in percentages
     */
    public function calculatingStorageUsageInPercentages()
    {
        throw new PendingException();
    }

    /**
     * @Then the percentage for owncloud user :arg1 should be :arg2%
     */
    public function thePercentageForOwncloudUserShouldBe($arg1, $arg2)
    {
        throw new PendingException();
    }

    /**
     * @Then the remaining percentage should be :arg1%
     */
    public function theRemainingPercentageShouldBe($arg1)
    {
        throw new PendingException();
    }
}