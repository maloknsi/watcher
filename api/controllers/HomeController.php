<?php
namespace api\controllers;

use yii;
use api\components\Controller;

/**
 * Home controller
 */
class HomeController extends Controller
{

    /**
     * @inheritdoc
     * @return array Список правил доступу для контроллера
     */
    public function rules()
    {
        return [
            ['allow' => true]
        ];
    }

    
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        unset($behaviors['authenticator']);
        return $behaviors;
    }

    /**
     * Displays homepage. Available versions
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return [
            'versions' => [
                Yii::$app->getModule('v1')->version => Yii::$app->urlManager->createAbsoluteUrl('/v1')
            ]
        ];
    }
}
