<?php
/**
 * @author xialeistudio
 * @date 2019-05-19
 */

namespace app\commands;

use app\thrift\SumService\SumServiceClient;
use swoole\foundation\thrift\client\Transport;
use Thrift\Exception\TTransportException;
use Thrift\Protocol\TBinaryProtocol;
use Thrift\Transport\TFramedTransport;
use yii\console\Controller;

/**
 * 测试客户端
 * Class ClientController
 * @package app\commands
 */
class ClientController extends Controller
{
    /**
     * 测试通信
     * @throws TTransportException
     */
    public function actionTest()
    {
        $transport = new Transport('localhost', 9501);
        $transport = new TFramedTransport($transport);
        $protocol = new TBinaryProtocol($transport);

        $client = new SumServiceClient($protocol);

        $transport->open();

        $this->stdout($client->sum(1, 1), PHP_EOL);
    }
}