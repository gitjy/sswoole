<?php
/**
 * Openssl 加解密
 */
namespace App\HttpController;

use EasySwoole\EasySwoole\Config;
use EasySwoole\Http\AbstractInterface\Controller;

use EasySwoole\EasySwoole\Swoole\Task\TaskManager;  //任务管理器
use App\Task\Task; //任务模板
use App\Task\QuickTask; //任务模板
use EasySwoole\Component\AtomicManager; //原子计数器

use EasySwoole\EasySwoole\EasySwooleEvent;
use EasySwoole\Component\DI;        //依赖注入容器
use \EasySwoole\Component\Crypto\AES;
use \EasySwoole\Component\Container;
use EasySwoole\Component\Context\ContextManager;

class Component extends Ctrl
{

    //加密数据
    function  encrypt()
    {
        //实际调用的是 openssl_encrypt
        echo "加密数据";
        $openssl = new \EasySwoole\Component\Crypto\AES('key','DES-EDE3');
        $msg = $openssl->encrypt('仙士可');
        var_dump($msg);
        $msg = $openssl->decrypt($msg);
        var_dump($msg);
    }

    /**
     * 依赖注入
     * 在服务启动后，对IOC容器的获取/注入仅限当前进程有效。
     */
    function  di()
    {
        //实际调用的是 openssl_encrypt
        echo "依赖注入\n";
        $di = Di::getInstance();
        $di->set('encrypt', AES::class, 'key', 'DES-EDE3');
        $openssl = $di->get('encrypt');
        $msg = $openssl->encrypt('仙士可');
        var_dump($msg);
        $msg = $openssl->decrypt($msg);
        var_dump($msg);
        var_dump(AES::class);
    }

    /**
     * container容器
     * container容器可存储事件，变量
     */
    function container()
    {
        $container = new Container();
        $container->set('name', 'limi');
        $container->set('onOpen', function () {   echo "onOpen事件回调";});
        //var_dump($container->all());
        echo $container->get('name');
        echo "\n";
        call_user_func($container->get('onOpen'));
        //$container->delete('name');
    }

    /**
     * Context 上下文管理器
     * 上下文管理器来实现协程上下文的隔离
     * swoole是协程并发，因此不能使用静态和全局变量
     * 局部变量保存在协程栈里
     */
    function context()
    {
        $context = ContextManager::getInstance();
        $context->set('key', 'in the key');
        var_dump($context->get('key'));
    }

    /**
     * Atomic 原子计数器
     * swoole_atomic是swoole扩展提供的原子计数操作类
     */
    function atomic()
    {
        AtomicManager::getInstance()->add('second', 0);
        $atomic = AtomicManager::getInstance()->get('second');
        $atomic->add(1);
        $this->response()->write($atomic->get());
    }


}