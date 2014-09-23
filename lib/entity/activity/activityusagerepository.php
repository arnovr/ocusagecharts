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
namespace OCA\ocUsageCharts\Entity\Activity;

use OCP\AppFramework\Db\Mapper;
use OCP\IDb;

class ActivityUsageRepository extends Mapper
{
    /**
     * @param IDb $db
     */
    public function __construct(IDb $db) {
        parent::__construct($db, 'activity', '\OCA\ocUsageCharts\Entity\ActivityUsage');
    }

    /**
     * Find all activity usages after the created date
     * When username is given, it will return only activities for that user
     *
     * @param \DateTime $created
     * @param string $username
     * @return ActivityUsage[]
     */
    public function findAfterCreated(\DateTime $created, $username = '') {
        $params = array(
            $created->getTimestamp()
        );

        $sql = 'SELECT * FROM `activity` WHERE `user` = ? AND `timestamp` > ? ORDER BY timestamp DESC';
        if ( $username !== '' )
        {
            $sql = 'SELECT * FROM `activity` WHERE `timestamp` > ? ORDER BY timestamp DESC';
            $params[] = $username;
        }

        $this->findEntities($sql, $params);
    }
}
