<?php
/**
 * Copyright (c) 2014 - Arno van Rossum <arno@van-rossum.com>
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

namespace OCA\ocUsageCharts\Owncloud;

use OC\Files\View as FilesView;

class Storage
{
    /**
     * @var User
     */
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Retrieve storage usage username
     *
     * This method exists, because after vigorous trying, owncloud does not supply a proper way
     * to check somebody's used size
     *
     * @param string $userName
     * @return integer
     */
    public function getStorageUsage($userName)
    {
        $data = new \OC\Files\Storage\Home(array('user' => \OC_User::getManager()->get($userName)));
        return $data->getCache('files')->calculateFolderSize('files');
    }

    /**
     * Retrieve the current storage usage for the user that is signed in
     *
     * @return array ( array('free' => bytes, 'used' => bytes) )
     */
    public function getCurrentStorageUsageForSignedInUser()
    {
        $username = $this->user->getSignedInUsername();
        $view = new FilesView('/' . $username . '/files');
        $freeSpace = $view->free_space();
        $usedSpace = $view->getFileInfo('/')->getSize();

        return array(
            'free' => $freeSpace,
            'used' => $usedSpace
        );
    }
}
