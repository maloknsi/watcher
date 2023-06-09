<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 * @property int $role
*/
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

	const ROLE_GUEST = 0;
	const ROLE_USER = 1;
	const ROLE_MODERATOR = 2;
	const ROLE_ADMIN = 4;
	public $new_password;
	public $auth_key;

	/**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
	        [['username', 'email', 'created_at', 'updated_at',], 'required'],
	        [['status', 'role'], 'integer'],
	        [['username', 'password_hash', 'password_reset_token', 'email'], 'string', 'max' => 255],
	        [['new_password'], 'string', 'max' => 50],
	        [['username'], 'unique'],
	        [['email'], 'unique'],
	        [['password_reset_token'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'username' => 'Пользователь',
			'password_hash' => 'Password Hash',
			'password_reset_token' => 'Password Reset Token',
			'email' => 'Email',
			'status' => 'Статус',
			'created_at' => 'Created At',
			'updated_at' => 'Updated At',
			'role' => 'Доступ',
			'new_password' => 'Новый пароль',
		];
	}
	/**
	 * @return array user roles
	 */
	public static function getRoles()
	{
		return [
			self::ROLE_ADMIN => 'Админ',
			self::ROLE_USER => 'Пользователь',
			self::ROLE_MODERATOR => 'Менеджер',
		];
	}

	/**
	 * @param $roleId int
	 * @return string
	 */
	public static function getRoleLabel($roleId)
	{
		return self::getRoles()[$roleId];
	}

	/**
	 * @return array user roles
	 */
	public static function getStatuses()
	{
		return [
			self::STATUS_ACTIVE => 'Активный',
			self::STATUS_DELETED => 'Заблокирован',
		];
	}

	/**
	 * @param $statusId int
	 * @return string
	 */
	public static function getStatusLabel($statusId)
	{
		return self::getStatuses()[$statusId];
	}

	/**
	 * @param $roles
	 * @return bool
	 */
	public static function checkAccess($roles)
	{
		$result = false;
		$roleIdentity = User::ROLE_GUEST;
		if (!Yii::$app->user->isGuest) {
			$roleIdentity = Yii::$app->user->identity->role;
		}
		if (in_array($roleIdentity, $roles)) {
			$result = true;
		}
		return $result;
	}

	/**
	 * @param $runValidation bool
	 * @param $attributeNames bool
	 * @return bool|void
	 */
	public function save($runValidation = true, $attributeNames = null)
	{
			if ($this->new_password) {
				$this->setPassword($this->new_password);
			}
			return parent::save($runValidation, $attributeNames);
	}

}
