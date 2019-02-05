<?php
$params = array_merge(
	require(__DIR__ . '/../../common/config/params.php'),
	require(__DIR__ . '/../../common/config/params-local.php'),
	require(__DIR__ . '/params.php'),
	require(__DIR__ . '/params-local.php')
);

return [
	'id' => 'moyo-api',
	'basePath' => dirname(__DIR__),
	'bootstrap' => ['log'],
	'controllerNamespace' => 'api\controllers',
	'defaultRoute' => 'home',
	'modules' => [
		'v1' => [
			'class' => 'api\modules\v1\RestModule',
			'defaultRoute' => 'index'
		],
	],

	'components' => [
		'user' => [
			'identityClass' => 'api\models\User',
			'enableSession' => false,
			'loginUrl' => null,
		],
		'response' => [
			'format' => yii\web\Response::FORMAT_JSON,
			'charset' => 'UTF-8',
		],
		'request' => [
			'class' => '\yii\web\Request',
			'enableCookieValidation' => false,
			'enableCsrfValidation' => false,
			'parsers' => [
				'application/json' => 'yii\web\JsonParser',
			],
		],
		'log' => [
			'traceLevel' => YII_DEBUG ? 3 : 0,
			'targets' => [
				[
					'class' => 'yii\log\FileTarget',
					'levels' => ['error', 'warning'],
				],
				[
					'class' => 'yii\log\FileTarget',
					'levels' => ['info'],
					'categories' => ['partner'],
					'logFile' => '@runtime/logs/partner-callback.log',
				],
			],
		],
		'errorHandler' => [
			'errorAction' => 'home/error',
		],
		'urlManager' => [
			'enablePrettyUrl' => true,
			'showScriptName' => false,
			'enableStrictParsing' => false,
			'rules' => [
				"/video/get-list/" => 'v1/video/get-list',
				[
					'pattern' => '/video/get-list',
					'route' => 'v1/video/get-list',
					'suffix' => '/',
				],
				"/video/get-markers/" => 'v1/video/get-markers',
				"/video/get-markers/<id:\d+>" => 'v1/video/get-markers',
				[
					'pattern' => '/video/get-markers',
					'route' => 'v1/video/get-markers',
					'suffix' => '/',
				],
				'<module:\w+>/<controller:\w+>/<id:\d+>' => '<module>/<controller>/index',
				'<module:\w+>/<controller:\w+>/<action:\w+>/<id:\d+>' => '<module>/<controller>/<action>',
				'<module:\w+>/<controller:\w+>/<action:\w+>/<command:\w+>'	=> '<module>/<controller>/<action>_<command>',
				'<module:\w+>/<controller:\w+>/<action:\w+>/<command:\w+>/<id:\d+>'	=> '<module>/<controller>/<action>_<command>',
				'<module:\w+>/<controller:\w+>/<action:\w+>/<id:\d+>'	=> '<module>/<controller>/<action>_view',
			],
		],
		'formatter' => [
			'class' => 'yii\i18n\Formatter',
			'dateFormat' => 'php:Y-m-d',
			'datetimeFormat' => 'php:Y-m-d H:i:s',
			'timeFormat' => 'php:H:i:s',
		],
		'path' => [
			'class' => 'api\components\Path',
			'real' => '',
			'web' => 'http://',
		],
		'fileCache' => [
			'class' => 'yii\caching\FileCache'
		],
	],
	'params' => $params,
];
