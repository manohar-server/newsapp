<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "news".
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $published_at
 * @property int $published_by
 */
class News extends \yii\db\ActiveRecord
{

    public $thumbnail, $video;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'news';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'video'], 'required'],
	    ['title', 'string', 'length' => [4, 150]],
            [['description', 'tags', 'video_url', 'thumbnail_url'], 'string'],
            [['published_at'], 'safe'],
            [['published_by'], 'integer'],
	    [['video'], 'file'],
            [['thumbnail'], 'file'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description'),
            'published_at' => Yii::t('app', 'Published At'),
            'published_by' => Yii::t('app', 'Published By'),
            'video' => Yii::t('app', 'Video'),
            'thumbnail' => Yii::t('app', 'Thumbnail'),
            'video_url' => Yii::t('app', 'Video URL' ),
            'thumbnail_url' => Yii::t('app', 'Thumbnail'),
	     'tags' => Yii::t('app', 'Tags'),
        ];
    }
}

