<?php
namespace OCA\ocUsageCharts\Command;
use OCA\ocUsageCharts\AppInfo\Chart;

/**
* @author    Arno van Rossum <arno@van-rossum.com>
* @copyright 2014 Arno van Rossum
* @license   http://www.opensource.org/licenses/mit-license.php MIT
*/
class UpdateUserStorageCommand
{
    public static function run() {
        $app = new Chart();
        $container = $app->getContainer();
        $container->query('ChartStorageUpdater')->updateUserStorage();
    }
}