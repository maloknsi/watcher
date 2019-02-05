<?php
return [
	'name' => 'Watcher',
	'language' => 'ru-RU',
	'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
	    'authManager' => [
		    'class' => 'yii\rbac\PhpManager',
		    'itemFile' => '@common/components/rbac/items.php',
		    'assignmentFile' => '@common/components/rbac/assignments.php',
		    'ruleFile' => '@common/components/rbac/rules.php',
		    'defaultRoles' => ['admin', 'moderator', 'user'],
	    ],
    ],
];
