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

    public function save(StorageUsage $usage)
    {
        $query = $this->db->prepareQuery('INSERT INTO oc_uc_storageusage (created, username, `usage`) VALUES (?,?,?)');
        $query->execute(Array($usage->getDate()->format('Y-m-d H:i:s'), $usage->getUsername(), $usage->getStorage()));

    }

    public function find($userName) {
        $sql = 'SELECT * FROM `oc_uc_storageusage` WHERE `username` = ?';
        return $this->findEntity($sql, array($userName));
    }

    public function updateUsage(ChartDataConfig $config)
    {
        switch($config->dataType)
        {
            case 'StorageUsage':
                \OC_Util::setupFS($config->userName);
                $storageInfo = \OC_Helper::getStorageInfo('/', $dirInfo);
                $usage = new StorageUsage(new \Datetime(), $storageInfo['used'], $config->userName);
                $this->save($usage);
                break;
        }
    }



    /**
     * @param ChartDataConfig $config
     * @return array
     */
    public function getUsage(ChartDataConfig $config)
    {
        switch($config->chartDataType)
        {
            default:
            case 'StorageUsageList':
                $usage = new FactoryStorageUsage($this->db);
                $data = $usage->getUsageList($config->userName);        // @TODO nice parser...
                $new = array();
                foreach($data as $item)
                {
                    $new[] = $item->toJson();
                }
                $data = array($config->userName => $new);
                break;
            case 'StorageUsageFree':
                $storageInfo = \OC_Helper::getStorageInfo('/');
                $used = ceil($storageInfo['used'] / 1024 / 1024);
                $free = ceil($storageInfo['free'] / 1024 / 1024);
                $data = array(
                    'used' => $used,
                    'free' => $free
                );
                break;
        }

        return $data;
    }
}