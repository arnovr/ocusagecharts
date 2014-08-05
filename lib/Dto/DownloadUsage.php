<?php
namespace OCA\ocUsageCharts\Dto;

/**
 * Single point in time how much a file is downloaded
 *
 * @todo, this would be a nice to have in the future, first want storage usage
 *
 * @author    Arno van Rossum <arno@van-rossum.com>
 * @copyright 2014 Arno van Rossum
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 */

class StorageUsage
{
    /**
     * @var String
     */
    private $filename;
    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var integer
     */
    private $times;

    public function __construct($filename, \DateTime $date, $times)
    {
        $this->filename = $filename;
        $this->date = $date;
        $this->times = $times;
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
    public function getFilename()
    {
        return $this->filename;
    }

    public function getTimes()
    {
        return $this->times;
    }
}