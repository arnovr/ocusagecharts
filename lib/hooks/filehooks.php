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

namespace OCA\ocUsageCharts\Hooks;
use Arnovr\Statistics\ContentStatisticsClient;
use Arnovr\Statistics\Streams\Activity\Activity;
use OCA\ocUsageCharts\Owncloud\User;

/**
 * Class FileHooks
 * @package OCA\ocUsageCharts\Hooks
 */
class FileHooks
{
    /**
     * @var boolean
     */
    private static $sendActivity;

    /**
     * @var ContentStatisticsClient
     */
    private static $contentStatisticsClient;

    /**
     * @param ContentStatisticsClient $contentStatisticsClient
     * @param boolean $sendActivity
     */
    public function __construct(ContentStatisticsClient $contentStatisticsClient, $sendActivity = false)
    {
        self::$sendActivity = $sendActivity;
        self::$contentStatisticsClient = $contentStatisticsClient;
    }

    /**
     * Register the available hooks for files
     */
    public function register()
    {
        $hooks = ['post_write', 'post_delete', 'post_rename', 'post_copy'];

        foreach($hooks as $hook) {
            $this->registerFileHook($hook);
        }
    }

    /**
     * @param string $event
     */
    private function registerFileHook($event)
    {
        $method = substr($event, 5); // remove post_
        \OC_HOOK::connect('OC_Filesystem', $event, 'OCA\ocUsageCharts\Hooks\FileHooks', $method);
    }

    /**
     * @param string $event
     */
    public static function logActivity($event)
    {
        if ( !self::$sendActivity ) {
            return;
        }
        $user = new User();
        $activity = new Activity($user->getSignedInUsername(), 'file:' . $event);
        self::$contentStatisticsClient->commit($activity);
        self::$contentStatisticsClient->push();
    }

    /**
     * Wrapper to capture method name, method is identical to event name
     * @param string $method
     * @param string $arguments
     */
    public static function __callStatic($method, $arguments)
    {
        self::logActivity($method);
    }
}
