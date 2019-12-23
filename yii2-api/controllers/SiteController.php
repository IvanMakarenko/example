<?php

// namespace api\controllers; directory api don`t work, it fork by symlink
namespace frontend\controllers\api;

use api\components\controllers\BaseController;
use api\models\forms\LoginForm;
use api\models\forms\SignupForm;
use api\models\forms\StatisticForm;
use api\models\forms\PasswordResetRequestForm;
use common\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class SiteController extends BaseController
{
    public $modelClass = '';

    public function actions()
    {
        return [];
    }

    public function actionIndex()
    {
        return 'api';
    }

    public function actionRole()
    {
        return print_r(Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId()), true);
    }

    public function actionLogin()
    {
        $model = new LoginForm();
        $model->load(Yii::$app->request->bodyParams, '');
        if ($token = $model->auth()) {
            return $token;
        } else {
            return $model;
        }
    }

    public function actionSignup()
    {
        $model = new SignupForm();
        $model->load(Yii::$app->request->bodyParams, '');
        if ($token = $model->signup()) {
            return $token;
        } else {
            return $model;
        }
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        $model->load(Yii::$app->request->bodyParams, '');
        return $model->sendEmail();
    }

    /**
     * Requests update statistic and return actual.
     *
     * @return mixed
     */
    public function actionStatistic()
    {
        $model = new StatisticForm();
        $model->load(Yii::$app->request->bodyParams, '');
        $model->update();
        return $model;
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access'] = [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'allow' => true,
                    'roles' => [User::ROLE_ADMIN, User::ROLE_APP],
                ],
                [
                    'actions' => ['index', 'role', 'statistic'],
                    'allow' => true,
                    'roles' => ['@'],
                ],
            ],
        ];

         $behaviors['verbs'] = [
             'class' => VerbFilter::class,
             'actions' => [
                 'login'                     => ['post'],
                 'signup'                    => ['post'],
                 'request-password-reset'    => ['post'],
             ],
         ];

         return $behaviors;
    }
}
