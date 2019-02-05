<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model backend\models\Journal */
/* @var $journalVideosArray mixed */
/* @var $form yii\widgets\ActiveForm */
?>
<?php $form = ActiveForm::begin([
	'id' => 'journal-form-video',
	'enableAjaxValidation' => false,
	'enableClientValidation' => false,
	'action' => ['upload-video', 'id' => $model->id],
	'options' => ['enctype' => 'multipart/form-data', 'class' => 'modal-active-form'],
]); ?>
	<div class="portlet-body">
		<div class="panel">
			<div class="panel  panel-default">
				<div class="panel-heading">Видео</div>
				<div class="panel-body">
					<div class="col-xs-12">
						<?= $form->field($model, 'journalVideos')->widget(FileInput::classname(),[
							'name' => 'journal_videos[]',
							'options'=>[
								'multiple'=>true,
								'accept'=>'video/mp4'
							],
							'pluginOptions' => [
								'allowedFileExtensions'=>['mp4'],
								'initialPreview'=>$journalVideosArray['files'],
								'initialPreviewAsData'=>true,
								'initialPreviewFileType'=>'video',
								'initialCaption'=>"Загрузите видео",
								'initialPreviewConfig' => $journalVideosArray['names'],
								'overwriteInitial'=>false,
								'uploadUrl' => Url::to(['journal/upload-video', 'id' => $model->id]),
								'dropZoneEnabled' => false,
								//'theme' => 'explorer-fa',
								'maxFileSize'=>30*1024*1024,
								'deleteUrl' => Url::to(['journal/delete-video']),
								'uploadAsync' => true,
							],
							'pluginEvents' => [
								"fileuploaded" => "function(event, files, extra) { console.log($(this));console.log($(files)); }",
							]
						]);?>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 col-md-12 col-lg-12 ">
				<?= $form->errorSummary($model); ?>
			</div>
		</div>
		<div class="modal-footer">
			<div class="form-group">
				<?= Html::button('Закрыть', ['class' => 'btn grey-mint', 'data-dismiss' => "modal"]); ?>
			</div>
		</div>
	</div>
<?php ActiveForm::end(); ?>