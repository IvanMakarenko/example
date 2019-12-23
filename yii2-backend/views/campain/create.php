<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Campain */

$this->title = 'Create Campain';
$this->params['breadcrumbs'][] = ['label' => 'Campains', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="campain-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
