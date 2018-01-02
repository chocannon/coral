<?php
// +----------------------------------------------------------------------
// | 基础Server
// +----------------------------------------------------------------------
// | Author: chocannon
// +----------------------------------------------------------------------
namespace Coral\Server;

use Coral\Traits;
use Coral\Protocol\ServerInterface;

abstract class BaseServer implements ServerInterface {
    use Traits\Driver;
    use Traits\ServerCallBack;

    protected $swoole      = null;
    protected $processName = 'SwooleServer';
    protected $host        = '0.0.0.0';
    protected $port        = 9501;
    protected $mode        = SWOOLE_PROCESS;
    protected $sockType    = SWOOLE_SOCK_TCP;
    protected $config      = [];
    protected $runPath     = '/tmp';
    protected $masterPidFile;
    protected $managePidFile;


    public function __construct()
    {
        $this->masterPidFile = $this->runPath . '/' . $this->processName . '.master.pid';
        $this->managePidFile = $this->runPath . '/' . $this->processName . '.manager.pid';
    }


    /**
     * 配置服务参数
     * @param array $config 参数数组
     */
    public function setConfig(array $config)
    {
        $this->config = array_merge([
            'open_length_check'     => 1,
            'package_length_type'   => 'N',
            'package_length_offset' => 0,
            'package_body_offset'   => 4,
            'package_eof'           => "\r\n",
            'open_eof_check'        => 1,
            'package_max_length'    => 1024 * 1024 * 2,
            'worker_num'            => 8,
            'backlog'               => 128,
            'daemonize'             => 0,
            'log_file'              => '/var/log/swoole.log',
        ], $config);
    }

    
    /**
     * 初始化服务
     * @return [type] [description]
     */
    protected function initialization()
    {
        // 实例化SWOOLE
        $this->swoole = new \Swoole\Server($this->host, $this->port, $this->mode, $this->sockType);
        if (null === $this->swoole) {
            throw new \Exception("Error Init Swoole Server", 1);
        }

        // 绑定回调函数
        $this->swoole->set($this->config);
        $this->swoole->on('Start',        [$this, 'onStart']);
        $this->swoole->on('Shutdown',     [$this, 'onShutdown']);
        $this->swoole->on('ManagerStart', [$this, 'onManagerStart']);
        $this->swoole->on('ManagerStop',  [$this, 'onManagerStop']);
        $this->swoole->on('WorkerStart',  [$this, 'onWorkerStart']);
        $this->swoole->on('WorkerStop',   [$this, 'onWorkerStop']);
        $this->swoole->on('Connect',      [$this, 'onConnect']);
        $this->swoole->on('Receive',      [$this, 'onReceive']);
        $this->swoole->on('Close',        [$this, 'onClose']);

        // 绑定TASK回调
        if (isset($this->config['task_worker_num'])) {
            $this->swoole->on('Task',     [$this, 'onTask']);
            $this->swoole->on('Finish',   [$this, 'onFinish']);
        }
        
        return $this->swoole;
    }
}