<?php

namespace app\controllers;


use app\core\entities\Subscribe;
use app\core\entities\User;
use app\core\entities\UserProfile;
use app\core\forms\BalanceLogsSearch;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

class CabinetController extends Controller
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
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $user = Yii::$app->user->identity;
        $user->setScenario(User::SCENARIO_UPDATE);

        return $this->render('index', compact('user'));
    }

    /**
     * Verification investors became.
     *
     * @return string
     */
    public function actionVerification()
    {
        $model = Yii::$app->user->identity->profile;
        $model->setScenario(UserProfile::SCENARIO_VALIDATE);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                $model->status = UserProfile::STATUS_VERIFY;
            } else {
                $model->status = UserProfile::STATUS_ERROR;
            }

            $model->save(false);
            Yii::$app->session->setFlash('success', $model->statusLabel);
            if ($model->isVerify) {
                Yii::$app->session->setFlash('success', $model->statusDescription);
            }
            return $this->refresh();
        }

        if ($model->status !== UserProfile::STATUS_NOT_VERIFY) {
            $model->validate();
        }

        return $this->render('verification', [
            'user' => Yii::$app->user->identity,
            'profile' => $model,
        ]);
    }

    /**
     * Displays tokens and functional for buy them.
     *
     * @return string
     */
    public function actionTokens()
    {
        $user = Yii::$app->user->identity;

        if ($user->profile->isVerify) {
            return $this->render('tokens', compact('user'));
        }
        return $this->render('not-verification', compact('user'));
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionOperations()
    {
        $searchModel = new BalanceLogsSearch([
            'user_id' => Yii::$app->user->id,
        ]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('operations', [
            'user' => Yii::$app->user->identity,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionUpdate()
    {
        $user = Yii::$app->user->identity;
        $user->setScenario(User::SCENARIO_UPDATE);

        if ($user->load(Yii::$app->request->post()) && $user->save() &&
            $user->profile->load(Yii::$app->request->post()) && $user->profile->save()
        ) {
            $message = Yii::t('admin', 'All data has been saved successfully');
            Yii::$app->session->setFlash('success', $message);
        } else {
            Yii::$app->session->setFlash('error', ArrayHelper::merge($user->getErrorSummary(true), $user->profile->getErrorSummary(true)));
        }
        Subscribe::updateByUser($user, Yii::$app->request->post('Subscribe')['status']);

        return $this->redirect(Yii::$app->request->referrer);
    }
}
