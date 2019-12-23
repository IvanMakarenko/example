<?php

namespace app\core\entities\queries;

use app\core\helpers\LangHelper;
use yii\db\ActiveQuery;

class NoveltyQuery extends ActiveQuery
{
    public function forCurrentLang()
    {
        return $this->andWhere(['lang' => [LangHelper::getDefault()->local, LangHelper::getCurrent()->local]]);
    }

    public function byDate()
    {
        return $this->orderBy(['date' => SORT_DESC]);
    }
}