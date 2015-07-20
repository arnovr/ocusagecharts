<?php
/**
 * Copyright (c) 2015 - Arno van Rossum <arno@van-rossum.com>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

use Arnovr\Statistics\Api\ApiConnection;
use GuzzleHttp\Exception\RequestException;
use OCA\ocUsageCharts\AppInfo\Chart;

\OCP\JSON::checkLoggedIn();
\OCP\JSON::checkAppEnabled('ocusagecharts');
\OCP\JSON::callCheck();
$l = \OCP\Util::getL10N('ocusagecharts');

$app = new Chart();
$container = $app->getContainer();
/** @var ApiConnection $apiConnection */
$apiConnection = $container->query('ContentStatisticsClientApiConnection');


$url = @$_POST['url'];

if(empty($url) || ( !filter_var($url, FILTER_VALIDATE_IP) && !filter_var(gethostbyname($url), FILTER_VALIDATE_IP)) )
{
    \OCP\JSON::error(array("data" => array( "message" => $l->t('Incorrect domainname or ip given'))));
    exit;
}

$dashboardUrl = @$_POST['dashboard_url'];
if ( !empty($dashboardUrl) && !filter_var($dashboardUrl, FILTER_VALIDATE_URL))
{

    \OCP\JSON::error(array("data" => array( "message" => $l->t('Dashboard url is incorrect'))));
    exit;
}

$useApi = false;
if ( @$_POST['useapi_enabled'] )
{
    try {
        $useApi = $apiConnection->testConnection();
    }
    catch(RequestException $exception) {
    }
}

$appConfig = $container->query('ServerContainer')->getConfig();
$appConfig->setAppValue('ocusagecharts', 'useapi', $useApi);
$appConfig->setAppValue('ocusagecharts', 'url', 'http://' . $url);
$appConfig->setAppValue('ocusagecharts', 'username', @$_POST['username']);
$appConfig->setAppValue('ocusagecharts', 'password', @$_POST['password']);
$appConfig->setAppValue('ocusagecharts', 'dashboard_url', $dashboardUrl);

\OCP\JSON::success(array("data" => array( "message" => $l->t('Your settings have been updated.'))));