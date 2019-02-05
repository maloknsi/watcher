<?php

namespace backend\controllers;

use backend\components\CController;
use backend\models\UserSearch;
use common\models\User;
use yii\web\NotFoundHttpException;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends CController
{
	public function getNewModel($isModelSearch = false)
	{
		return $isModelSearch ? new UserSearch() : new User();
	}
	/**
	 * Finds the User model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return User the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = User::findOne($id)) !== null) {
			return $model;
		}
		throw new NotFoundHttpException('Не найдена запись в базе данных');
	}
}
