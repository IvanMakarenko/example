<?php

namespace api\components\controllers;

use common\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\ContentNegotiator;
use yii\rest\ActiveController;
use yii\web\Response;

class BaseController extends ActiveController
{
    public function verbs()
    {
        $verbs =  parent::verbs();
        $verbs['update'][] = 'POST';
        return $verbs;
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => CompositeAuth::class,
            'authMethods' => [
                HttpBasicAuth::class,
                HttpBearerAuth::class,
            ],
        ];

        $behaviors['content-negotiator'] = [
            'class' => ContentNegotiator::class,
            'formatParam' => 'format',
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
                'application/xml' => Response::FORMAT_XML,
            ],
        ];

        $behaviors['access'] = [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'allow' => true,
                    'roles' => [User::ROLE_ADMIN],
                ],
            ],
        ];

        return $behaviors;
    }
}