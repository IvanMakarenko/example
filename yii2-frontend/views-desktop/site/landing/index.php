<?php

/* @var $this yii\web\View */

use app\core\helpers\LangHelper;
use yii\helpers\Html;

$this->title = Yii::t('app', 'MamboCoin - A currency that erases the boundaries of e-commerce');

echo $this->render('_main');
echo $this->render('_why-we');
echo $this->render('_why-blockchain');
echo $this->render('_why-trust');
echo $this->render('_white-papaer');
echo $this->render('_media');

echo Html::beginTag('div', ['class' => 'advisers-team']);
echo $this->render('_team');
echo $this->render('_advisers');
echo Html::endTag('div');

if (LangHelper::isRu()) {
    echo $this->render('_feedbacks');
}
echo $this->render('_roadmap');
echo $this->render('_tokens-distribution');
echo $this->render('_news');
