<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\News;

/**
 * NewsSearch represents the model behind the search form of `frontend\models\News`.
 */
class NewsSearch extends News
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'provider_id', 'published_by'], 'integer'],
            [['title', 'description', 'video_url', 'thumbnail_url', 'published_at'], 'safe'],
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
        //$query = News::find()->where('provider_id='.Yii::$app->user->identity->id);
	$query = News::find();
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
	    'sort' => [
        	'defaultOrder' => [
	        	'published_at' => SORT_DESC,
	            	'id' => SORT_DESC, 
        	]
    	    ],
	    //'sort'=> ['defaultOrder' => ['id' => SORT_DSC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'provider_id' => Yii::$app->user->identity->id,
            //'published_at' => $this->published_at,
            //'published_by' => $this->published_by,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'video_url', $this->video_url])
            ->andFilterWhere(['like', 'thumbnail_url', $this->thumbnail_url]);

        return $dataProvider;
    }
}
