<?php

namespace api\modules\v1\controllers;

use api\components\Controller;
use backend\models\Journal;
use common\models\User;
use common\models\Video;
use Yii;


/**
 * PageController implements the CRUD actions for Page model.
 */
class VideoController extends Controller
{

	public function actionGetList(){
		/** @var Journal $journal */
		$journal = Journal::find()
			->where(['status'=>User::STATUS_ACTIVE])->andFilterCompare('publish_at',new \yii\db\Expression('NOW()'),'<')
			->orderBy(['publish_at'=>SORT_DESC])->limit(1)->one();
		$videoCount = Video::find()->where(['journal_id'=>$journal->id])->count();
		return [['journal-id'=>$journal->external_code, 'video-quantity'=>$videoCount]];
	}

	public function actionGetMarkers($id){
		$journal = Journal::find()
			->where(['status'=>User::STATUS_ACTIVE, 'external_code'=>intval($id)])
			->andFilterCompare('publish_at',new \yii\db\Expression('NOW()'),'<')
			->limit(1)->one();
		/** @var Journal $journal */
		if ($journal->id){
			echo file_get_contents(Yii::getAlias('@backend')."/files/markers/{$journal->external_code}.dat");
			die();
		} else {
			header("HTTP/1.0 404 Not Found");
			echo "Data not found";
			die();
		}
	}

}
