<?php

use yii\authclient\widgets\AuthChoice;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = Yii::t('personal', 'Personal information');
$this->beginContent('@app/views/layouts/cabinet.php', compact('user'));

$form = ActiveForm::begin([
    'id' => 'user-form',
    'action' => '/cabinet/update',
    'options' => ['class' => 'inline-form'],
]); ?>

<div>
    <?= $form->field($user->profile, 'last_name')
        ->textInput()
        ->label(Yii::t('app', 'Last Name')); ?>
    <?= $form->field($user->profile, 'first_name')
        ->textInput()
        ->label(Yii::t('app', 'First Name')); ?>
    <?= $form->field($user, 'login')
        ->textInput()
        ->label(Yii::t('app', 'Nick')); ?>
</div>
<div>
    <?= $form->field($user, 'email')
        ->input('email')
        ->label(Yii::t('app', 'Email')); ?>
    <?= $form->field($user->profile, 'phone')
        ->input('tel')
        ->label(Yii::t('app', 'Phone')); ?>
</div>
<div>
    <?= $form->field($user->profile, 'eth_address')
        ->textInput([
            'placeholder' => '0x0000000000000000000000000000000000000000',
        ]); ?>

    <?php $authAuthChoice = AuthChoice::begin([
        'baseAuthUrl' => ['/auth'],
        'popupMode' => true,
        'options' => [
            'class' => 'social-links form-group',
        ],
    ]); ?>
    <!--noindex-->
    <label class="control-label"><?= Yii::t('registration', 'Connect social networks'); ?></label>
    <div>
        <?php foreach ($authAuthChoice->getClients() as $client): ?>
            <?= $authAuthChoice->clientLink($client, Html::img('/images/social/' . $client->id . '.svg'), [
                'rel' => 'nofollow',
                'disabled' => $user->{$client->id . '_id'} ? true : false,
                'title' => $user->{$client->id . '_id'}
                    ? Yii::t('registration', 'Connected')
                    : Yii::t('registration', 'Connect?'),
            ]); ?>
        <?php endforeach; ?>
    </div>
    <!--/noindex-->
    <?php AuthChoice::end(); ?>

    <?= $form->field($user->subscribe ? $user->subscribe : new \app\core\entities\Subscribe(), 'status')
        ->checkbox([
            'value' => \app\core\entities\Subscribe::STATUS_ACTIVE,
        ])
        ->label(Yii::t('registration', 'Subscribe')); ?>

    <?= Html::submitButton(Yii::t('app', 'Save'), [
        'class' => 'btn btn-save pull-right',
        'name' => 'save-button',
    ]); ?>
</div>

<?php
ActiveForm::end();
$this->endContent();
?>