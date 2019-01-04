<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\NewsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'News';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create News', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'thumbnail_url',
                'format' => 'html',
	        'value' => function($data) { return Html::img($data->thumbnail_url, ['width'=>'100', 'height'=>'50']); },
            ],
            'title:ntext',
            array(
                'attribute' => 'published_at',
                'value'=>function($data) { return date("Y-m-d H:i:s", $data->published_at); },
            ),



            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
