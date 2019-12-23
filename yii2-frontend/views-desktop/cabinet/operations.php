<?php

use kartik\grid\GridView;

/* @var $this \yii\web\View view component instance */
/* @var $dataProvider \yii\data\ActiveDataProvider */
/* @var $searchModel \app\core\forms\BalanceLogsSearch */

$this->title = Yii::t('personal', 'My operations');
$this->beginContent('@app/views/layouts/cabinet.php', compact('user'));

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'striped' => true,
    'bordered' => false,
    'layout' => '{items}{pager}',
    'formatter' => [
        'class' => 'yii\i18n\Formatter',
        'nullDisplay' => '',
    ],
    'emptyText' => Yii::t('personal', 'You have not done any operations yet.'),
    'columns' => [
        'created_at:datetime',
        'tokens',
        'additional:raw',
    ],
]);

$this->endContent(); ?>