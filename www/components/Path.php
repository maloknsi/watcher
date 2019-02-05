<?php

namespace api\components;

use yii\base\Component;

class Path extends Component
{

    /**
     * @var string коренева директорія для збереження статичних файлів
     */
    public $real;

    /**
     * @var string базовий URL "статики"
     */
    public $web;

    /**
     * Фізиний шлях для збереження статичних файлів
     *
     * @param $directory
     * @return string
     */
    public function path($directory)
    {
        return \Yii::getAlias($this->real).$directory;
    }

    /**
     * Генерація URL для статиних файлів
     *
     * @param $directory
     * @return string
     */
    public function url($directory)
    {
        return $this->web . $directory;
    }
}
