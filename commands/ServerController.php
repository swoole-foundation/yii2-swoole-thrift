<?php
/**
 * @author xialeistudio
 * @date 2019-05-19
 */

namespace app\commands;


use app\components\console\Application;
use app\services\SumServiceImpl;
use app\thrift\SumService\SumServiceProcessor;
use swoole\foundation\thrift\factory\TFramedTransportFactory;
use swoole\foundation\thrift\server\SwooleServer;
use swoole\foundation\thrift\server\SwooleServerTransport;
use Swoole\Server;
use Thrift\Exception\TTransportException;
use Thrift\Factory\TBinaryProtocolFactory;
use Yii;
use yii\console\Controller;

/**
 * 服务器管理
 * Class ServerController
 * @package app\commands
 */
class ServerController extends Controller
{

    /**
     * 发送信号
     * @param int $signal
     */
    private function kill($signal)
    {
        $pid = file_get_contents(Yii::getAlias('@runtime/swoole.pid'));
        if (empty($pid)) {
            $this->stderr("swoole服务器未运行\n");
            return;
        }
        if (!posix_kill($pid, $signal)) {
            $this->stderr("swoole服务器未运行\n");
            return;
        }
    }

    /**
     * 启动Swoole服务器
     * @param string $host 监听主机
     * @param int $port 监听端口
     * @throws TTransportException
     */
    public function actionStart($host = 'localhost', $port = 9501)
    {
        $processor = new SumServiceProcessor(new SumServiceImpl());
        $serverTransport = new SwooleServerTransport($host, $port, SWOOLE_PROCESS, SWOOLE_SOCK_TCP, [
            'pid_file' => Yii::getAlias('@runtime/swoole.pid')
        ]);
        $transportFactory = new TFramedTransportFactory();
        $protocolFactory = new TBinaryProtocolFactory();
        $server = new SwooleServer(
            $processor,
            $serverTransport,
            $transportFactory,
            $transportFactory,
            $protocolFactory,
            $protocolFactory
        );
        $server->on('start', function (Server $server) {
            $this->stdout("listen on {$server->host}:{$server->port}\n");
        });
        // 工作进程启动，初始化yii2框架环境
        $server->on('workerStart', function (Server $server) {
            $config = require __DIR__ . '/../config/console.php';
            new Application($config);
            Yii::$app->set('server', $server);
        });
        $server->serve();
    }

    /**
     * 关闭服务器
     */
    public function actionShutdown()
    {
        $this->kill(SIGTERM);
        $this->stdout("swoole服务器已关闭\n");
    }

    /**
     * reload工作进程
     */
    public function actionReload()
    {
        $this->kill(SIGUSR1);
        $this->stdout("Swoole服务器工作进程reload完成\n");
    }
}