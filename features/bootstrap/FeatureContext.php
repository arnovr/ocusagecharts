<?php
use Behat\Behat\Context\SnippetAcceptingContext;
use PHPUnit_Framework_Assert as PHPUnit;

use OCA\ocUsageCharts\Chart\Chart;
use OCA\ocUsageCharts\Entity\ChartConfig;
use OCA\ocUsageCharts\Entity\User;
use OCA\ocUsageCharts\Storage\Storage;
use OCA\ocUsageCharts\Entity\Storage\StorageUsage as StorageUsageEntity;


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
     * @var Chart
     */
    protected $chart;

    /**
     * @var ChartConfig
     */
    protected $chartConfig;

    /**
     * @var Storage
     */
    protected $storageUsage;

    /**
     * @var \OCA\ocUsageCharts\Entity\Storage\StorageUsageRepository|Mockery\MockInterface
     */
    private $storageUsageRepository;

    /**
     * @var string
     */
    protected $response;

    /**
     * @Given I am a user
     */
    public function iAmAUser()
    {
        $this->user = new User('testuser1');
    }

    /**
     * @Given I am logged in
     */
    public function iAmLoggedIn()
    {
        $this->user->login();
        PHPUnit::assertTrue($this->user->isLoggedIn());
    }

    /**
     * @Given i am not logged in
     */
    public function iAmNotLoggedIn()
    {
        PHPUnit::assertFalse($this->user->isLoggedIn());
    }

    /**
     * @Given I am administrator
     */
    public function iAmAdministrator()
    {
        $this->user->isAnAdministrator();
        PHPUnit::assertTrue($this->user->isAdministrator());
    }

    /**
     * @Given I have a chart
     */
    public function iHaveAChart()
    {
        $this->setUpStorageUsage();
        $this->chartConfig = new ChartConfig(1, new DateTime(), $this->user->getName(), 'StorageUsagePerMonth', null, null);
    }

    protected function setUpStorageUsage()
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

    protected function retrieveStorage(\OCA\ocUsageCharts\Storage\DataConverters\DataConverterInterface $converter)
    {
        try {
            $this->response = $this->chart->getStorage($converter, $this->user);
        } catch (\InvalidArgumentException $e) {
            $this->response = null;
        }
    }

    /**
     * Setup some data entities to return
     * @return array
     */
    protected function stubData()
    {
        $data = array(
            new StorageUsageEntity(DateTime::createFromFormat("Y-m-d", '2015-01-01'), 10, 'testuser1'),
            new StorageUsageEntity(DateTime::createFromFormat("Y-m-d", '2015-01-15'), 11, 'testuser1'),
            new StorageUsageEntity(DateTime::createFromFormat("Y-m-d", '2015-02-01'), 13, 'testuser1'),
            new StorageUsageEntity(DateTime::createFromFormat("Y-m-d", '2015-02-15'), 13, 'testuser1'),
            new StorageUsageEntity(DateTime::createFromFormat("Y-m-d", '2015-03-01'), 10, 'testuser1'),
            new StorageUsageEntity(DateTime::createFromFormat("Y-m-d", '2015-03-15'), 12, 'testuser1'),
            new StorageUsageEntity(DateTime::createFromFormat("Y-m-d", '2015-04-01'), 14, 'testuser1'),
            new StorageUsageEntity(DateTime::createFromFormat("Y-m-d", '2015-04-15'), 11, 'testuser1'),
            new StorageUsageEntity(DateTime::createFromFormat("Y-m-d", '2015-05-01'), 15, 'testuser1'),
            new StorageUsageEntity(DateTime::createFromFormat("Y-m-d", '2015-05-02'), 11, 'testuser1'),
            new StorageUsageEntity(DateTime::createFromFormat("Y-m-d", '2015-05-03'), 16, 'testuser1'),
            new StorageUsageEntity(DateTime::createFromFormat("Y-m-d", '2015-05-04'), 17, 'testuser1'),
            new StorageUsageEntity(DateTime::createFromFormat("Y-m-d", '2015-05-05'), 19, 'testuser1'),
            new StorageUsageEntity(DateTime::createFromFormat("Y-m-d", '2015-05-06'), 16, 'testuser1'),
            new StorageUsageEntity(DateTime::createFromFormat("Y-m-d", '2015-05-16'), 18, 'testuser1'),
            new StorageUsageEntity(DateTime::createFromFormat("Y-m-d", '2015-05-20'), 19, 'testuser1'),
            new StorageUsageEntity(DateTime::createFromFormat("Y-m-d", '2015-05-23'), 20, 'testuser1'),
            new StorageUsageEntity(DateTime::createFromFormat("Y-m-d", '2014-05-20'), 5, 'testuser2'),
            new StorageUsageEntity(DateTime::createFromFormat("Y-m-d", '2015-02-01'), 20, 'testuser3'),
            new StorageUsageEntity(DateTime::createFromFormat("Y-m-d", '2015-03-01'), 40, 'testuser4'),
        );

        if (!$this->user->isAdministrator())
        {
            $return = array();
            foreach($data as $storageUsage)
            {
                if ( $storageUsage->getUsername() == 'testuser1' )
                {
                    $return[] = $storageUsage;
                }
            }
            return $return;
        }
        return $data;
    }
}
