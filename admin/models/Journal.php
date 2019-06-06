<?php

namespace backend\models;
use common\models\User;
use common\models\Video;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

/**
 * JournalSearch represents the model behind the search form of `common\models\Journal`.
 */
class Journal extends \common\models\Journal
{
	public $fileMarker;
	public $journalVideos;
	public function rules()
	{
		return ArrayHelper::merge(
			[[['fileMarker'], 'file', 'skipOnEmpty' => true, 'extensions' => 'zip']],
			parent::rules()
		);
	}

	public function save($runValidation = true, $attributeNames = null)
	{
		if (!$this->external_code) {
			$this->external_code = strval(time());
		}
		if (!$this->number) {
			/** @var Journal $journal */
			$journal = self::find()->select(['number'])->orderBy(['number'=>SORT_DESC])->one();
			$this->number = $journal->number ? ($journal->number + 1) : 1;
		}
		$this->fileMarker = UploadedFile::getInstance($this, 'fileMarker');
		if (!$this->fileMarker){
			if (!$this->file_marker){
				$this->addError('fileMarker', "нет файл-маркера");
				return false;
			}
			if ($this->status == User::STATUS_ACTIVE && $this->getOldAttribute('status') == User::STATUS_DELETED){
				self::changeExternalCode($this->id);
			}
		} else {
			Journal::updateAll(['status' => User::STATUS_DELETED],['id'=>$this->id]);
			if ($this->fileMarker->type == 'application/x-zip-compressed'){
				$zip = new \ZipArchive();
				$zip->open($this->fileMarker->tempName);
				if ($zip->open($this->fileMarker->tempName) === true) {
					$isFindDatFile = false;
					for($i = 0; $i < $zip->numFiles; $i++) {
						$fileName = $zip->getNameIndex($i);
						$fileInfo = pathinfo($fileName);
						if ($fileInfo["extension"] == 'dat'){
							$isFindDatFile = true;
							//$zip->extractTo(\Yii::$app->basePath.'/web/', [$fileName]);
							if (@file_put_contents(\Yii::$app->basePath.'/files/markers/'.$this->external_code.'.dat', $zip->getFromIndex($i)) === false){
								$this->addError('fileMarker', "Ошибка сохранения файл-маркера");
								return false;
							} else {
								$this->file_marker = $this->external_code.'.dat';
							}
						}
					}
					$zip->close();
					if (!$isFindDatFile){
						$this->addError('fileMarker', "В архиве не найден файл-маркер");
						return false;
					}
				} else {
					$this->addError('fileMarker', "Ошибка в архиве файл-маркера");
					return false;
				}
			} else {
				$this->addError('fileMarker', "Неправильный файл-маркер".print_r($this->fileMarker,1));
				return false;
			}
		}
		return parent::save($runValidation, $attributeNames);

	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels()
	{
		return ArrayHelper::merge([
			'fileMarker' => 'файл-маркер',
			'journalVideos' => 'Видео журнала',
		],parent::attributeLabels());
	}

	public static function changeExternalCode($journalId)
	{
		/** @var Journal $journal */
		$journal = Journal::find()->where(['id'=>$journalId])->one();
		if ($journal){
			Journal::updateAll(['status' => User::STATUS_DELETED],['id'=>$journal->id]);
			$newExternalCode = strval(time());
			@rename(\Yii::$app->basePath.'/files/markers/'.$journal->external_code.'.dat', \Yii::$app->basePath.'/files/markers/'.$newExternalCode.'.dat');
			Journal::updateAll(['external_code' => $newExternalCode, 'file_marker' => $newExternalCode.'.dat'],['id'=>$journal->id]);
			$journalVideos = Video::find()->where(['journal_id'=>$journal->id])->orderBy(['number'=>SORT_ASC])->all();
			$videoNumber = 0;
			/** @var Video $journalVideo */
			foreach ($journalVideos as $journalVideo){
				$newVideoFileName = $newExternalCode.'-'.$videoNumber.'.mp4';
				Video::updateAll(['file_video'=>$newVideoFileName, 'number' => $videoNumber],['id'=>$journalVideo->id]);
				@rename(\Yii::getAlias('@api')."/web/".$journalVideo->file_video, \Yii::getAlias('@api')."/web/".$newVideoFileName);
				$videoNumber++;
			}
		}
	}
}
