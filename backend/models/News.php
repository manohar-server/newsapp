<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "news".
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $video_url
 * @property string $thumbnail_url
 * @property int $provider_id
 * @property string $published_at
 * @property int $published_by
 *
 * @property NewsProviders $provider
 */
class News extends \yii\db\ActiveRecord
{
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
            [['title', 'provider_id'], 'required'],
            [['title', 'description'], 'string'],
            [['provider_id', 'published_by'], 'integer'],
            [['published_at'], 'safe'],
            [['video_url', 'thumbnail_url'], 'string', 'max' => 255],
            [['provider_id'], 'exist', 'skipOnError' => true, 'targetClass' => NewsProviders::className(), 'targetAttribute' => ['provider_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'description' => 'Description',
            'video_url' => 'Video Url',
            'thumbnail_url' => 'Thumbnail Url',
            'provider_id' => 'Provider ID',
            'published_at' => 'Published At',
            'published_by' => 'Published By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProvider()
    {
        return $this->hasOne(NewsProviders::className(), ['id' => 'provider_id']);
    }
}
