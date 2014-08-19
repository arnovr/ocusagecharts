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

namespace OCA\ocUsageCharts\Service;

use \stdClass as ChartConfig;

/**
 * @author    Arno van Rossum <arno@van-rossum.com>
 */
class ChartConfigService
{
    /**
     * STUB, This method should retrieve config from database
     *
     * @param integer $id
     *
     * @return ChartConfig
     */
    public function getChartConfigById($id)
    {
        $config = new ChartConfig();
        $config->chartId = $id;
        $config->userName = 'admin';
        $config->chartDataType = 'StorageUsageGraph';
        if ( $id == 1 )
        {
            $config->chartDataType = 'StorageUsageInfo';
        }
        $config->chartProvider = 'c3js';
        $config->extraConfig = $_GET;
        return $config;
    }

    /**
     * Get chart config for a specific user
     * @TODO retrieve config getChartConfigById
     *
     * @param string $userName
     * @return array
     */
    public function getChartConfig($userName)
    {
        $config = new ChartConfig();
        $config->chartId = '1';
        $config->userName = $userName;
        $config->chartProvider = 'c3js';
        $config->chartDataType = 'StorageUsageInfo';
        $config->extraConfig = $_GET;

        $config2 = new ChartConfig();
        $config2->chartId = '2';
        $config2->userName = $userName . '2';
        $config2->chartProvider = 'c3js';
        $config2->chartDataType = 'StorageUsageGraph';
        $config->extraConfig = $_GET;

        return array($config, $config2);


    }


    /*
    public static function getUConfValue($key, $default = NULL){
        $query = \OCP\DB::prepare("SELECT uc_id,uc_val FROM *PREFIX*usage_charts_uconf WHERE oc_uid = ? AND uc_key = ?");
        $result = $query->execute(Array(\OCP\User::getUser(), $key))->fetchRow();
        if($result){
            return $result;
        }
        return $default;
    }

    public static function setUConfValue($key,$val){
        $conf = self::getUConfValue($key);
        if(!is_null($conf)){
            $query = \OCP\DB::prepare("UPDATE *PREFIX*usage_charts_uconf SET uc_val = ? WHERE uc_id = ?");
            $query->execute(Array($val, $conf['uc_id']));
        }else{
            $query = \OCP\DB::prepare("INSERT INTO *PREFIX*usage_charts_uconf (oc_uid,uc_key,uc_val) VALUES (?,?,?)");
            $query->execute(Array(\OCP\User::getUser(), $key, $val));
        }
    }
    */
}