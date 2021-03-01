<?php

namespace backend\controllers;

use app\exceptions\AppleException;
use app\models\Apple;
use app\models\AppleSearch;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use yii\web\NotFoundHttpException;
use app\models\EatForm;


/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index', 'create', 'fail', 'eat', 'add-hour'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new AppleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $this->layout = 'blank';

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
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $count = Apple::createApplies();
        $infoString = "Новых яблок: $count";

        Yii::$app->session->setFlash('info', $infoString);

        return $this->redirect('index');
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionFail($id)
    {
        $model = $this->findModel($id);
        try {
            $model->fail();
            Yii::$app->session->setFlash('info', "Яблока №{$model->id} упало");
        } catch (AppleException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', 'Неизвестная ошибка: ' . $e->getMessage());
        }

        return $this->redirect('index');
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionEat($id)
    {
        $model = new EatForm();
        if ($model->load(Yii::$app->request->post())) {
            $apple = $this->findModel($id);
            try {
                $apple->eat($model->eaten);
                Yii::$app->session->setFlash('info', "От яблока №{$apple->id} съедено {$model->eaten}%");
                if ($apple->eaten == 0) {
                    $apple->delete();
                    Yii::$app->session->addFlash('info', "Яблоко №{$apple->id} съедено полностью");
                }
                return $this->redirect(['index']);
            } catch (AppleException $e) {
                Yii::$app->session->setFlash('error', $e->getMessage());
            } catch (\Exception $e) {
                Yii::$app->session->setFlash('error', 'Неизвестная ошибка: ' . $e->getMessage());
            }
        }
        return $this->render('eat', ['model' => $model]);
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionAddHour($id)
    {
        $model = $this->findModel($id);
        try {
            $model->addHourFail();
            Yii::$app->session->setFlash('info', "Время падения яблока №{$model->id} увеличилось на 1 час");
        } catch (AppleException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', 'Неизвестная ошибка: ' . $e->getMessage());
        }

        return $this->redirect('index');
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

    /**
     * Finds the Apple model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Apple the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Apple::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
