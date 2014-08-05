<?php
namespace OCA\ocUsageCharts\Dto;

/**
 * Single point in time of storage usage
 *
 * @author    Arno van Rossum <arno@van-rossum.com>
 * @copyright 2014 Arno van Rossum
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 */

class StorageUsage
{
    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var integer Kilobytes
     */
    private $storage;

    public function __construct(\DateTime $date, $storage)
    {
        $this->date = $date;
        $this->storage = $storage;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return integer
     */
    public function getStorage()
    {
        return $this->storage;
    }
}