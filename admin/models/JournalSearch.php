<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
/**
 * JournalSearch represents the model behind the search form of `common\models\Journal`.
 */
class JournalSearch extends Journal
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'number', 'status'], 'integer'],
            [['title', 'external_code', 'file_marker', 'created_at', 'publish_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Journal::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
	    $dataProvider->sort->defaultOrder =  ['publish_at'=>SORT_DESC];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'number' => $this->number,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'publish_at' => $this->publish_at,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'external_code', $this->external_code])
            ->andFilterWhere(['like', 'file_marker', $this->file_marker]);

        return $dataProvider;
    }
}
