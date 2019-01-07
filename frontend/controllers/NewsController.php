<?php

namespace frontend\controllers;

use Yii;
use frontend\models\News;
use frontend\models\NewsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
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

    public function actionTest(){
        $string = Yii::$app->translate->translate('mr', 'en', 'सोलापूर महानगरपालिकेच्या विधान सल्लागार पदी राहुल कुलकर्णी');
        $string['data']['translations'][0]['translatedText'];
    }

    
    /**
     * Lists all News models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new NewsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('/news/index', [
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
		$randomString = $this->gfRandKey();
                $fileName = $randomString . '.' . $model->video->extension;
                $filePath = '/opt/streaming/uploads/videos/' . preg_replace('/\s+/', '',$fileName);
                $model->video->saveAs($filePath);
                $model->video_url = $fileName;
		$model->post_url = $randomString;
                $sec = 20;
                $movie = $filePath;
		$thumbnail = '/usr/share/nginx/html/newsapp/frontend/web/uploads/thumbnails/'.preg_replace('/\s+/', '', $randomString .'.png');

                $ffmpeg = \FFMpeg\FFMpeg::create([
                    //'ffmpeg.binaries'  => exec('which ffmpeg'),
                    //'ffprobe.binaries' => exec('which ffprobe')
		         'ffmpeg.binaries'  => '/opt/bin/ffmpeg',
			 'ffprobe.binaries' => '/opt/bin/ffprobe'
                ]);

                $video = $ffmpeg->open($movie);
		$video->filters()->resize(new \FFMpeg\Coordinate\Dimension(320, 240))->synchronize();

                $frame = $video->frame(\FFMpeg\Coordinate\TimeCode::fromSeconds($sec));
                $frame->save($thumbnail);

                $model->thumbnail_url = '/uploads/thumbnails/' . substr($thumbnail, strrpos($thumbnail,"/") + 1);
            }

            $model->published_at = round(microtime(true) * 1000);
	    #$model->published_by = $model->provider_id = Yii::$app->user->identity->id;
	    $model->published_by = Yii::$app->user->identity->id == 2 ? 3 : (Yii::$app->user->identity->id == 3 ? 2 : Yii::$app->user->identity->id);
            $model->provider_id = Yii::$app->user->identity->id == 2 ? 3 : (Yii::$app->user->identity->id == 3 ? 2 : Yii::$app->user->identity->id);
	    try {
              $string = Yii::$app->translate->translate('mr', 'en', $model->title);
	      $model->en_title = $string['data']['translations'][0]['translatedText'];
	    } catch(Exception $e) {
	    }
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

    public function gfRandKey($minlength=12, $maxlength=12, $useupper=true, $usespecial=false, $usenumbers=true)

{

    $charset = "abcdefghijklmnopqrstuvwxyz";
    $key = 'temp';

    if ($useupper) $charset .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";

    if ($usenumbers) $charset .= "0123456789";

    if ($usespecial) $charset .= "~@#$%^*()_±={}|][";

    for ($i=0; $i<$maxlength; $i++) $key .= $charset[(mt_rand(0,(strlen($charset)-1)))];

    return $key;

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

	public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }


}
