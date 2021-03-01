<?php

namespace app\models;

use app\models\Apple;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * AppleSearch represents the model behind the search form of `app\models\Apple`.
 */
class AppleSearch extends Apple
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'color', 'status', 'eaten'], 'integer'],
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
        Apple::updateStatusApplies();

        $query = Apple::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'color' => $this->color,
            'status' => $this->status,
            'eaten' => $this->eaten,
        ]);

        return $dataProvider;
    }
}
