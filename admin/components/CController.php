<?php

namespace backend\components;

use Yii;
use yii\bootstrap\ActiveForm;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class CController extends \yii\web\Controller {
	/**
	 * @var array
	 * хлебные крошки
	 */
	public $breadcrumbs = [];

	/**
	 * @var array
	 * меню сайта
	 */
	public $menuItems = [];

	/**
	 * @var string
	 * название сайта
	 */
	public $title = '';

	/** @var $ajaxResult AjaxResult */
	public $ajaxResult;
	public function init()
	{
		$this->title = \Yii::$app->name;
		if (Yii::$app->request->isAjax) {
			$this->ajaxResult = new AjaxResult();
		}
	}
	public function afterAction($action, $result)
	{
		if(Yii::$app->request->isAjax &&
			(!is_null($this->ajaxResult->data) || !is_null($this->ajaxResult->notify) || !is_null($this->ajaxResult->error))){
			return Json::encode($this->ajaxResult);
		}
		return parent::afterAction($action, $result);
	}
	/**
	 * {@inheritdoc}
	 */
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					[
						'actions' => ['index' ,'edit','validate','save','delete'],
						'allow' => true,
						'roles' => ['admin'],
					],
				],
			],
			[
				'class' => 'yii\filters\AjaxFilter',
				'only' => ['edit','validate','save','delete'],
				'errorMessage' => 'Ошибка типа запорса (AJAX ONLY!)',
			],
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'delete' => ['post'],
				],
			],
		];
	}

	/**
	 * @param bool $isModelSearch
	 * @return ActiveDataProvider | ActiveRecord
	 */
	public function getNewModel($isModelSearch = false)
	{
		return null;
	}

	/**
	 * Finds the model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return ActiveRecord the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		return null;
	}
	/**
	 * Lists all models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = $this->getNewModel(true);
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}
	/**
	 * return form for for edit model create.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @param int
	 * @return mixed
	 */
	public function actionEdit($id = null)
	{
		$model = $id ? $this->findModel(intval($id)) : $this->getNewModel();
		return $this->renderAjax('form', [
			'model' => $model
		]);
	}
	/**
	 * Ajax Validate model.
	 * @param $id
	 * @return mixed
	 */
	public function actionValidate($id = null)
	{
		Yii::$app->response->format = Response::FORMAT_JSON;
		$model = $id ? $this->findModel(intval($id)) : $this->getNewModel();
		if (Yii::$app->getRequest()->isPost && $model->load(Yii::$app->getRequest()->post())) {
			return ActiveForm::validate($model);
		}
	}

	/**
	 * Save model.
	 * @param $id
	 * @return mixed
	 */
	public function actionSave($id = null)
	{
		$model = $id ? $this->findModel(intval($id)) : $this->getNewModel();
		if ($model->load(Yii::$app->getRequest()->post())) {
			if ($model->validate() && $model->save()){
				$this->ajaxResult->data = "Изменения успешно сохранены #" . $model->id;
			} else $this->ajaxResult->error = 'Ошибка [validate-save] #'. $model->id;
		} else {
			$this->ajaxResult->error = 'Ошибка [load] #'. $model->id;
		}
		$this->ajaxResult->error .= $model->hasErrors() ? Html::errorSummary($model) : '';
	}
	/**
	 * Deletes an existing model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionDelete($id)
	{
		//Yii::$app->response->format = Response::FORMAT_JSON;
		$model = $this->findModel($id);
		$model->delete();
		$this->ajaxResult->data = "Удалено #" . $id;
	}
}

