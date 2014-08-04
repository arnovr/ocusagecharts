<?php
namespace OCA\ocUsageCharts\Service;
/**
 * @TODO: Change config value methods
 *
 * @author    Arno van Rossum <arno@van-rossum.com>
 * @copyright 2014 Arno van Rossum
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
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