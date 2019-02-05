<?php

namespace api\modules\v1;

use \yii\base\Module;

/**
 * RestModule
 */
class RestModule extends Module
{

    /**
     * @var string current version
     */
    public $version = '1.0.0';

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'api\modules\v1\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }
}
