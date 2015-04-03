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
    private $user;

    /**
     * @Given /^I am a user$/
     */
    public function iAmAUser()
    {
        $this->user = new User();
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

}
