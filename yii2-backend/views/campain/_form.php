<?php

use common\models\Campain;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Campain */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="campain-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'user_id')->textInput() ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->dropDownList([
        Campain::STATUS_ACTIVE => 'Active',
        Campain::STATUS_INACTIVE => 'Inactive',
        Campain::STATUS_WAIT_CONFIRM => 'Wait Confirm',
        Campain::STATUS_FINISHED => 'Finished',
    ]) ?>

    <?= $form->field($model, 'type')->dropDownList([
        Campain::TYPE_CUSTOM => 'Custom',
        Campain::TYPE_BASIC => 'Basic',
        Campain::TYPE_GOLD => 'Gold',
    ]) ?>

    <?= $form->field($model, 'source')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'budget')->textInput() ?>

    <?= $form->field($model, 'min_price')->textInput() ?>

    <?= $form->field($model, 'max_price')->textInput() ?>

    <?= $form->field($model, 'count')->textInput() ?>

    <?= $form->field($model, 'time_from')->textInput() ?>

    <?= $form->field($model, 'time_to')->textInput() ?>

    <?= $form->field($model, 'datetime_from')->textInput() ?>

    <?= $form->field($model, 'datetime_to')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'finished_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
