<?php

namespace app\controllers;


use app\core\entities\BountyQuest;
use app\core\entities\User;
use app\core\forms\BountyQuestsSearch;
use app\core\forms\BountyRequestForm;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

class BountyController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'sign-up' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Displays bounty functional.
     *
     * @return string
     */
    public function actionIndex()
    {
        if (Yii::$app->user->can('bounty')) {
            $searchModel = new BountyQuestsSearch([
                'user_id' => Yii::$app->user->identity->id,
            ]);
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'user' => Yii::$app->user->identity,
            ]);
        } elseif (Yii::$app->user->identity->role == User::ROLE_BOUNTY_REQUEST) {
            return $this->render('wait-answer', ['user' => Yii::$app->user->identity]);
        }
        return $this->render('request', ['user' => Yii::$app->user->identity]);
    }

    public function actionCreate()
    {
        $model = new BountyQuest([
            'scenario' => BountyQuest::SCENARIO_CREATE,
            'status' => BountyQuest::STATUS_CHECK,
            'user_id' => Yii::$app->user->identity->id,
        ]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('admin', 'All data has been saved successfully'));
            return $this->redirect(['/cabinet/bounty']);
        }

        return $this->render('create', [
            'model' => $model,
            'user' => Yii::$app->user->identity,
        ]);
    }

    /**
     * Create user`s request for take part in bounty program.
     *
     * @return Response|string
     */
    public function actionSignUp()
    {
        $model = new BountyRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->request()) {
            $message = Yii::t('registration', 'You have submit request for bounty. We will review it and contact you within 2 days.');
            Yii::$app->session->setFlash('success', $message);
        } else {
            Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
        }

        return $this->redirect(Yii::$app->request->referrer);
    }
}
