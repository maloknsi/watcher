<?php
Yii::setAlias('@common', dirname(__DIR__));
Yii::setAlias('@frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('@backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('@console', dirname(dirname(__DIR__)) . '/console');
Yii::setAlias('api', dirname(dirname(__DIR__)) . '/api');

Yii::setAlias('apiWeb', dirname(dirname(__DIR__)).DIRECTORY_SEPARATOR.'api'.DIRECTORY_SEPARATOR.'web'.DIRECTORY_SEPARATOR);
Yii::setAlias('apiUrl', 'http://admin.watcher.ua/');
