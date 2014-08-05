<?php
namespace OCA\ocUsageCharts\Service;


/**
 * @author    Arno van Rossum <arno@van-rossum.com>
 * @copyright 2014 Arno van Rossum
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 */
class ChartStorageUpdater
{
    /**
     * @var ChartDataProvider
     */
    private $provider;

    /**
     * @param ChartDataProvider $provider
     */
    public function __construct(ChartDataProvider $provider)
    {
        $this->provider = $provider;
    }

    /**
     * @stub
     */
    public function updateUserStorage()
    {
        // Loop customers and update database
    }
}