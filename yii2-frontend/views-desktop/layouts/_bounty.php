<?php

use app\core\forms\BountySignUpForm;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$model = new BountySignUpForm();
?>
<h2><?= Yii::t('registration', 'Request for bounty'); ?></h2>
<?php $form = ActiveForm::begin([
    'action' => '/bounty-sign-up',
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
<?= $form->field($model, 'facebook_link')
    ->textInput([
        'placeholder' => 'https://www.facebook.com/xxxx',
    ]); ?>
<?= $form->field($model, 'twitter_link')
    ->textInput([
        'placeholder' => 'https://twitter.com/xxxxxxxxxxxx',
    ]); ?>
<?= $form->field($model, 'linkedin_link')
    ->textInput([
        'placeholder' => 'https://www.linkedin.com/in/xxx',
    ]); ?>
<?= $form->field($model, 'vk_link')
    ->textInput([
        'placeholder' => 'https://vk.com/xxxxxxxxxxxxxxxx',
    ]); ?>
<?= $form->field($model, 'email')
    ->input('email', [
        'placeholder' => Yii::t('app', 'Email'),
    ]); ?>
<?= $form->field($model, 'eth_address')
    ->textInput([
        'placeholder' => '0x0000000000000000000000000000000000000000',
    ]); ?>
<?= $form->field($model, 'agree')
    ->checkbox()
    ->label(
        Yii::t('registration', 'I agree with the terms of the offer. {link}', [
            'link' => Html::a(Yii::t('registration', 'Read'), ['/bounty-agree'], [
                'class' => 'color-chartreuse',
                'target' => '_blank',
            ]),
        ])
        . Html::tag('div', Yii::t('registration', 'Citizens of the United States and China are not allowed to participate in the ICO according to the current legislation'), ['class' => 'text-danger'])
    ); ?>
<?= $form->field($model, 'isSubscribe')
    ->checkbox()
    ->label(Yii::t('registration', 'I want to receive newsletters')); ?>

<div class="form-group form-group-submit">
    <div class="col-lg-12">
        <?= Html::submitButton(Yii::t('registration', 'Submit a request'), [
            'class' => 'btn btn-buy',
            'name' => 'signup-button',
         ]); ?>
    </div>
</div>

<?php ActiveForm::end(); ?>
