<?php


namespace App\Crontab;

use EasySwoole\EasySwoole\Crontab\AbstractCronTask;


class TaskTwo extends  AbstractCronTask
{
    public static function getRule(): string
    {
        // TODO: Implement getRule() method.
        // 定时周期 （每两分钟一次）
        return '*/2 * * * *';
    }

    public static function getTaskName(): string
    {
        // TODO: Implement getTaskName() method.
        // 定时任务名称
        return 'taskTwo';
    }

    static function run(\swoole_server $server, int $taskId, int $fromWorkerId,$flags=null)
    {
        // TODO: Implement run() method.
        // 定时任务处理逻辑
        var_dump('run every two minutes');
    }
}