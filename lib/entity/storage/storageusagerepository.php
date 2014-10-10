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

namespace OCA\ocUsageCharts\Entity\Storage;

use OCP\AppFramework\Db\Mapper;
use \OCP\IDb;

/**
 * @author Arno van Rossum <arno@van-rossum.com>
 */
class StorageUsageRepository extends Mapper
{
    /**
     * @param IDb $db
     */
    public function __construct(IDb $db) {
        parent::__construct($db, 'uc_storageusage', '\OCA\ocUsageCharts\Entity\Storage\StorageUsage');
    }

    /**
     * Save a storage usage entity to the database
     *
     * @param StorageUsage $usage
     * @return boolean
     */
    public function save(StorageUsage $usage)
    {
        $query = $this->db->prepareQuery('INSERT INTO oc_uc_storageusage (created, username, `usage`) VALUES (?,?,?)');
        $result = $query->execute(Array($usage->getDate()->format('Y-m-d H:i:s'), $usage->getUsername(), $usage->getUsage()));
        /*
         * $query->execute could return integer or OC_DB_StatementWrapper or false
         * I am expecting an integer with number 1
         */
        if ( is_int($result) && $result === 1 )
        {
            return true;
        }
        return false;
    }

    /**
     * @param string $userName
     * @param integer $limit
     * @return array
     */
    public function find($userName, $limit = 30) {
        $sql = 'SELECT * FROM `oc_uc_storageusage` WHERE `username` = ? ORDER BY created DESC LIMIT ' . $limit;
        return $this->findEntities($sql, array($userName));
    }

    /**
     * This method retrieves per username the latest storage usage with a limit of given
     *
     * @param integer $limit
     * @return array
     */
    public function findAllWithLimit($limit = 30)
    {
        return $this->findAll($limit);

    }

    /**
     * This method retrieves per username the latest storage usage from date given
     *
     * @param \DateTime $created
     * @return array
     */
    public function findAllAfterCreated(\DateTime $created)
    {
        return $this->findAll(30, $created);
    }

    /**
     * This method retrieves all usernames,
     * and gets the latest storage usage for them
     *
     * Limit is bogus when $afterCreated is used
     *
     * @param integer $limit
     * @param \DateTime $afterCreated
     *
     * @return array
     */
    private function findAll($limit = 30, \DateTime $afterCreated = null)
    {
        $sql = 'SELECT username FROM `oc_uc_storageusage` WHERE `usage` > 0 GROUP BY username';
        $query = $this->db->prepareQuery($sql);
        $result = $query->execute();
        $entities = array();
        while($row = $result->fetch()){
            if ( empty($entities[$row['username']]) )
            {
                $entities[$row['username']] = array();
            }

            $entities[$row['username']] = array_merge(
                $entities[$row['username']],
                $this->findEntitiesBasedOnOrCreated($row['username'], $limit, $afterCreated)
            );
        }
        return $entities;
    }

    /**
     * @param string $username
     * @param integer $limit
     * @param \DateTime $afterCreated
     * @return array
     */
    private function findEntitiesBasedOnOrCreated($username, $limit, \DateTime $afterCreated = null)
    {
        if ( !is_null($afterCreated) )
        {
            $return = $this->findAfterCreated($username, $afterCreated);
        }
        else
        {
            $return = $this->find($username, $limit);
        }

        return $return;
    }

    /**
     * @param string $userName
     * @param \DateTime $created
     * @return array
     */
    public function findAfterCreated($userName, \DateTime $created) {
        $sql = 'SELECT * FROM `oc_uc_storageusage` WHERE `username` = ? AND `created` > ? ORDER BY created DESC';
        return $this->findEntities($sql, array($userName, $created->format('Y-m-d H:i:s')));
    }

    /**
     * Find all storage usages grouped by username and month
     * When username supplied, only for that user
     * With a maximum of going back 1 year
     *
     * @param string $username
     * @return array
     */
    public function findAllPerMonth($username = '')
    {
        $created = new \DateTime();
        $created->sub(new \DateInterval('P1Y'));

        // When no username supplied, search for all information
        $sql = 'SELECT DISTINCT CONCAT(MONTH(`created`), \' \', YEAR(`created`)) as month, avg(`usage`) as average, username FROM oc_uc_storageusage WHERE `usage` > 0 AND created > ? GROUP BY username, month';
        $params = array();

        // Username is supplied, get results only for that user
        if ( $username !== '' )
        {
            $sql = 'SELECT DISTINCT CONCAT(MONTH(`created`), \' \', YEAR(`created`)) as month, avg(`usage`) as average, username FROM oc_uc_storageusage WHERE `usage` > 0 AND username = ? AND created > ? GROUP BY month';
            $params = array($username);
        }
        $params[] = $created->format('Y-m-d H:I:s');
        $query = $this->db->prepareQuery($sql);
        $result = $query->execute($params);

        return $this->parsePerMonthEntities($result);
    }

    /**
     * Parse the results from the per month entities found
     *
     * @param \OC_DB_StatementWrapper $result
     * @return array
     */
    private function parsePerMonthEntities($result)
    {
        $entities = array();
        while($row = $result->fetch()){
            if ( !isset($entities[$row['username']]))
            {
                $entities[$row['username']] = array();
            }
            $date = explode(' ', $row['month']);
            $dateTime = new \Datetime();
            $dateTime->setDate($date[1], $date[0], 1);

            $entities[$row['username']] = array_merge(
                $entities[$row['username']],
                array(new StorageUsage($dateTime, $row['average'], $row['username']))
            );
        }


        return $entities;
    }
}