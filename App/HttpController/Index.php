<?php
namespace App\HttpController;

use EasySwoole\EasySwoole\Config;
use EasySwoole\Http\AbstractInterface\Controller;

use EasySwoole\EasySwoole\Swoole\Task\TaskManager;  //任务管理器
use App\Task\Task; //任务模板
use App\Task\QuickTask; //任务模板

use EasySwoole\EasySwoole\EasySwooleEvent;

class Index extends Controller
{

    //输出数据
    function  index()
    {
        echo $str = "中国啊，我亲爱的中国";
//        $this->response()->write('Hello world! -.-');
//        $this->response()->write('next ! -.-');
        $this->response()->write($str);
        //$this->response()->write(['name' => 'lisi']);
        $this->response()->withHeader('Content-type','application/json;charset=utf-8');
    }

    //打印数据 在终端显示
    function  dump()
    {
        echo "hello world";
        var_dump('hello');
    }

    //打印数据 捕获再写入
    function  prefDump()
    {
        ob_start();
        echo "hello world";
        var_dump('hello');
        $rt = ob_get_clean();
        $this->response()->write($rt);
    }

    /*打印数据 在终端显示
    * 常量
    */
    function  const()
    {
        $this->response()->write(EASYSWOOLE_ROOT);
        $this->response()->withHeader('Content-type','application/json;charset=utf-8');
    }

    function setConfig()
    {

        Config::getInstance()->setConf('level.presenter_division.13', 'asimi');
        $conf = Config::getInstance()->getConf('level.presenter_division');
        $this->response()->write(json_encode($conf, 256));
        $this->response()->withHeader('Content-type','application/json;charset=utf-8');
    }

    function getConfig()
    {
        $conf = Config::getInstance()->getConf();
        $this->response()->write(json_encode($conf, 384));
        $this->response()->withHeader('Content-type','application/json;charset=utf-8');
    }

    function config1()
    {
        $conf['dynamic'] = Config::getInstance()->getConf(Config::getInstance()->getDynamicConf('CONSOLE.PUSH_LOG'));
        $conf['conf'] = Config::getInstance()->getConf('CONSOLE.PUSH_LOG');
        $this->response()->write('config' . json_encode($conf, 256));
        $this->response()->withHeader('Content-type','application/json;charset=utf-8');
    }

    function log()
    {
        //$this->response()->withHeader('Content-type','application/json;charset=utf-8');
    }

    /*投递任务
     * !!! 任务完成需要 return 结果
    **/
    function taskManager()
    {
        //闭包投递
        TaskManager::async(function () {echo "执行异步任务...\n";return true;},
            function () {echo "异步任务执行完毕...\n";});
        //对象投递
        $task = new Task('data');
        TaskManager::async($task);
        //类投递
        TaskManager::async(QuickTask::class);
        //任务并发
        $tasks = [
            function () {sleep(3);return 'this is 1 task';},
            function () {sleep(1);return 'this is 2 task';},
            function () {sleep(2);return 'this is 3 task';},
        ];
        $ret = TaskManager::barrier($tasks, 3);
        var_dump($ret);
    }
}