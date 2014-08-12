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

namespace OCA\ocUsageCharts\Dto;

use OCP\AppFramework\Db\DoesNotExistException;
use OCP\AppFramework\Db\Mapper;
use OCP\IDb;

/**
  * @author Arno van Rossum <arno@van-rossum.com>
 */
class FactoryStorageUsage extends Mapper
{

    public function __construct(Idb $db)
    {
        parent::__construct($db, 'uc_storageusage', '\OCA\ocUsageCharts\Dto\StorageUsage');
    }


    public function save(StorageUsage $usage)
    {
        $query = $this->db->prepareQuery('INSERT INTO oc_uc_storageusage (created, username, `usage`) VALUES (?,?,?)');
        $query->execute(Array($usage->getDate()->format('Y-m-d H:i:s'), $usage->getUsername(), $usage->getStorage()));

    }

    public function find($userName) {
        $sql = 'SELECT * FROM `oc_uc_storageusage` WHERE `username` = ?';
        try {
            return $this->findEntity($sql, array($userName));
        }
        catch(DoesNotExistException $e)
        {

        }
    }
    /**
     * Data for what ID
     *
     * @param string $userName
     * @return array
     */
    public function getUsageList($userName)
    {
        $result = $this->find($userName);
        var_dump($result);
        exit;
        // @TODO, retrieve from usagechart repository
        $total = array();
        for($i = 1; $i < 30; $i++)
        {
            $t = $i;
            if ( $i < 10 )
            {
                $t = '0' . $i;
            }
            $total[] = new StorageUsage(new \DateTime($t . '-08-2014'), rand(100, 1000));
        }
        return $total;
    }

}