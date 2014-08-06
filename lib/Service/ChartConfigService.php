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

/**
 * @TODO: Change config value methods
 *
 * @author    Arno van Rossum <arno@van-rossum.com>
 */
class ChartConfigService
{
    /**
     * Get configuration values stored in the database
     * @param $key The conf key
     * @param $default
     * @return Array The conf value
     */
    public static function getUConfValue($key, $default = NULL){
        $query = \OCP\DB::prepare("SELECT uc_id,uc_val FROM *PREFIX*usage_charts_uconf WHERE oc_uid = ? AND uc_key = ?");
        $result = $query->execute(Array(\OCP\User::getUser(), $key))->fetchRow();
        if($result){
            return $result;
        }
        return $default;
    }

    /**
     * @brief Put configuration values into the database
     * @param $key The conf key
     * @param $val The conf value
     */
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
}