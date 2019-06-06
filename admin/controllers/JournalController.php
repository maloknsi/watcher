<?php

namespace backend\controllers;

use backend\models\Journal;
use backend\models\JournalSearch;
use backend\components\CController;
use common\models\User;
use common\models\Video;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * JournalController implements the CRUD actions for Journal model.
 */
class JournalController extends CController
{
	/**
	 * {@inheritdoc}
	 */
	public function behaviors()
	{
		return ArrayHelper::merge(
			[
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					[
						'actions' => ['edit-video', 'upload-video', 'delete-video'],
						'allow' => true,
						'roles' => ['admin'],
					],
				],
			],
		   ],parent::behaviors());
	}

	public function getNewModel($isModelSearch = false)
	{
		return $isModelSearch ? new JournalSearch() : new Journal();
	}
	/**
	 * Finds the User model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return Journal the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = Journal::findOne($id)) !== null) {
			return $model;
		}
		throw new NotFoundHttpException('Не найдена запись в базе данных');
	}
	/**
	 * return form for for edit model create.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @param int
	 * @return mixed
	 */
	public function actionEditVideo($id)
	{
		$journal = $id ? $this->findModel(intval($id)) : $this->getNewModel();
		$journalVideosArray = ['files'=>[],'names'=>[],];
		foreach ($journal->videos as $journalVideo){
			$fileVideo = $journal->external_code.'-'.$journalVideo->number.'.mp4';
			$journalVideosArray['files'][] = \Yii::$app->params['pathVideo'] . $fileVideo;
			$journalVideosArray['names'][] = [
				'caption'=>$journalVideo->number,'size'=>@filesize(\Yii::getAlias('@api')."/web/".$fileVideo),'type'=>'video', 'filetype' => 'video/mp4',
				'key' => $journalVideo->id, 'downloadUrl' => \Yii::$app->params['pathVideo']. $fileVideo, 'filename' => $fileVideo,
			];
		}
		return $this->renderAjax('form-video', [
			'model' => $journal,
			'journalVideosArray' => $journalVideosArray,
		]);
	}

	public function actionUploadVideo($id){
		$output = [];
		/** @var Journal $journal */
		$journal = Journal::find()->where(['id'=>$id])->one();
		if ($journal){
			$video = new Video();
			/** @var Video $lastVideo */
			$lastVideo = Video::find()->where(['journal_id'=>$id])->select(['number'])->orderBy(['number'=>SORT_DESC])->one();
			$video->number = isset($lastVideo->number) ? ($lastVideo->number + 1) : 0;
			$video->journal_id = $id;
			$fileVideo = $journal->external_code.'-'.$video->number.'.mp4';
			$video->file_video = $fileVideo;
			$video->save();
			// UPLOAD
			$uploadedFile = UploadedFile::getInstance($journal, 'journalVideos');
			if ($uploadedFile) {
				$uploadedFile->saveAs(\Yii::getAlias('@api')."/web/".$fileVideo);
				$output = [
					'href' => '/'.$fileVideo,
					'name' => $video->number,
					'error' => $video->hasErrors()? Html::errorSummary($video, ['header'=>'','footer'=>'','encode'=>false]) : null,
				];
			}
			Journal::updateAll(['status' => User::STATUS_DELETED],['id'=>$video->journal->id]);
		} else {
			$output['error'] = 'не найден журнал #'.$id;
		}
		return json_encode($output);
	}

	public function actionDeleteVideo(){
		$videoId = \Yii::$app->getRequest()->post('key');
		$output = [];
		/** @var Video $video */
		$video = Video::find()->where(['id'=>$videoId])->one();
		if ($video){
			$fileVideo = $video->journal->external_code.'-'.$video->number.'.mp4';
			@unlink(\Yii::getAlias('@api')."/web/".$fileVideo);
			$video->delete();
			Journal::updateAll(['status' => User::STATUS_DELETED],['id'=>$video->journal->id]);
		} else {
			$output['error'] = 'не найден файл видео #'. $videoId;
		}
		return json_encode($output);

	}
}
