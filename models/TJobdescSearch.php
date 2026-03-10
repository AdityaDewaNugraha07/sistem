<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TJobdesc;

/**
 * TJobdescSearch represents the model behind the search form about `app\models\TJobdesc`.
 */
class TJobdescSearch extends TJobdesc
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['jobdesc_id', 'pegawai_id', 'file_id', 'created_by'], 'integer'],
            [['created_at', 'created_by', 'updated_at', 'updated_by'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
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
        $query = TJobdesc::find();
        $query->joinWith(['pegawai', 'file']);

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
            'jobdesc_id' => $this->jobdesc_id,
            'pegawai_id' => $this->pegawai_id,
            'file_id' => $this->file_id,
        ]);

        return $dataProvider;
    }
}
