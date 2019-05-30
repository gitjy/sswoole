<?php


namespace App\HttpController;


//use EasySwoole\Http\AbstractInterface\Controller Ctrl;
use EasySwoole\Component\Timer;

use EasySwoole\EasySwoole\Logger;
use EasySwoole\EasySwoole\Swoole\Task\TaskManager;
use EasySwoole\Component\Di;
use EasySwoole\EasySwoole\SysConst;
use EasySwoole\EasySwoole\Config;

class Tool extends Ctrl
{
    function index()
    {
        echo "hello\n";
    }

    /*
     *定时器
     */
    function timer()
    {
        $timerName = 'puppy';
        $timerId = Timer::getInstance()->loop(1000, function () use ($timerName) {
            $loop = Config::getInstance()->getConf($timerName);
            $loop++;
            Config::getInstance()->setConf($timerName, $loop);
            echo "this timer run intevals 1 seconds\n";
            echo "times " . $loop . "\n";
        }, $timerName);
        Timer::getInstance()->after(1000, function () {
            echo "this timer run after 1 seconds\n";
        });
        //闭包投递 无法用异步任务开启的定时任务，无法关闭
        /*TaskManager::async(function () use ($timerName) {
            $timerId = Timer::getInstance()->loop(1000, function () use ($timerName) {
                $loop = Config::getInstance()->getConf($timerName);
                $loop++;
                Config::getInstance()->setConf($timerName, $loop);
                echo "this timer run intevals 1 seconds\n";
                echo "times " . $loop . "\n";
            }, $timerName);
            echo "timerId id " . $timerId . "\n";
            return true;
            },
            function () {echo "异步开启任务执行完毕...\n";}
            );*/


        //5秒后关闭异步任务
        $timerIdOrName = $timerName;
        //闭包投递 无法用异步任务中关闭定时任务
        TaskManager::async(function () use ($timerIdOrName) {
            sleep(3);
            $ret = Timer::getInstance()->clear($timerIdOrName);
            echo "异步清理定时任务{$timerIdOrName}...: " . ($ret ? '成功' :'失败') . "\n";
            //echo "清理定时任务{$timerName}...\n";
            return true;},
            function () {echo "异步任务执行完毕...\n";});

        Timer::getInstance()->after(5000, function () use ($timerIdOrName) {
            $ret = Timer::getInstance()->clear($timerIdOrName);
            echo "清理定时任务{$timerIdOrName}...: " . ($ret ? '成功' :'失败') . "\n";
            //Timer::getInstance()->clearAll();

        });

    }

    function consoleLog()
    {
        Logger::getInstance()->console("time 3", false);
    }

    function getDi()
    {
        $logger = Di::getInstance()->get(SysConst::LOGGER_HANDLER);
        var_dump($logger);

    }
}