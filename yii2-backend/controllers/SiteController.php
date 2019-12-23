<?php
namespace backend\controllers;

use common\models\Area;
use common\models\DriverLog;
use common\models\entities\map\Polygon;
use common\models\LoginForm;
use dosamigos\google\maps\LatLng;
use dosamigos\google\maps\Map;
use Yii;

/**
 * Site controller
 */
class SiteController extends BaseSiteController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['rules'][] = [
            'actions' => ['login'],
            'allow' => true,
        ];

        return $behaviors;
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
        $map = new Map([
            'center' => LatLng::createFromString('43.717899,-79.6582408'),
            'zoom' => 10,
            'width' => '100%',
            'height' => 700,
        ]);

        // add drivers
        $logIDs = DriverLog::find()
            ->andCreatedLastMinutes()
            ->groupBy('user_id')
            ->max('id');
        $logs = DriverLog::findAll(['id' => $logIDs]);
        foreach ($logs as $driverLog) {
            $map->addOverlay($driverLog->getMarker());
            $map->setCenter($driverLog->getLatLng());
        }
        foreach (Area::find()->all() as $area) {
            $map->addOverlay($area->getPolygon());
        }

        return $this->render('index', compact('map'));
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
