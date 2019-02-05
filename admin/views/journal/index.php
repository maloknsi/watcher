<?php

use common\models\User;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\JournalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<div class="journal-index">
	<?php Pjax::begin(['id' => 'journal-grid', 'timeout' => 240000, 'enablePushState' => true]); ?>
	<?= Html::button('Добавить', [
		'class' => 'btn btn-success btn-show-modal-form',
		'title' => 'Добавить',
		'data-action-url' => Url::to('/journal/edit'),
	]); ?>
	<?= GridView::widget([
		'layout' => "{summary}\n{pager}\n{items}\n{pager}\n{summary}",
		'formatter' => ['class' => 'yii\i18n\Formatter', 'nullDisplay' => '-'],
		'options' => ['class' => ['table-report-detailed', 'grid-view']],
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
		'rowOptions' => function ($data) {
			return ($data->status != User::STATUS_ACTIVE || strtotime($data->publish_at) > time()) ? ['class' => 'danger'] : null;
		},
		'columns' => [
			[
				'attribute' => 'number',
				'contentOptions' => ['style' => 'text-align: center'],
				'headerOptions' => ['style' => 'width: 20px;']
			],
			'title',
			'external_code',
			[
				'class' => 'yii\grid\ActionColumn',
				'header' => 'Видео',
				'template' => '{edit-video}',
				'contentOptions' => ['style' => 'text-align: center'],
				'buttons' => [
					/** @var User $model */
					'edit-video' => function ($url, $model, $key) {
						return Html::a('<span class="glyphicon glyphicon-pencil"></span> ['.count($model->videos).']', '#', [
							'title' => 'Редактировать',
							'class' => 'btn-show-modal-form',
							'data-action-url' => Url::to(['/journal/edit-video', 'id' => $model->id]),
						]);
					},
				],
			],
			[
				'attribute' => 'created_at',
				'format' => ['date', 'php:Y-m-d H:i'],
				'filter' => false,
				'contentOptions' => ['style' => 'text-align: center'],
				'headerOptions' => ['style' => 'min-width: 155px;']
			],
			[
				'attribute' => 'publish_at',
				'format' => ['date', 'php:Y-m-d'],
				'filter' => false,
				'contentOptions' => ['style' => 'text-align: center'],
				'headerOptions' => ['style' => 'min-width: 150px;']
			],
			[
				'attribute' => 'status',
				'content' => function ($data) {
					return User::getStatusLabel($data->status);
				},
				'filter' => Html::activeDropDownList(
					$searchModel,
					'status',
					ArrayHelper::merge(array('' => ''), User::getStatuses()),
					['class' => 'form-control']
				),
				'headerOptions' => ['style' => 'min-width: 155px;']
			],
			[
				'class' => 'yii\grid\ActionColumn',
				'template' => '{update}',
				'contentOptions' => ['style' => 'text-align: center'],
				'buttons' => [
					/** @var User $model */
					'update' => function ($url, $model, $key) {
						return Html::a('<span class="glyphicon glyphicon-pencil"></span>', '#', [
							'title' => 'Редактировать',
							'class' => 'btn-show-modal-form',
							'data-action-url' => Url::to(['/journal/edit', 'id' => $model->id]),
						]);
					},
				],
			],
			[
				'class' => 'yii\grid\ActionColumn',
				'template' => '{delete}',
				'contentOptions' => ['style' => 'text-align: center'],
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
</div>
<?php Pjax::end()?>
