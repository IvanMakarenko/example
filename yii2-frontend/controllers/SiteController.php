<?php

namespace app\controllers;


use app\core\entities\Novelty;
use app\core\entities\SocUser;
use app\core\forms\BountySignUpForm;
use app\core\forms\ContactForm;
use app\core\forms\LoginForm;
use app\core\forms\SignUpForm;
use Yii;
use yii\authclient\BaseOAuth;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['sign-up', 'bounty-sign-up', 'logout'],
                'rules' => [
                    [
                        'actions' => ['sign-up', 'bounty-sign-up'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                    'sign-up' => ['post'],
                    'bounty-sign-up' => ['post'],
                    'get-novelty' => ['post'],
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
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'onAuthSuccess'],
                'successUrl' => '/cabinet',
                'cancelUrl' => '/',
            ],
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
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
        return $this->render('landing/index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect('/cabinet');
        } else {
            Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
        }

        $model->password = '';
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Registration action.
     *
     * @return Response|string
     */
    public function actionSignUp()
    {
        if (!Yii::$app->user->isGuest) {
            Yii::$app->session->setFlash('error', Yii::t('registration', 'You are already authorized'));
            return $this->goHome();
        }

        $model = new SignUpForm();
        if ($model->load(Yii::$app->request->post()) && $model->registration()) {
            $message = Yii::t('registration', 'You have successfully registered, check your mail, in the near future you will receive a letter with the data for authorization on the site.');
            Yii::$app->session->setFlash('success', $message);
        } else {
            Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Registration new user for bounty program.
     *
     * @return Response|string
     */
    public function actionBountySignUp()
    {
        if (!Yii::$app->user->isGuest) {
            Yii::$app->session->setFlash('error', Yii::t('registration', 'You are already authorized'));
            return $this->redirect('/cabinet/bounty');
        }

        $model = new BountySignUpForm();
        if ($model->load(Yii::$app->request->post()) && $model->registration()) {
            $message = Yii::t('registration', 'You have submit request for bounty. We will review it and contact you within 2 days.');
            Yii::$app->session->setFlash('success', $message);
        } else {
            Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays bounty agrement.
     *
     * @return string
     */
    public function actionBountyAgree()
    {
        return $this->render('bounty-agree');
    }

    public function onAuthSuccess(BaseOAuth $client)
    {
        $user = SocUser::findByAuth($client);

        if (Yii::$app->user->isGuest) {
            if ($user) {    // User exists and need login
                $user->updateIfEmpty($client);
                Yii::$app->user->login($user);
            } else {        // User need registration
                try {
                    $user = SocUser::create($client);

                    Yii::$app->user->login($user);
                    $message = Yii::t('registration', 'You have successfully registered, check your mail, in the near future you will receive a letter with the data for authorization on the site.');
                    Yii::$app->session->setFlash('success', $message);
                } catch (\Exception $e) {
                    Yii::$app->session->setFlash('error', $e->getMessage());
                }
            }
        } elseif (!$user) {     // User authorized and want to set social network
            $user = SocUser::findIdentity(Yii::$app->user->id);
            $user->updateIfEmpty($client);
            Yii::$app->session->setFlash('success', Yii::t('registration', 'You have successfully add social network.'));
        }
    }

    public function actionFaq()
    {
        return $this->render('faq');
    }

    public function actionContact()
    {
        $model = new ContactForm();

        if ($model->load(Yii::$app->request->post()) && $model->contact())
        {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }

        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    public function actionSmartContract()
    {
        return $this->render('smart-contract');
    }

    public function actionGetNovelty()
    {
        if (Yii::$app->request->post('noveltyID') && Yii::$app->request->isAjax) {
            Yii::error(Yii::$app->request->post()['noveltyID']);
            Yii::$app->response->format = Response::FORMAT_JSON;

            return Novelty::find()->where(['id' => Yii::$app->request->post('noveltyID')])->asArray()->one();
        }
    }
}
