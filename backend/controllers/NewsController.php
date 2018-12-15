<?php

namespace backend\controllers;

use Yii;

class NewsController extends \yii\rest\ActiveController
{

    public $modelClass = 'backend\models\News';

   public function actions(){
        $actions = parent::actions();
        unset($actions['create']);
        unset($actions['update']);
        unset($actions['delete']);
        unset($actions['view']);
        unset($actions['index']);
        return $actions;
    }


    protected function verbs(){
        return [
            'create' => ['POST'],
            'update' => ['PUT', 'PATCH','POST'],
            'delete' => ['DELETE'],
            'view' => ['GET'],
            'index'=>['GET', 'POST'],
        ];
    }

   public function actionIndex($providerId){
	$request = Yii::$app->request;
	if ($request->isGet)  { 
            \Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;
            $query = new \yii\db\Query();
            $provider = new \yii\data\ActiveDataProvider([
                'query' => $query->from('news')->where('provider_id ='. $providerId),
                'pagination' => [
                'pageSize' => 11,
                ],
            ]);
            $data_rows = $provider->getModels();
            return array('data' => $data_rows);
	}
	else{
		return array('success' => False, 'method not allowed');
        }
    }

}
