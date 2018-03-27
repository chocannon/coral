<?php
// +----------------------------------------------------------------------
// | TcpServer
// +----------------------------------------------------------------------
// | Author: chocannon
// +----------------------------------------------------------------------
namespace Coral\Server;

use RuntimeException;
use Coral\Traits;
use Coral\Utility\Process;
use Coral\Interfs\ServerInterface;

abstract class BaseServer implements ServerInterface 
{
    use Traits\Driver;
    use Traits\ServerCallBack;

    protected $swoole      = null;
    protected $processName = 'SwooleTcpServer';
    protected $host        = '0.0.0.0';
    protected $port        = 9501;
    protected $mode        = SWOOLE_PROCESS;
    protected $sockType    = SWOOLE_SOCK_TCP;
    protected $sockSsl     = SWOOLE_SSL;
    protected $config      = [];
    protected $runPath     = '/tmp';
    protected $serverType  = 'Http';
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
        return $this;
    }

    
    /**
     * 初始化服务
     * @return [type] [description]
     */
    protected function initialization()
    {
        // 实例化SWOOLE
        switch ($this->serverType) {
            case 'Https':
                $this->swoole = new \Swoole\Http\Server(
                    $this->host, $this->port, $this->mode, $this->sockType | $this->sockSsl);
                break;
            case 'Http':
                $this->swoole = new \Swoole\Http\Server(
                    $this->host, $this->port, $this->mode, $this->sockType);
                break;
            case 'Tcp':
                $this->swoole = new \Swoole\Server(
                    $this->host, $this->port, $this->mode, $this->sockType);
                break;
            case 'Webserver':
                $this->swoole = new \Swoole\Websocket\Server(
                    $this->host, $this->port, $this->mode, $this->sockType);
                break;
            default:
                break;
        }
        if (!$this->swoole instanceof \Swoole\Server) {
            throw new RuntimeException("Error Init Swoole Server");
        }

        // 绑定回调函数
        $this->swoole->set($this->config);
        $this->swoole->on('Start',        [$this, 'onStart']);
        $this->swoole->on('Shutdown',     [$this, 'onShutdown']);
        $this->swoole->on('WorkerStop',   [$this, 'onWorkerStop']);
        $this->swoole->on('ManagerStop',  [$this, 'onManagerStop']);
        $this->swoole->on('WorkerStart',  [$this, 'onWorkerStart']);
        $this->swoole->on('ManagerStart', [$this, 'onManagerStart']);

        switch ($this->serverType) {
            case 'Https':
                 $this->swoole->on('Request', [$this, 'onRequest']);
                break;
            case 'Http':
                $this->swoole->on('Request', [$this, 'onRequest']);
                break;
            case 'Tcp':
                $this->swoole->on("Close",   [$this, 'onClose']);
                $this->swoole->on("Receive", [$this, 'onReceive']);
                $this->swoole->on("Connect", [$this, 'onConnect']);
                break;
            case 'Webserver':
                $this->swoole->on('Open',    [$this, 'onOpen']);
                $this->swoole->on('Request', [$this, 'onRequest']);
                $this->swoole->on('Message', [$this, 'onMessage']);
                break;
            default:
                break;
        }
        
        // 绑定TASK回调
        if (isset($this->config['task_worker_num'])) {
            $this->swoole->on('Task',   [$this, 'onTask']);
            $this->swoole->on('Finish', [$this, 'onFinish']);
        }
        
        return $this->swoole;
    }
}