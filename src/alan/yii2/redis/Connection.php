<?php
/**
 * Created by PhpStorm.
 * User: alan
 * Date: 2018/2/26
 * Time: 12:11
 */

namespace alan\yii2\redis;


use yii\helpers\Inflector;
use yii\db\Exception;


class Connection extends \yii\redis\Connection
{

    /**
     * Allows issuing all supported commands via magic methods.
     *
     * ```php
     * $redis->hmset('test_collection', 'key1', 'val1', 'key2', 'val2')
     * ```
     *
     * @param string $name name of the missing method to execute
     * @param array $params method call arguments
     * @return mixed
     */
    public function __call($name, $params)
    {
        $redisCommand = strtoupper(Inflector::camel2words($name, false));
        if (in_array($redisCommand, $this->redisCommands)) {
            try{
                return $this->executeCommand($redisCommand, $params);
            }catch (Exception $exception){
                $this->_socket = false;
                return $this->executeCommand($redisCommand, $params);
            }

        } else {
            return parent::__call($name, $params);
        }
    }
}
