<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\News */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'News', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,

        'attributes' => [
            [
                'format' => 'raw',
                'attribute'=>'video_url',
                'value' => function($data) { return '<video width="150" height="100" autoplay controls>
                              <source src="http://solapur24x7.com/video/' .$data->video_url .'" type="video/mp4">
                            </video>' ; },
            ],
            
            'title:ntext',
            'description:ntext',
	    'tags:ntext',
            array(
                'attribute' => 'published_at',
                'value'=>function($data) { return date("Y-m-d H:i:s", $data->published_at); },
            ),

        ],
    ]) ?>

</div>
