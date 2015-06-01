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

use OCA\ocUsageCharts\AppInfo\Chart;

\OCP\JSON::checkLoggedIn();
\OCP\JSON::checkAppEnabled('ocusagecharts');
\OCP\JSON::callCheck();
$l = \OCP\Util::getL10N('ocusagecharts');


$app = new Chart();
$container = $app->getContainer();
$configService = $container->query('ChartConfigService');

$validatorKey = 'ocusagecharts-charts-';

foreach($_POST as $key => $value)
{
    if ( substr($key, 0, strlen($validatorKey)) === $validatorKey )
    {
        $id = substr($key, strlen($validatorKey));
        $config = $configService->getChartConfigById($id);
        $config->setMetaData(json_encode(array('size' => $value)));
        $configService->save($config);
    }
}

$url = @$_POST['url'];
if ( !empty($_POST['url']) && filter_var($_POST['url'], FILTER_VALIDATE_URL))
{
    $url = '';
}
$appConfig = $container->query('ServerContainer')->getConfig();
$appConfig->setAppValue('ocusagecharts', 'url', $url);
$appConfig->setAppValue('ocusagecharts', 'username', @$_POST['username']);
$appConfig->setAppValue('ocusagecharts', 'password', @$_POST['password']);


\OCP\JSON::success(array("data" => array( "message" => $l->t('Your settings have been updated.'))));