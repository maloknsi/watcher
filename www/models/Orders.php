<?php

namespace api\models;

class Orders extends \common\models\Order
{

    public function rules()
    {
        return [
            [['user_id',  'pay_type'], 'required'],
            [['user_id',  'service_id',
	            'box', 'code', 'degree_wear', 'status', 'pay_type', 'pay_status', 'price'], 'integer'],
            [['defects', 'warning'], 'string'],
            [['date_receipt', 'date_issue', 'created', 'confirmed', 'shipment'], 'safe'],
        ];
    }


    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
