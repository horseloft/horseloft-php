<?php
/**
 * Date: 2021/5/10 18:13
 * User: YHC
 * Desc: worker级别是数据库连接池
 */

namespace Library\Pool;

use Library\Core\Database\Handle\ConnectHandle;
use Library\Core\Database\Handle\ConnectionHandle;
use Library\Pdo\Transaction;
use Library\Core\Database\Factory\Proxy;

class Table
{
    use ConnectHandle,ConnectionHandle;

    /*
     * ----------------------------------------------------------------------------
     * 数据表名称
     * ----------------------------------------------------------------------------
     *
     * 如果继承了当前类，则允许在子类中声明静态属性 $table 并赋值
     *
     */
    public static $table = '';

    /*
     * ----------------------------------------------------------------------------
     * 数据库配置|字符串或数组
     * ----------------------------------------------------------------------------
     *
     *【1】$connection是数组，那么必须是一维数组，并且内容为：
     * $connection = [
     *      'driver' => 'mysql',        //必填项 dblib/mysql
     *      'host' => '127.0.0.1',      //必填项 database host
     *      'port' => 3306,             //必填项 database port
     *      'username' => 'username',   //必填项 database username
     *      'password' => 'password',   //必填项 database password
     *      'database' => 'database'    //必填项 database name
     *      'charset' => 'utf8'         //选填项 database charset
     * ]
     *
     *【2】$connection是字符串
     * 例：$connection = 'mysql';
     *
     *    //必须在启动文件中声明并加载了database配置文件
     *    $horseLoft->setConfigure(['database']);
     *
     *    此时 /Config/ 或 /Services/xxxService/Config/ 目录下应该有 database.php 文件
     *
     *    database.php文件内容应有索引为 mysql 的配置
     *
     *    return [
     *      APP_ENV => [
     *          'mysql' => [
     *              'driver' => 'mysql',        //必填项 dblib/mysql
     *              'host' => '127.0.0.1',      //必填项 database host
     *              'port' => 3306,             //必填项 database port
     *              'username' => 'username',   //必填项 database username
     *              'password' => 'password',   //必填项 database password
     *              'database' => 'database'    //必填项 database name
     *              'charset' => 'utf8'         //选填项 database charset
     *          ]
     *      ]
     *    ];
     */
    public static $connection;

    //连接池
    public static $pool = [];

    /**
     *
     * @return \Library\Core\Database\Factory\PDO
     */
    public static function pdo()
    {
        return self::getInstance()->pdo();
    }

    /**
     *
     * @return Transaction
     */
    public static function transaction()
    {
        return self::getInstance()->transaction();
    }

    /**
     *
     * @return \Library\Pdo\Select
     */
    public static function select(string $column = '*')
    {
        $select = self::getInstance()->select();

        $select->column($column);

        return $select;
    }

    /**
     *
     * @param array $data
     * @return \Library\Pdo\Update
     */
    public static function update(array $data = [])
    {
        $update = self::getInstance()->update();

        if (!empty($data)) {
            $update->set($data);
        }

        return $update;
    }

    /**
     *
     * @param array $data
     * @return \Library\Pdo\Insert
     */
    public static function insert(array $data = [])
    {
        $insert = self::getInstance()->insert();

        if (!empty($data)) {
            $insert->builder($data);
        }

        return $insert;
    }

    /**
     *
     * @return \Library\Pdo\Delete
     */
    public static function delete()
    {
        return self::getInstance()->delete();
    }

    /**
     *
     * @return Proxy
     */
    private static function getInstance()
    {
        $connection = self::connection(static::$connection);

        return new Proxy(self::getKey($connection), static::$table, self::getConnect($connection));
    }

    /**
     *
     * @return string
     */
    private static function getKey(array $config)
    {
        return md5(serialize($config));
    }

    /**
     *
     * @return \PDO
     */
    private static function getConnect(array $config)
    {
        $key = self::getKey($config);

        if (!empty(self::$pool[$key])) {
            $connect = array_shift(self::$pool[$key]);

            //验证$connect是否过期
            $statement = $connect->query('select 1 from ' . static::$table . ' limit 1');
            if ($statement == false) {
                return (new self())->connect($config);
            }

            return $connect;
        }

        return (new self())->connect($config);
    }
}