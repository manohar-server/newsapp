<?php

namespace frontend\controllers;

use Yii;
use frontend\models\News;
use frontend\models\NewsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * NewsController implements the CRUD actions for News model.
 */
class NewsController extends Controller
{
    /**
     * {@inheritdoc}
     */
/*    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }*/

public function behaviors()
{
    return [
        'access' => [
            'class' => \yii\filters\AccessControl::className(),
            'only' => ['index','create', 'update', 'delete'],
            'rules' => [
                // deny all POST requests
                //[
                //    'allow' => false,
                //    'verbs' => ['POST']
                //],
                // allow authenticated users
                [
                    'allow' => true,
                    'roles' => ['@'],
                ],
                // everything else is denied
            ],
        ],
    ];
}

    
    /**
     * Lists all News models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new NewsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single News model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Finds the News model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return News the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = News::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Creates a new News model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new News();

        if ($model->load(Yii::$app->request->post())){

            $model->video = UploadedFile::getInstance($model, 'video');

            if ($model->video && $model->validate()) {
                $fileName = $model->video->baseName . '.' . $model->video->extension;
                $filePath = '/opt/streaming/uploads/videos/' . preg_replace('/\s+/', '',$fileName);
                $model->video->saveAs($filePath);
                $model->video_url = $fileName;

                $sec = 1;
                $movie = $filePath;
                //$thumbnail = '/opt/streaming/uploads/thumbnails/'. $model->video->baseName .'.png';
		$thumbnail = '/usr/share/nginx/html/newsapp/frontend/web/uploads/thumbnails/'.preg_replace('/\s+/', '', $model->video->baseName .'.png');

                $ffmpeg = \FFMpeg\FFMpeg::create([
                    //'ffmpeg.binaries'  => exec('which ffmpeg'),
                    //'ffprobe.binaries' => exec('which ffprobe')
		         'ffmpeg.binaries'  => '/opt/bin/ffmpeg',
			 'ffprobe.binaries' => '/opt/bin/ffprobe'
                ]);

                $video = $ffmpeg->open($movie);

                $frame = $video->frame(\FFMpeg\Coordinate\TimeCode::fromSeconds($sec));
                $frame->save($thumbnail);

                $model->thumbnail_url = '/uploads/thumbnails/' . substr($thumbnail, strrpos($thumbnail,"/") + 1);
            }

            $model->published_at = round(microtime(true));
	    $model->published_by = $model->provider_id = Yii::$app->user->identity->id;
            if($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing News model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->published_at = round(microtime(true));
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing News model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

}
