<?php
// +----------------------------------------------------------------------
// | 基础Client
// +----------------------------------------------------------------------
// | Author: chocannon
// +----------------------------------------------------------------------
namespace Coral\Client;

use Swoole;
use RuntimeException;
use Coral\Utility\Package;
use Coral\Traits\ClientCallBack;
use Coral\Interfs\ClientInterface;

abstract class BaseClient implements ClientInterface 
{
    use ClientCallBack;

    protected $client   = null;
    protected $host     = '0.0.0.0';
    protected $port     = 9501;
    protected $sockType = SWOOLE_SOCK_TCP;
    protected $config   = [];
    protected $timeout  = 0.5;
    protected $syncType = SWOOLE_SOCK_ASYNC;

    /**
     * 配置服务参数
     * @param array $config 参数数组
     */
    public function setConfig(array $config)
    {
        $this->config = array_merge([
            'open_eof_check'        => 1,
            'package_eof'           => "\r\n",
            'package_max_length'    => 1024 * 1024 * 2,
            'open_length_check'     => 1,
            'package_length_type'   => 'N',
            'package_length_offset' => 0,
            'package_body_offset'   => 4,
        ], $config);
    }


    /**
     * 初始化服务
     * @return [type] [description]
     */
    protected function initialization()
    {
        $this->client = new Client($this->sockType, $this->syncType);

        if ($this->client instanceof Client) {
            throw new RuntimeException("Error Init Swoole Client");
        }

        $this->client->set($this->config);

        if (SWOOLE_SOCK_ASYNC === $this->syncType) {
            $this->cli->on('Connect', [$this, 'onConnect']);
            $this->cli->on('Receive', [$this, 'onReceive']);
            $this->cli->on('Error',   [$this, 'onError']);
            $this->cli->on('Close',   [$this, 'onClose']);
        }

        return $this->client;
    }


    /**
     * 发送请求获取数据,获取结果
     * @param  string $data [description]
     * @return [type]       [description]
     */
    public function exec(string $data)
    {
        if ($this->client instanceof Client) {
            $this->initialization();
        }
        
        if (false === $this->client->connect($this->host, $this->port, $this->timeout)) {
            throw new RuntimeException("Connect Failed!", $this->client->errCode);
        }

        if (false === $this->client->send(Package::encode($data))) {
            throw new RuntimeException("Send Failed!", $this->client->errCode);
        }
        
        $result = $this->client->recv();
        $this->client->close();
        return Package::decode($result);
    }
}
