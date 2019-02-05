<?php

namespace api\components;

use yii\filters\AccessControl;
use yii\filters\ContentNegotiator;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;
use yii\web\Response;

/**
 * Class Controller
 * @package api\components
 */
class Controller extends \yii\rest\Controller
{
    /**
     * HTTP-заголовок для передачі терміну дії кешу
     */
    const EXPIRES_HEADER = 'X-Cache';

    /**
     * Конфігурація Serializer для перенесення інформації пропагінацію з HTTP заголовків в відповідь.
     *
     * @var string
     */
    public $serializer = 'api\components\Serializer';

    /**
     * @return array Набори "поведінки" контроллера
     */
    public function behaviors()
    {
        return [
            'contentNegotiator' => [
                'class' => ContentNegotiator::className(),
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ],
            'verbFilter' => [
                'class' => VerbFilter::className(),
                'actions' => $this->verbs(),
            ],
            'authenticator' => [
                'class' => ApiAuth::className(),
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => $this->rules(),
                'denyCallback' => [$this, 'createAuthError']
            ],
        ];
    }

    /**
     * @return array Список правил окремого контроллера.
     */
    public function rules()
    {
        return [
            [
                'allow' => true,
            ]
        ];
    }

    /**
     * @param array $headers
     *
     * @return $this
     */
    public function doRawResponse(array $headers = [])
    {
        \Yii::$app->response->format = Response::FORMAT_RAW;

        foreach ($headers as $name => $value)
            \Yii::$app->response->headers->add($name, $value);

        return $this;
    }

    /**
     * @throws ForbiddenHttpException Вивід помилки про заборонений доступ.
     */
    public function createAuthError()
    {
        throw new ForbiddenHttpException('Access denied');
    }

    /**
     * Викличеться перед запуском action
     * 
     * @param \yii\base\Action $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            /**
             * Додаємо заголовки для кешу, якщо передано відповідний HTTP-заголовок (static::EXPIRES_HEADER)
             */
            if ($append = (int)\Yii::$app->request->headers->get(static::EXPIRES_HEADER)) {
                \Yii::$app->response->headers->fromArray([
                    'Expires' => [
                        \Yii::$app->formatter->asDatetime(time() + $append, 'php:D, d M Y H:i:s T')
                    ],
                    'Cache-Control' => ['private, max-age=' . $append],
                ]);
            }
            return true;
        }

        return false;
    }
}
