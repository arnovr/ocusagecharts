<?php
use Behat\Behat\Context\SnippetAcceptingContext;
use OCA\ocUsageCharts\Entity\User;
use PHPUnit_Framework_Assert as PHPUnit;

/**
 * Behat context class.
 */
class FeatureContext implements SnippetAcceptingContext
{
    /**
     * @var User
     */
    protected $user;

    /**
     * @Given /^I am a user$/
     */
    public function iAmAUser()
    {
        $this->user = new User('testuser1');
    }

    /**
     * @Given /^I am logged in$/
     */
    public function iAmLoggedIn()
    {
        $this->user->login();
        PHPUnit::assertTrue($this->user->isLoggedIn());
    }

    /**
     * @Given /^i am not logged in$/
     */
    public function iAmNotLoggedIn()
    {
        PHPUnit::assertFalse($this->user->isLoggedIn());
    }

    /**
     * @Given /^I am administrator$/
     */
    public function iAmAdministrator()
    {
        $this->user->isAnAdministrator();
        PHPUnit::assertTrue($this->user->isAdministrator());
    }
}
