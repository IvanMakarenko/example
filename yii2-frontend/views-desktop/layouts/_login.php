<?php

use app\core\forms\LoginForm;
use yii\authclient\widgets\AuthChoice;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$model = new LoginForm();
?>
<h2><?= Yii::t('registration', 'Sign in to your account'); ?></h2>
<?php $form = ActiveForm::begin([
    'action' => '/login',
    'layout' => 'horizontal',
    'fieldConfig' => [
        'template' => "<div class=\"col-lg-12\">{input}</div>",
    ],
]); ?>

<?= $form->field($model, 'username')
    ->textInput([
        'autofocus' => true,
        'placeholder' => Yii::t('registration', 'login'),
    ]); ?>
<?= $form->field($model, 'password')
    ->passwordInput([
        'placeholder' => Yii::t('registration', 'password'),
    ]); ?>

<div class="form-group form-group-submit">
    <div class="col-lg-12">
        <?= Html::submitButton(Yii::t('registration', 'Sign in'), [
            'class' => 'btn btn-get',
            'name' => 'login-button',
        ]); ?>
    </div>
</div>

<?php ActiveForm::end(); ?>

<p class="text-center signup-link">
    <?= Yii::t('registration', 'or'); ?>
    <a onclick="$('#signupform-name').focus()" class="color-chartreuse">
        <?= Yii::t('registration', 'sign up'); ?>
    </a>
</p>

<?php $authAuthChoice = AuthChoice::begin([
    'baseAuthUrl' => ['/auth'],
    'popupMode' => true,
    'options' => [
        'class' => 'social-links text-center',
    ],
]); ?>
<!--noindex-->
<p class="text-center"><?= Yii::t('registration', 'Sign in with social networks'); ?></p>
<?php foreach ($authAuthChoice->getClients() as $client): ?>
    <?= $authAuthChoice->clientLink($client, Html::img('/images/social/' . $client->id . '.svg'), [
        'rel' => 'nofollow',
    ]); ?>
<?php endforeach; ?>
<!--/noindex-->
<?php AuthChoice::end(); ?>