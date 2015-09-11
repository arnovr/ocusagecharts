<?php

namespace Domain;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Tester\Exception\PendingException;
use OC\Files\Storage\OwnCloud;

class CurrentStorageUsageContext implements Context, SnippetAcceptingContext
{
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
     * @Given /^"([^"]*)" GB owncloud storage was stored by owncloud user "([^"]*)"$/
     */
    public function gbOwncloudStorageWasStoredByOwncloudUser($gbOfStorage, $userName)
    {
        $owncloudUser = $this->owncloud->getUserNamed($userName);
        $owncloudStorage = new Owncloud\Storage($gbOfStorage);
        $owncloudUser->store($owncloudStorage);
    }

    /**
     * @When /^owncloud user "([^"]*)" stores "([^"]*)" GB of owncloud storage$/
     */
    public function owncloudUserFromTheChartStoresGBOfOwncloudStorage($userName, $gbOfStorage)
    {
        $owncloudStorage = new OwnCloud\Storage($gbOfStorage);
        $this->owncloud->store($userName, $owncloudStorage);
    }

    /**
     * @Given /^owncloud has "([^"]*)" GB of free storage left$/
     */
    public function owncloudHasGBOfFreeStorageLeft($gbOfStorage)
    {
        $this->owncloud->hasFreeStorageLeft($gbOfStorage);
    }
}