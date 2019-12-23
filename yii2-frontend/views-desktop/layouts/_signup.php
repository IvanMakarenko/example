<?php

use app\core\forms\SignUpForm;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$model = new SignUpForm();
?>
<h2><?= Yii::t('app', 'Buy tokens'); ?></h2>
<?php $form = ActiveForm::begin([
    'action' => '/sign-up',
    'layout' => 'horizontal',
    'fieldConfig' => [
        'template' => "<div class=\"col-lg-12\">{input}</div>",
        'horizontalCssClasses' => [
            'offset' => null,
            'wrapper' => 'col-sm-12',
        ],
    ],
]); ?>

<?= $form->field($model, 'name')
    ->textInput([
        'placeholder' => Yii::t('app', 'First Name'),
    ]); ?>
<?= $form->field($model, 'surname')
    ->textInput([
        'placeholder' => Yii::t('app', 'Last Name'),
    ]); ?>
<?= $form->field($model, 'login')
    ->textInput([
        'placeholder' => Yii::t('app', 'Nick'),
    ]); ?>
<?= $form->field($model, 'email')
    ->input('email', [
        'placeholder' => Yii::t('app', 'Email'),
    ]); ?>
<?= $form->field($model, 'agree')
    ->checkbox()
    ->label(
        Yii::t('registration', 'I agree with the terms of the offer. {link}', [
            'link' => Html::a(Yii::t('registration', 'Read'), '#'),
        ])
        . Html::tag('div', Yii::t('registration', 'Citizens of the United States and China are not allowed to participate in the ICO according to the current legislation'), ['class' => 'text-danger'])
    ); ?>
<?= $form->field($model, 'isSubscribe')
    ->checkbox()
    ->label(Yii::t('registration', 'I want to receive newsletters')); ?>

<div class="form-group form-group-submit">
    <div class="col-lg-12">
        <?= Html::submitButton(Yii::t('app', 'Buy tokens'), [
            'class' => 'btn btn-buy',
            'name' => 'signup-button',
         ]); ?>
    </div>
</div>

<?php ActiveForm::end(); ?>
