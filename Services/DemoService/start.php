<?php
/**
 * Date: 2021/4/25 11:00
 * User: YHC
 * Desc: HTTP服务启动文件，启动命令: php start.php dev
 *       特别说明：仅当以dev模式启动时，命令行有信息输出
 */




/*
 * --------------------------------------------------------------------------
 * 定义当前Service名称
 * --------------------------------------------------------------------------
 *
 */
define('HORSE_LOFT_SERVICE', basename(__DIR__));




/*
 * --------------------------------------------------------------------------
 * 启动文件
 * --------------------------------------------------------------------------
 *
 * 环境变量常量 APP_ENV
 *
 * 配置项目启动的基础常量等信息
 *
 * 项目环境配置：
 * 启动命名的参数既是服务的环境变量
 *
 * 例：
 *  以命令：php start.php dev 启动，则设置环境变量为dev，即常量APP_ENV=dev
 *  以命令：php start.php online 启动，则设置环境变量为online，即常量APP_ENV=online
 *
 * 根据常量值，在服务启动时，将加载Config目录下的当前环境变量配置文件；
 * 例：
 *  APP_ENV=dev，当前service为DemoService，则加载demoService.dev.php
 *  推荐：demoService.dev.php文件内容为，define定义的各种常量
 *
 */
require_once __DIR__ . '/../../Library/Core/app.php';




use Library\Utils\Helper;
/*
 * --------------------------------------------------------------------------
 * 使用服务
 * --------------------------------------------------------------------------
 *
 * 不可更改变量名：$horseLoft
 * 变量 $horseLoft 将在全局被引用
 *
 * 第一个参数：服务监听的IP
 * 第二个参数：服务监听的端口
 *
 */
$horseLoft = new \Library\Horseloft\HTTPServer('0.0.0.0', 80);





/*
 * --------------------------------------------------------------------------
 * 【可选项】配置信息
 * --------------------------------------------------------------------------
 *
 * 以下配置名称已被使用，不可被自定义
 * database     数据库配置信息
 *
 */
$horseLoft->setConfigure('redis', 'crontab');





/*
 * --------------------------------------------------------------------------
 * 【可选项】设置日志的存储路径
 * --------------------------------------------------------------------------
 *
 * 例: /var/log
 *
 */
//$horseLoft->setLogPath('/var/log/horseloft');





/*
 * --------------------------------------------------------------------------
 * 【可选项】设置日志文件名称
 * --------------------------------------------------------------------------
 *
 * 例: horseloft
 * 说明：文件名称无需后缀名
 *
 */
//$horseLoft->setLogFilename('horseloft');





/*
 * --------------------------------------------------------------------------
 * 【可选项】创建基于Redis订阅服务的消费队列
 * --------------------------------------------------------------------------
 *
 * 相同的频道名称使用一个setRedisQueue即可
 * 如有多个频道，则需要使用多个setRedisQueue()
 *
 * 如果未能连接Redis则抛出异常
 *
 */
//$horseLoft->setRedisQueue('channelName', Helper::envConfig('redis.main'));





/*
 * --------------------------------------------------------------------------
 * 【可选项】定时任务
 * --------------------------------------------------------------------------
 *
 * 基于swoole的毫秒定时器创建的定时任务
 *
 */
//$horseLoft->setCrontab(Helper::config('crontab.demo'));
//$horseLoft->setCrontab(Helper::config('crontab.demo2'));





/*
 * --------------------------------------------------------------------------
 * 【可选项】启动配置项
 * --------------------------------------------------------------------------
 *
 * 数组格式，参考文档：https://wiki.swoole.com/#/server/setting
 *
 */
$horseLoft->setSwooleConfig(DEMO_SWOOLE_CONFIG);




/*
 * --------------------------------------------------------------------------
 * 【可选项】配置路由
 * --------------------------------------------------------------------------
 *
 * 配置路由之后
 *      将不再使用默认路由
 *
 * 例：setRoute('firstRoute', 'secondRoute') 将自动读取Route目录下名称为firstRoute.php 和 secondRoute.php的文件
 *    文件内容格式：参考Route/default.php
 *
 * 默认路由不支持拦截器
 *
 */
//$horseLoft->setRoute('firstRoute', 'secondRoute');






/*
 * --------------------------------------------------------------------------
 * 【启动
 * --------------------------------------------------------------------------
 *
 */
$horseLoft->start();
