<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\CampainSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Campains';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="campain-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Campain', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'user_id',
            'status',
            'type',
            'title',
            'source',
            'budget',
            //'min_price',
            //'max_price',
            //'count',
            //'time_from',
            //'time_to',
            //'datetime_from',
            //'datetime_to',
            //'created_at',
            //'finished_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
