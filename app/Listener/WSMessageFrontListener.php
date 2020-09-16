<?php
namespace App\Listener;
use SwoStar\Event\Listener;

class WSMessageFrontListener extends Listener
{
// ..
/**
* 接收Route服务器的广播信息
*
* 
*/
	protected function routeBroadcast(SwoStarServer $swoStarServer, SwooleServer $swooleServer, $data, $fd)
	{
		$dataAck = [
		'method' => 'ack',
		'msg_id' => $data['msg_id'],
		];
		$swooleServer->push($fd, \json_encode($dataAck));
		// 接收之后可能会有其他的业务
		$swoStarServer->sendAll(\json_encode($data['data']));
	}
}
?>