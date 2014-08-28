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

/**
 * @author Arno van Rossum <arno@van-rossum.com>
 */
class ChartConfigRepository extends Mapper
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
        parent::__construct($db, 'uc_chartconfig', '\OCA\ocUsageCharts\Entity\ChartConfig');
    }

    /**
     * Return ChartConfigs for username given
     * @param $userName
     * @return array|ChartConfig[]
     */
    public function findByUsername($userName) {
        $sql = 'SELECT * FROM `oc_uc_chartconfig` WHERE `username` = ?';
        return $this->findEntities($sql, array($userName));
    }


    /**
     * Save a chartconfig entity to the database
     *
     * @param ChartConfig $config
     */
    public function save(ChartConfig $config)
    {
        $query = $this->db->prepareQuery('INSERT INTO oc_uc_chartconfig(created, username, charttype, chartprovider) VALUES (?,?,?,?)');
        $query->execute(Array($config->getDate()->format('Y-m-d H:i:s'), $config->getUsername(), $config->getChartType(), $config->getChartProvider()));
    }
}