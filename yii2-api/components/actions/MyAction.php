<?php


namespace api\components\actions;

use Yii;
use yii\rest\Action;

class MyAction extends Action
{
    /**
     * Displays a model of current user.
     * @return \yii\db\ActiveRecordInterface the model being displayed
     */
    public function run()
    {
        $model = $this->findModel(Yii::$app->user->id);
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id, $model);
        }

        return $model;
    }
}