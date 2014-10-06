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

use OC_DB_StatementWrapper;
use OCP\AppFramework\Db\Mapper;
use OCP\IDb;

class ActivityUsageRepository extends Mapper
{
    /**
     * @param IDb $db
     */
    public function __construct(IDb $db) {
        parent::__construct($db, 'activity', '\OCA\ocUsageCharts\Entity\Activity\ActivityUsage');
    }

    /**
     * Find all activity usages after the created date
     * When username is given, it will return only activities for that user
     *
     * @param \DateTime $created
     * @param string $username
     * @return ActivityUsage[]
     */
    public function findAfterCreatedByUsername(\DateTime $created, $username) {
        $params = array(
            $username,
            $created->getTimestamp()
        );
        $sql = 'SELECT * FROM `oc_activity` WHERE `user` = ? AND `timestamp` > ? ORDER BY timestamp DESC';
        return $this->findEntities($sql, $params);
    }

    /**
     * Calculate the start and end timestamp based on parameters given
     *
     * @param integer $month
     * @param \DateTime $created
     * @return array
     */
    private function calculateStartAndEndTimestamp($month, \DateTime $created)
    {
        $endTimestamp = $created->getTimestamp();

        // First month is first day for this month
        if ( $month == 1 )
        {
            $created->setDate($created->format('Y'), $created->format('m'), 1);
        }
        else
        {
            $created->sub(new \DateInterval('P1M'));
        }
        $startTimestamp = $created->getTimestamp();
        return array($startTimestamp, $endTimestamp);
    }

    /**
     * Find all activity usages grouped by username and month
     * When username supplied, only for that user
     * With a maximum of going back 1 year
     *
     * @param string $username
     * @return array
     */
    public function findAllPerMonth($username = '')
    {
        $return = array();
        $created = new \DateTime();
        // Months, maximum 1 year
        $runs = 12;
        for($i = 1; $i <= $runs; $i++)
        {
            list($startTimestamp, $endTimestamp) = $this->calculateStartAndEndTimestamp($i, $created);
            if ( $username !== '' )
            {
                $return[$created->format('Y-m-d')] = $this->findActivitiesBetweenTimestampAndUser($startTimestamp, $endTimestamp, $username);
            }
            else
            {
                $return[$created->format('Y-m-d')] = $this->findActivitiesBetweenTimestamp($startTimestamp, $endTimestamp);
            }
        }
        return $return;
    }

    /**
     * Return all activities for all users between a certain timestamp
     *
     * @param integer $startTimestamp
     * @param integer $endTimestamp
     * @return array
     */
    private function findActivitiesBetweenTimestamp($startTimestamp, $endTimestamp)
    {
        $sql = 'SELECT user, count(1) as activities, user FROM oc_activity WHERE timestamp >= ? AND timestamp < ? GROUP BY user';
        $params = array(
            $startTimestamp, $endTimestamp
        );
        return $this->findActivitiesBySQLAndParams($sql, $params);
    }

    /**
     * * Return all activities between a certain timestamp, for a specific username
     *
     * @param integer $startTimestamp
     * @param integer $endTimestamp
     * @param string $username
     * @return array
     */
    private function findActivitiesBetweenTimestampAndUser($startTimestamp, $endTimestamp, $username)
    {
        $sql = 'SELECT user, count(1) as activities, user FROM oc_activity WHERE timestamp >= ? AND timestamp < ? AND user = ? GROUP BY user';
        $params = array(
            $startTimestamp, $endTimestamp, $username
        );
        return $this->findActivitiesBySQLAndParams($sql, $params);
    }

    /**
     * @param string $sql
     * @param array $params
     * @return array
     */
    private function findActivitiesBySQLAndParams($sql, $params)
    {
        $query = $this->db->prepareQuery($sql);
        $result = $query->execute($params);
        return $this->parsePerMonthEntities($result);
    }

    /**
     * Just some method to parse database results for
     *
     * @param OC_DB_StatementWrapper $result
     * @return array
     */
    private function parsePerMonthEntities($result)
    {
        $entities = array();
        while($row = $result->fetch()){
            if ( !isset($entities[$row['user']]))
            {
                $entities[$row['user']] = "";
            }
            $entities[$row['user']] = $row['activities'];
        }
        return $entities;
    }
}
