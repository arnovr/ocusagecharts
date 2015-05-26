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

use OCA\ocUsageCharts\Dto\ActivityInformation;
use OCA\ocUsageCharts\Service\ActivityConnector;

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
     * @var ActivityConnector
     */
    private $api;

    /**
     * @param \OCP\Files\Folder $folder
     * @param ActivityConnector $api
     */
    public function __construct(\OCP\Files\Folder $folder, ActivityConnector $api)
    {
        $this->folder = $folder;
        $this->api = $api;

        $this->register();
    }

    private function register()
    {
        $this->folder->listen('\OC\Files', 'postWrite', $this->createCallback('file:write'));
        $this->folder->listen('\OC\Files', 'postCreate', $this->createCallback('file:create'));
        $this->folder->listen('\OC\Files', 'postDelete', $this->createCallback('file:delete'));
        $this->folder->listen('\OC\Files', 'postTouch', $this->createCallback('file:touched'));
        $this->folder->listen('\OC\Files', 'postCopy', $this->createCallback('file:copied'));
        $this->folder->listen('\OC\Files', 'postRename', $this->createCallback('file:renamed'));
    }

    /**
     * @param string $event
     * @return callable
     */
    public function createCallback($event)
    {
        // listen on user predelete
        return function(\OCP\Files\Node $node) use ($event) {
            $activityInformation = new ActivityInformation('TODO', $event);
            $this->api->activity($activityInformation);
        };
    }
}