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

namespace OCA\ocUsageCharts\Entity;

use OCA\ocUsageCharts\ChartType\ChartTypeAdapterInterface;
use OCP\AppFramework\Db\Mapper;
use \OCP\IDb;

/**
 * @author Arno van Rossum <arno@van-rossum.com>
 */
class StorageUsageRepository extends Mapper
{
    /**
     * @var \OCP\IDb
     */
    protected $db;

    /**
     * @param IDb $db
     */
    public function __construct(IDb $db) {
        $this->db = $db;
        parent::__construct($db, 'uc_storageusage', '\OCA\ocUsageCharts\Entity\StorageUsage');
    }

    /**
     * Save a storage usage entity to the database
     *
     * @param StorageUsage $usage
     */
    public function save(StorageUsage $usage)
    {
        $query = $this->db->prepareQuery('INSERT INTO oc_uc_storageusage (created, username, `usage`) VALUES (?,?,?)');
        $query->execute(Array($usage->getDate()->format('Y-m-d H:i:s'), $usage->getUsername(), $usage->getUsage()));
    }

    /**
     * @param $userName
     * @param $limit
     * @return array
     */
    public function find($userName, $limit = 30) {
        $sql = 'SELECT * FROM `oc_uc_storageusage` WHERE `username` = ? ORDER BY created DESC LIMIT ' . $limit;
        return $this->findEntities($sql, array($userName));
    }

    /**
     * This method retrieves all usernames,
     * and gets the lastest storage usage for them
     *
     * @param integer $limit
     * @return array
     */
    public function findAll($limit = 30)
    {
        $sql = 'SELECT username FROM `oc_uc_storageusage` GROUP BY username';
        $query = $this->db->prepareQuery($sql);
        $result = $query->execute();
        $entities = array();
        while($row = $result->fetch()){
            if ( !isset($entities[$row['username']]))
            {
                $entities[$row['username']] = array();
            }
            $entities[$row['username']] = array_merge($entities[$row['username']], $this->find($row['username'], $limit));
        }
        return $entities;
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
     * This method retrieves all usernames,
     * and gets the lastest storage usage for them
     *
     * @param \DateTime $created
     * @return array
     */
    public function findAllAfterCreated(\DateTime $created)
    {
        $sql = 'SELECT username FROM `oc_uc_storageusage` GROUP BY username';
        $query = $this->db->prepareQuery($sql);
        $result = $query->execute();
        $entities = array();
        while($row = $result->fetch()){
            if ( !isset($entities[$row['username']]))
            {
                $entities[$row['username']] = array();
            }
            $entities[$row['username']] = array_merge($entities[$row['username']], $this->findAfterCreated($row['username'], $created));
        }
        return $entities;
    }

    /**
     * @return array
     */
    public function findAllPerMonth()
    {
        $sql = 'SELECT DISTINCT CONCAT(MONTH(`created`), \' \', YEAR(`created`)) as month, avg(`usage`) as average, username FROM oc_uc_storageusage GROUP BY username, month';
        $query = $this->db->prepareQuery($sql);
        $result = $query->execute();
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

    /**
     * Retrieve storage usage from cache by username
     *
     * This method exists, because after vigorous trying, owncloud does not supply a proper way
     * to check somebody's used size
     * @param string $userName
     * @return integer
     */
    private function getStorageUsageFromCacheByUserName($userName)
    {
        $data = new \OC\Files\Storage\Home(array('user' => \OC_User::getManager()->get($userName)));
        return $data->getCache('files')->calculateFolderSize('files');
        /*
        $sql = 'select SUM(`size`) as totalsize from oc_filecache WHERE `size` >= 0 AND path LIKE ?';
        $query = $this->db->prepareQuery($sql);
        $result = $query->execute(array($userName . '/files/%'));
        while($row = $result->fetch()) {
            if ( $row['totalsize'] > 0 )
            {
                return $row['totalsize'];
            }
        }
        */
        return 0;
    }

    /**
     * @param string $userName
     */
    public function updateUsage($userName)
    {
        $created = new \DateTime();
        $created->setTime(0,0,0);
        $results = $this->findAfterCreated($userName, $created);

        // Apparently today it is allready scanned, ignore, only update once a day.
        if ( count($results) > 0 )
        {
           return;
        }
        $usage = new StorageUsage(new \Datetime(), $this->getStorageUsageFromCacheByUserName($userName), $userName);
        $this->save($usage);
     }

    /**
     * Check if user is admin
     * small wrapper for owncloud methology
     * @return boolean
     */
    private function isAdminUser()
    {
        return \OC_User::isAdminUser(\OC_User::getUser());
    }

    /**
     * @TODO refactor to proper code
     *
     * @param ChartConfig $config
     * @return ChartTypeAdapterInterface
     *
     * @throws \OCA\ocUsageCharts\Exception\StorageUsageRepositoryException
     */
    public function getUsage(ChartConfig $config)
    {
        switch($config->getChartType())
        {
            default:
            case 'StorageUsageLastMonth':
                $created = new \DateTime("-1 month");
                if ( $this->isAdminUser() )
                {
                    $data = $this->findAllAfterCreated($created);
                }
                else
                {
                    $data = $this->findAfterCreated($config->getUsername(), $created);
                    $data = array($config->getUsername() => $data);
                }
                break;

            case 'StorageUsagePerMonth':
                //@TODO don't need to get everything for one user...
                // Performance and such
                $data = $this->findAllPerMonth();
                if ( !$this->isAdminUser() )
                {
                    $data = array($config->getUsername() => $data[$config->getUsername()]);
                }
                break;
            case 'StorageUsageCurrent':
                $new = array();

                $storageInfo = \OC_Helper::getStorageInfo('/');
                $free = ceil($storageInfo['free'] / 1024 / 1024);
                if ( $this->isAdminUser() )
                {
                    $data = $this->findAll(1);
                    foreach($data as $username => $items)
                    {
                        foreach($items as $item)
                        {
                            $new[$username] = ceil($item->getUsage() / 1024 / 1024);
                        }
                    }
                    $new['free'] = $free;
                    $data = $new;
                }
                else
                {
                    $free = ceil($storageInfo['free'] / 1024 / 1024);
                    $used = ceil($storageInfo['used'] / 1024 / 1024);
                    $data = array(
                        'used' => $used,
                        'free' => $free
                    );
                }
                break;
        }

        // If an adapter has been defined, format the data, else just return data parsed by the system
        $adapter = '\OCA\ocUsageCharts\ChartType\\' .  $config->getChartProvider() . '\Adapters\\' . $config->getChartType() . 'Adapter';
        if ( class_exists($adapter) )
        {
            $chartAdapter = new $adapter($config);
            return $chartAdapter->formatData($data);
        }
        return $data;
    }
}