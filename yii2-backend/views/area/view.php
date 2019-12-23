<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Area */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Areas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-view">

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
            'name',
            'city.name',
            'high_demand:boolean',
            [
                'label' => 'Polygon',
                'format' => 'raw',
                'value' => function ($model) {
                    $polygon = $model->getPolygon();
                    $map = new \dosamigos\google\maps\Map([
                        'center' => $polygon->getCenterOfBounds(),
                        'zoom' => 10,
                        'width' => '100%',
                        'height' => 500,
                    ]);
                    $map->addOverlay($polygon);
                    return $map->display();
                },
            ],
        ],
    ]) ?>

</div>
