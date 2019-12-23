<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\City */

$this->title = 'City search';
$this->params['breadcrumbs'][] = ['label' => 'City search', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="city-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="city-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'id')->textInput(['readonly' => true]) ?>

        <?= $form->field($model, 'name')->widget(\yii\jui\AutoComplete::class, [
            'clientOptions' => [
                'source'    => Url::to(['search']),
                'minLength' => '2',
                'select'    => new \yii\web\JsExpression("function( event, ui ) {
                    console.log(ui.item);
                    $('#city-id').val(ui.item.id);
                }"),
            ],
            'options' => [
                'class' => 'form-control',
            ],
        ]) ?>

        <div class="form-group">
            <?= Html::submitButton('Search City with Areas', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
