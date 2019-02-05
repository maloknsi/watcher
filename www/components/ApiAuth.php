<?php

namespace api\components;

use yii\filters\auth\AuthMethod;

/**
 * Class ApiAuth
 * @package api\components
 *
 * @property string $header
 */
class ApiAuth extends AuthMethod
{

    /**
     * Getter для властивості $header.
     *
     * @return string
     */
    public function getHeader()
    {
        return \Yii::$app->params['api']['header'];
    }

    /**
     * Аутентифікація.
     *
     * @param \yii\web\User $user
     * @param \yii\web\Request $request
     * @param \yii\web\Response $response
     * @return boolean|\yii\web\IdentityInterface
     */
    public function authenticate($user, $request, $response)
    {
        if($header = $request->getHeaders()->get($this->getHeader(), '')
            and $identity = $user->loginByAccessToken($header)) {
                $identity->token = $header;
                return $identity;
        }

        return false;
    }
}
