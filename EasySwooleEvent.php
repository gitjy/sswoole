<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2018/5/28
 * Time: 下午6:33
 */

namespace EasySwoole\EasySwoole;


use EasySwoole\EasySwoole\Swoole\EventRegister;
use EasySwoole\EasySwoole\AbstractInterface\Event;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;

use EasySwoole\Utility\File;
use App\Process\HotReload;


class EasySwooleEvent implements Event
{

    public static function initialize()
    {
        // TODO: Implement initialize() method.
        //date_default_timezone_set('Asia/Shanghai');
        ini_set('display_errors', isset($_GET['_debug'])); // 线上环境关闭错误
        mb_internal_encoding("UTF-8");
        date_default_timezone_set('PRC');
        self::loadConf(EASYSWOOLE_ROOT.'/App/Config');

    }

    public static function mainServerCreate(EventRegister $register)
    {
        // TODO: Implement mainServerCreate() method.
        $swooleServer = ServerManager::getInstance()->getSwooleServer();
        $swooleServer->addProcess((new HotReload('HotReload', ['disableInotify' => false]))->getProcess());
        //为主服务配置onWorkStart事件
        $register->add($register::onWorkerStart,function (\swoole_server $server,int $workerId){
            echo $workerId .' start' . "\n";
        });
    }

    public static function onRequest(Request $request, Response $response): bool
    {
        // TODO: Implement onRequest() method.
        return true;
    }

    public static function afterRequest(Request $request, Response $response): void
    {
        // TODO: Implement afterAction() method.
    }


    /**
     * 加载配置文件
     */
    public static function loadConf($dir){
        $files = File::scanDirectory($dir);
        if(is_array($files)){
            foreach ($files['files'] as $file) {
                Config::getInstance()->loadFile($file);
            }
        }
    }

}