<?php
return [
	"http" => [
		"host" => "0.0.0.0",
		"port" => 9000,
		"tcpable" =>1,
		"swoole" => [
			"task_worker_num" => 0,
		],
		"rpc" => [
			"host" => "127.0.0.1",
			"port" => "9501",
			"swoole_setting" => [
				"worker_num" => "2"
				]
		]
	],
	'ws'=>[
		'host' => '192.168.186.131', //服务监听ip
		'port' => 9800, //监听端口
		'tcpable'=>1, //是否开启tcp监听
		'enable_http' => true, //是否开启http服务
		'swoole' => [ //swoole配置
			"task_worker_num" => 0,
			// 'daemonize' => 0, //是否开启守护进程
		],
		"rpc" => [
			"host" => "127.0.0.1",
			"port" => "9502",
			"swoole_setting" => [
			"worker_num" => "2"
			]
		]
	],
	'route' => [
		'server' => [
			'host' => '192.168.186.131',
			'port' => 9600,
		],
		'jwt' => [
			'key' => 'swocloud',
			'alg' => 'HS256'
		]
	]
];
?>