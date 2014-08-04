<?php
namespace OCA\ocUsageCharts\Entity;

use \OCP\IDb;
use \OCP\AppFramework\Db\Mapper;

/**
 * @TODO, mapper stuff http://doc.owncloud.org/server/7.0/developer_manual/app/database.html
 * @author    Arno van Rossum <arno@van-rossum.com>
 * @copyright 2014 Arno van Rossum
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 */
class UsageChartRepository extends Mapper
{
    public function __construct(IDb $db) {
        //todo
        //parent::__construct($db, 'ocUsageCharts');
    }
}