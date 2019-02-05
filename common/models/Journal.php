<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "journal".
 *
 * @property int $id
 * @property int $number
 * @property string $title
 * @property string $external_code
 * @property int $status
 * @property string $file_marker
 * @property string $created_at
 * @property string $publish_at
 *
 * @property Video[] $videos
 */
class Journal extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'journal';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['number', 'status'], 'integer'],
            [['status'], 'default', 'value'=>User::STATUS_DELETED],
            [['title', 'publish_at'], 'required'],
            [['created_at'], 'safe'],
            [['title', 'external_code', 'file_marker'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'number' => 'Номер',
            'title' => 'Название',
            'external_code' => 'Внешний код',
            'status' => 'Статус',
            'file_marker' => 'файл-маркер',
            'created_at' => 'Создан',
            'publish_at' => 'Опубликован',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVideos()
    {
        return $this->hasMany(Video::className(), ['journal_id' => 'id']);
    }
}
