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

class User
{
    /**
     * @return string
     */
    public function getSignedInUsername()
    {
        return \OCP\User::getUser();
    }

    /**
     * Check if username given is admin
     *
     * @param string $username
     * @return boolean
     */
    public function isAdminUser($username)
    {
        return \OC_User::isAdminUser($username);
    }

    /**
     * Return all users from the current system
     *
     * @return array
     */
    public function getSystemUsers()
    {
        return \OC_User::getUsers();
    }

    /**
     * Returns the display name for the uid given
     *
     * @param string $uid
     * @return string
     */
    public function getDisplayName($uid)
    {
        return \OC_User::getDisplayName($uid);
    }
}
