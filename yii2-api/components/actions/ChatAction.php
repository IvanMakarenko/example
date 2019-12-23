<?php


namespace api\components\actions;

use Yii;
use yii\data\ActiveDataProvider;
use yii\rest\IndexAction as Action;

class ChatAction extends Action
{
    public $isNew = false;

    protected function prepareDataProvider()
    {
        $requestParams = Yii::$app->getRequest()->getBodyParams();
        if (empty($requestParams)) {
            $requestParams = Yii::$app->getRequest()->getQueryParams();
        }

        $filter = null;
        if ($this->dataFilter !== null) {
            $this->dataFilter = Yii::createObject($this->dataFilter);
            if ($this->dataFilter->load($requestParams)) {
                $filter = $this->dataFilter->build();
                if ($filter === false) {
                    return $this->dataFilter;
                }
            }
        }

        if ($this->prepareDataProvider !== null) {
            return call_user_func($this->prepareDataProvider, $this, $filter);
        }

        /* @var $modelClass \yii\db\BaseActiveRecord */
        $modelClass = $this->modelClass;

        $query = $modelClass::find()
            ->orWhere(['or',
                ['owner_id' => Yii::$app->user->id],
                ['recipient_id' => Yii::$app->user->id],
            ]);

        if ($this->isNew) {
            $query->andWhere(['read_at' => null]);
        }

        if (!empty($filter)) {
            $query->andWhere($filter);
        }

        $models = $query->all();
        foreach ($models as $model) {
            $model->read_at = date('Y-m-d H:i:s');
            $model->save(false);
        }

        return Yii::createObject([
            'class' => ActiveDataProvider::className(),
            'models' => $models,
//            'query' => $query,
            'pagination' => [
                'params' => $requestParams,
            ],
            'sort' => [
                'params' => $requestParams,
            ],
        ]);
    }
}