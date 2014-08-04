<?php
/**
 * @author    Arno van Rossum <arno@van-rossum.com>
 * @copyright 2014 Arno van Rossum
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
*/

OCP\App::addNavigationEntry(Array(
    'id'	=> 'ocUsageCharts',
    'order'	=> 60,
    'href' => \OCP\Util::linkToRoute('ocUsageCharts.chart.show'),
    'icon'	=> OCP\Util::imagePath('ocUsageCharts', 'chart.png'),
    'name'	=> \OC_L10N::get('ocUsageCharts')->t('ocUsageCharts')
));

// Register navigation entries
include_once('../lib/registerNavigationEntries.php');
// Register background jobs
include_once('../lib/registerBackgroundJobs.php');
// Register hooks 
include_once('../lib/registerHooks.php');
