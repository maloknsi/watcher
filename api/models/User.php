<?php

namespace api\models;

use yii\web\IdentityInterface;

/**
 * Class Users
 * @package api\models
 *
 * @property null $authKey
 * @property string $basketId
 */
class User extends \common\models\User implements IdentityInterface
{

	/**
	 * @var string Токен потоного користувача
	 */
	public $token;

	/**
	 * Знаходить користувача по id.
	 *
	 * @param int|string $id
	 * @return array|\common\models\User|null
	 */
	public static function findIdentity($id)
	{
		return User::find()
			->active()
			->where(['id' => $id])
			->one();
	}

	/**
	 * Finds user by phone
	 *
	 * @param string $phone
	 * @return static|null
	 */
	public static function findByPhone($phone)
	{
		return static::findOne([
			'phone' => $phone,
			'status' => self::STATUS_ACTIVE
		]);
	}

	/**
	 * Знаходить користувача по токену.
	 *
	 * @param mixed $token Token
	 * @param null $type Search engine type
	 * @return User|null
	 */
	public static function findIdentityByAccessToken($token, $type = null)
	{
		return static::findOne([
			'user_auth_token' => $token,
			'status' => self::STATUS_ACTIVE
		]);
	}

	/**
	 * Getter для властивості $id.
	 *
	 * @return int Current user's id
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Getter для властивості $authKey.
	 *
	 * @return null
	 */
	public function getAuthKey()
	{
		return null;
	}

	/**
	 * Валідація ключа
	 *
	 * @param string $authKey
	 * @return null
	 */
	public function validateAuthKey($authKey)
	{
		return null;
	}

	protected function getString($count = 6)
	{ //Генерация случайных цифр
		$result = '';
		$array = range('0', '9');
		for ($i = 0; $i < $count; $i++) {
			$result .= $array[mt_rand(0, 9)]; //с англ буквами 35
		}
		return $result;
	}

}
