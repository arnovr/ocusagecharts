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
use Arnovr\Statistics\Dto\ActivityInformation;


/**
 * Class FileHooks
 * @package OCA\ocUsageCharts\Hooks
 */
class FileHooks {
    /**
     * @var \OCP\Files\Folder
     */
    private $folder;

    /**
     * @var ContentStatisticsClient
     */
    private $contentStatisticsClient;

    /**
     * @param \OC\Files\Node\Root $folder
     * @param ContentStatisticsClient $contentStatisticsClient
     */
    public function __construct(\OC\Files\Node\Root $folder, ContentStatisticsClient $contentStatisticsClient)
    {
        $this->folder = $folder;
        $this->contentStatisticsClient = $contentStatisticsClient;
    }

    public function register()
    {
        $client = $this->contentStatisticsClient;
        $preListener = function ($node) use (&$client) {
            var_dump($node);
            $activityInformation = new ActivityInformation('vagrant', 'something');
            $client->activity($activityInformation);
        };

        $this->folder->listen('\OC\Files', 'delete', $preListener);
        /*
        $this->folder->listen('\OC\Files', 'create', $preListener);
        $this->folder->listen('\OC\Files', 'postDelete', $preListener);
        $this->folder->listen('\OC\Files', 'preDelete', $preListener);
        $this->folder->listen('\OC\Files', 'postTouch',$preListener);
        $this->folder->listen('\OC\Files', 'postCopy', $preListener);
        $this->folder->listen('\OC\Files', 'postRename', $preListener);
        */
    }

    public static function log($arg1)
    {
        var_dump('x');
    }
}