<?php

use common\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
?>
<?php $form = ActiveForm::begin([
	'id' => 'user-form',
	'enableAjaxValidation' => true,
	'enableClientValidation' => false,
	'action' => ['save', 'id' => $model->id],
	'validationUrl' => ['validate', 'id' => $model->id],
	'options' => ['enctype' => 'multipart/form-data', 'class' => 'modal-active-form'],
]); ?>
<div class="portlet-body">
	<div class="panel">
		<div class="panel  panel-default">
			<div class="panel-heading">Пользователь</div>
			<div class="panel-body">
				<div class="col-xs-6">
					<?= $form->field($model, 'username')->textInput() ?>
				</div>
				<div class="col-xs-6">
					<?= $form->field($model, 'email')->textInput() ?>
				</div>
				<div class="col-xs-6">
					<?= $form->field($model, 'new_password')->textInput() ?>
				</div>
			</div>
		</div>
		<div class="panel panel-danger">
			<div class="panel-heading">Управление</div>
			<div class="panel-body">
				<div class="col-xs-6">
					<?= $form->field($model, 'status')->dropDownList(User::getStatuses()) ?>
				</div>
				<div class="col-xs-6">
					<?= $form->field($model, 'role')->dropDownList(User::getRoles()) ?>
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
			<?= $form->field($model, 'id', [
				'options'=>['tag'=>false],
				'errorOptions' => [],
			])->hiddenInput()->label(''); ?>
			<?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['value' => Url::to(['edit']), 'title' => 'Подтвердить действие', 'class' => 'showModalButton btn btn-success']); ?>
			<?= Html::button('Закрыть', ['class' => 'btn grey-mint', 'data-dismiss' => "modal"]); ?>
		</div>
	</div>
</div>
<?php ActiveForm::end(); ?>