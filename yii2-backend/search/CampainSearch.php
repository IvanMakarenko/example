<?php

namespace backend\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Campain;

/**
 * CampainSearch represents the model behind the search form of `common\models\Campain`.
 */
class CampainSearch extends Campain
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status', 'type', 'count', 'user_id'], 'integer'],
            [['title', 'source', 'time_from', 'time_to', 'datetime_from', 'datetime_to', 'created_at', 'finished_at'], 'safe'],
            [['budget', 'min_price', 'max_price'], 'number'],
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
        $query = Campain::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'type' => $this->type,
            'budget' => $this->budget,
            'min_price' => $this->min_price,
            'max_price' => $this->max_price,
            'count' => $this->count,
            'time_from' => $this->time_from,
            'time_to' => $this->time_to,
            'datetime_from' => $this->datetime_from,
            'datetime_to' => $this->datetime_to,
            'created_at' => $this->created_at,
            'finished_at' => $this->finished_at,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'source', $this->source]);

        return $dataProvider;
    }
}
