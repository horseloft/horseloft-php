<?php
/*
 * ------------------------------------------------------------------
 * horseloft项目的基础配置文件
 * ------------------------------------------------------------------
 *
 * 当前配置文件不区分项目运行的环境变量；
 * 仅当 application.php 中的 process 字段全等于 true 时，以 while(true) 模式执行当前文件中定义的任务
 */

return [
    /*
     * 1. 数组下标作为进程的名称
     *
     * 2. callback 自定义进程执行的回调方法
     *
     * 3. args 自定义进程执行的回调方法的参数
     */
    'first_process' => [
        'callback' => ['\Application\Controller\DemoController', 'index'],
        'args' => []
    ],
];