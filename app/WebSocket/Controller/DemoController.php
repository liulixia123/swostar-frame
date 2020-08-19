<?php
namespace App\WebSocket\Controller;
use Swoole\WebSocket\Server as SwooleServer;
class DemoController
{
	public function open(SwooleServer $server, $request)
	{
		dd('DemoController open ($server, $request)');
	}
	public function message(SwooleServer $server, $frame)
	{
		dd('DemoController message ($server, $frame)');
	}
	public function close(SwooleServer $server, $fd)
	{
		dd('DemoController close ($server, $fd)');
	}
}
?>