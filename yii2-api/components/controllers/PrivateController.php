<?php

namespace api\components\controllers;

use api\components\actions\IndexAction;
use api\components\actions\MyAction;
use common\models\User;
use Yii;

class PrivateController extends BaseController
{
    public $ownerKey = 'user_id';

    private function withMyAction()
    {
        return $this->ownerKey == 'id';
    }

    public function actions()
    {
        $actions = parent::actions();

        $actions['index'] = [
            'class' => IndexAction::class,
            'ownerKey' => $this->ownerKey,
            'modelClass' => $this->modelClass,
            'checkAccess' => [$this, 'checkAccess'],
        ];

        if ($this->withMyAction()) {
            $actions['my'] = [
                'class' => MyAction::class,
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ];
        }

        return $actions;
    }

    public function checkAccess($action, $model = null, $params = [])
    {
        if (!Yii::$app->user->can(User::ROLE_ADMIN)) {
            if (in_array($this->action, ['view', 'update']) && $model->{$this->ownerKey} != Yii::$app->user->id) {
                throw new \yii\web\HttpException(500, 'You have access only for u data.!');
            }
        }
    }
}