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

use OCA\ocUsageCharts\ChartType\ChartTypeViewInterface;
use OCA\ocUsageCharts\Exception\StorageUsageRepositoryException;
use OCP\AppFramework\Db\Mapper;
use \OCP\IDb;
use \stdClass as ChartDataConfig;

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
     * @TODO select on date specific
     * @param $userName
     * @param $limit
     * @return array
     */
    public function find($userName, $limit = 30) {
        $sql = 'SELECT * FROM `oc_uc_storageusage` WHERE `username` = ? ORDER BY created DESC LIMIT ' . $limit;
        return $this->findEntities($sql, array($userName));
    }

    public function updateUsage(ChartDataConfig $config)
    {
        switch($config->dataType)
        {
            case 'StorageUsage':
                $userDir = '/'.$config->userName.'/files';
                $view = new \OC\Files\View('/');
                $data = $view->getFileInfo($userDir, false);
                $used = 0;
                if ( $data instanceof \OC\Files\FileInfo )
                {
                    $fileInfoData = $data->getData();
                    $used = $fileInfoData['size'];
                    if ( $used < 0 )
                    {
                        $used = 0;
                    }
                }
                $usage = new StorageUsage(new \Datetime(), $used, $config->userName);
                $this->save($usage);
                break;
        }
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
     * @return ChartTypeViewInterface
     *
     * @throws \OCA\ocUsageCharts\Exception\StorageUsageRepositoryException
     */
    public function getUsage(ChartConfig $config)
    {
        switch($config->getChartType())
        {
            default:
            case 'StorageUsageGraph':
                if ( $this->isAdminUser() )
                {
                    $data = $this->findAll();
                }
                else
                {
                    $data = $this->find($config->getUsername());
                    $data = array($config->getUsername() => $data);
                }
                break;
            case 'StorageUsageInfo':
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

        $view = '\OCA\ocUsageCharts\ChartType\\' .  $config->getChartProvider() . '\Views\\' . $config->getChartType() . 'View';

        if ( !class_exists($view) )
        {
            throw new StorageUsageRepositoryException("View for " . $config->getChartType() . ' does not exist.');
        }
        $chartView = new $view($config);
        return $chartView->show($data);
    }
}