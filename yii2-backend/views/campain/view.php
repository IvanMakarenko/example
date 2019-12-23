<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Campain */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Campains', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="campain-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'status',
            'type',
            'source',
            'budget',
            'min_price',
            'max_price',
            'count',
            'time_from',
            'time_to',
            'datetime_from',
            'datetime_to',
            'created_at',
            'finished_at',
        ],
    ]) ?>

</div>
