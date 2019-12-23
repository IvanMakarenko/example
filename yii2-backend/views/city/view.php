<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\City */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'City', 'url' => ['index']];
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
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function ($model) {
                    $column = new \backend\components\columns\CityStatusColumn(['withLabel' => true]);
                    return $column->getDataCellValue($model, 'status', 0);
                }
            ],
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

                    foreach ($model->areas as $area) {
                        $polygon = $area->getPolygon();
                        $map->addOverlay($polygon);
                    }

                    return $map->display();
                },
            ],
            [
                'label' => 'Areas',
                'format' => 'raw',
                'value' => function ($model) {
                    $links = [];
                    foreach ($model->areas as $area) {
                        $links[] = Html::a($area->name, ['area/view', 'id' => $area->id], ['target' => '_blank']);
                    }
                    return implode(', ', $links);
                },
            ],
        ],
    ]); ?>

</div>
