<?php

use common\models\User;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $data common\models\User */
?>
<div class="user-index">
	<?php Pjax::begin(['id' => 'user-grid', 'timeout' => 240000, 'enablePushState' => false]); ?>
	<?= Html::button('Добавить', [
		'class' => 'btn btn-success btn-show-modal-form',
		'title' => 'Добавить',
		'data-action-url' => Url::to('/user/edit'),
	]); ?>
	<?= GridView::widget([
		'layout' => "{summary}\n{pager}\n{items}\n{pager}\n{summary}",
		'formatter' => ['class' => 'yii\i18n\Formatter', 'nullDisplay' => '-'],
		'options' => ['class' => ['table-report-detailed','grid-view']],
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
		'columns' => [
			'username',
			'email',
			[
				'attribute' => 'role',
				'content' => function ($data) {	return User::getRoleLabel($data->role);},
				'filter' => User::getRoles(),
				'headerOptions'=>['style'=>'min-width: 125px;']
			],
			[
				'attribute' => 'status',
				'content' => function ($data) {	return User::getStatusLabel($data->status);},
				'filter' => Html::activeDropDownList(
					$searchModel,
					'status',
					ArrayHelper::merge(array('' => ''), User::getStatuses()),
					['class' => 'form-control']
				),
				'headerOptions'=>['style'=>'min-width: 155px;']
			],
			[
				'class' => 'yii\grid\ActionColumn',
				'template' => '{update}',
				'contentOptions'=>['style'=> 'text-align: center'],
				'buttons' => [
					/** @var User $model */
					'update' => function ($url, $model, $key) {
						return Html::a('<span class="glyphicon glyphicon-pencil"></span>', '#', [
							'title' => 'Редактировать',
							'class' => 'btn-show-modal-form',
							'data-action-url' => Url::to(['/user/edit', 'id' => $model->id]),
						]);
					},
				],
			],
			[
				'class' => 'yii\grid\ActionColumn',
				'template' => '{delete}',
				'contentOptions'=>['style'=> 'text-align: center'],
				'buttons' => [
					'delete' => function ($url, $model) {
						/** @var User $model */
						return Html::a('<span class="glyphicon glyphicon-trash button-action-delete"></span>', 'javascript:;', [
							'title' => 'Удалить этот элемент',
							'class' => 'btn-show-confirm-form',
							'data-action-url' => Url::to(['delete', 'id' => $model->id]),
						]);
					},
				],
			],

		],
	]); ?>
	<?php Pjax::end(); ?>
</div>
