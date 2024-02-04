<?php

namespace purchases\models\searches;

use purchases\models\PurchaseNomenclature;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * PurchaseNomenclatureSearch represents the model behind the search form of `purchases\models\PurchaseNomenclature`.
 */
class PurchaseNomenclatureSearch extends PurchaseNomenclature
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'qty', 'created_by', 'updated_by'], 'integer'],
            [['description', 'units', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param int $purchaseId
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search(int $purchaseId, array $params): ActiveDataProvider
    {
        $query = PurchaseNomenclature::find()
            ->byPurchase($purchaseId);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_ASC,
                ],
            ],
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
            'qty' => $this->qty,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'units', $this->units]);

        return $dataProvider;
    }
}
