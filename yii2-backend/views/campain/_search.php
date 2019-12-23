<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\search\CampainSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="campain-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'source') ?>

    <?= $form->field($model, 'budget') ?>

    <?= $form->field($model, 'min_price') ?>

    <?php // echo $form->field($model, 'max_price') ?>

    <?php // echo $form->field($model, 'count') ?>

    <?php // echo $form->field($model, 'time_from') ?>

    <?php // echo $form->field($model, 'time_to') ?>

    <?php // echo $form->field($model, 'datetime_from') ?>

    <?php // echo $form->field($model, 'datetime_to') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'finished_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
