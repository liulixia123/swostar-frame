<?php
namespace App\Listener;
use SwoStar\Event\Listener;
use SwoStar\Server\WebSocket\WebSocketServer;
class HandsHakeListener extends Listener
{
	protected $name = 'ws.hand';
	public function handler(WebSocketServer $server = null, $request = null, $response = null)
	{
		// 权限校验
		// 没有携带token直接结束
		$token = $request->header['sec-websocket-protocol'];
		if (empty($token) || (!$this->check($server, $token, $request->fd))) {
		$response->end();
		return false;
		}
		// websocket算法
		$this->handShake($request, $response);
	}
	/**
	* 对连接的客户端进行权限校验，如果说通过就会存入redis中
	* 
	* @param WebSocketServer $server
	* @param string $token jwt的token
	* @param int $fd 连接的客户端
	* @return boolean
	*/
	protected function check(WebSocketServer $server, $token, $fd)
	{
		try {
			$config = $this->app->make('config');
			$key = $config->get('server.route.jwt.key');
			// 对jwt的token进行解析，返回jwt对象
			$jwt = JWT::decode($token, $key, [$config->get('server.route.jwt.alg')]);
			// 从jwt中获取信息
			$userInfo = $jwt->data;
			// 然后绑定路由的关系
			$url = $userInfo->service_url;
			$server->getRedis()->hset($key, $userInfo->uid, \json_encode([
				'fd' => $fd,
				'name' => $userInfo->name
			]));
			return true;
		} catch (\Exception $e) {
			return false;
		}
	}
	/**
	* websocket的加密算法
	* 
	*/
	protected function handShake($request, $response)
	{
		// websocket握手连接算法验证
		$secWebSocketKey = $request->header['sec-websocket-key'];
		$patten = '#^[+/0-9A-Za-z]{21}[AQgw]==$#';
		if (0 === preg_match($patten, $secWebSocketKey) || 16 !== strlen(base64_decode($secWebSocketKey))) {
			$response->end();
			return false;
		}
		echo $request->header['sec-websocket-key'];
		$key = base64_encode(sha1(
		$request->header['sec-websocket-key'] . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11',
		true
		));
		$headers = [
			'Upgrade' => 'websocket',
			'Connection' => 'Upgrade',
			'Sec-WebSocket-Accept' => $key,
			'Sec-WebSocket-Version' => '13',
		];
		if (isset($request->header['sec-websocket-protocol'])) {
			$headers['Sec-WebSocket-Protocol'] = $request->header['sec-websocket-protocol'];
		}
		foreach ($headers as $key => $val) {
			$response->header($key, $val);
		}
		$response->status(101);
		$response->end();
	}
}
?>