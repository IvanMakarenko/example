<?php

namespace app\controllers;


use app\core\entities\Subscribe;
use app\core\forms\SubscribeForm;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;

class EmailController extends Controller
{
    const PIXEL_PATH = 'images/letter/blank-mail.gif';


    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'subscribe' => ['POST'],
                ],
            ],
        ];
    }

    public function actionConfirm($token)
    {
        if ($subscribe = Subscribe::findOne(['token' => $token])) {
            $subscribe->confirm();
            Yii::$app->session->setFlash('success', Yii::t('app', 'You have been subscribed.'));
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app', 'You have not signed up yet.'));
        }
        return $this->redirect('/');
    }

    public function actionReadMail($token)
    {
        if ($subscribe = Subscribe::findOne(['token' => $token])) {
            if ($subscribe->status == Subscribe::STATUS_INACTIVE) {
                $subscribe->read();
            }
        }
        return Yii::$app->getResponse()->sendFile(self::PIXEL_PATH);
    }

    public function actionSubscribe()
    {
        $model = new SubscribeForm();

        if ($model->load(Yii::$app->request->post()) && $model->subscribe()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'You have subscribed to the newsletter, check your e-mail and confirm it by clicking on the link.'));
        } else {
            Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionUnsubscribe($email)
    {
        if ($subscribe = Subscribe::findByMail($email)) {
            $subscribe->status = Subscribe::STATUS_INACTIVE;
            $subscribe->save();
            Yii::$app->session->setFlash('success', Yii::t('app', 'You have been unsubscribed.'));
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app', 'You have not signed up yet.'));
        }
        return $this->redirect('/');
    }
}