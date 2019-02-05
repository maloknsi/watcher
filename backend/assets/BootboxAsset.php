<?php
namespace backend\assets;

use Yii;
use yii\web\AssetBundle;

class BootboxAsset extends AssetBundle
{
	#http://bootboxjs.com/
	public $sourcePath = '@vendor/bower-asset/bootbox';
	public $js = [
		'bootbox.js',
	];

	public static function overrideSystemConfirm()
	{
		Yii::$app->view->registerJs('
         yii.confirm = function(message, ok, cancel) {
          bootbox.dialog({
            message: message,
            title: "Подтвердите",
            buttons: {
              success: {
                label: "Да",
                className: "btn-danger",
                callback: function() {
                  ok();
                }
              },
              danger: {
                label: "Нет",
                className: "btn-success",
                callback: function() {
                  //cancel();
                }
              },
            }
          });
        }
       ');
	}
}