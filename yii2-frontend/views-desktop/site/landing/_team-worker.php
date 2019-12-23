<?php

use yii\helpers\Html;

?>
<div class="col-xs-3">
    <?= Html::img($user->photo . '?date=20180917', [
        'class' => 'worker-img img-responsive greyscale',
    ]); ?>

    <p class="fio">
        <?= Yii::t('team', $user->profile->first_name); ?>
        <?= Yii::t('team', $user->profile->last_name); ?>
    </p>

    <?= \app\widgets\ReadMore::widget([
        'label' => Yii::t('app', 'Read more'),
        'content' => Yii::t('team', $user->about),
        'length' => 60,
        'options' => [
            'tag' => 'p',
            'class' => 'about',
        ],
    ]); ?>

    <?= \app\widgets\SocialLinks::widget([
        'socials' => [
            'vk' => $user->vk_link,
            'facebook' => $user->facebook_link,
            'linkedin' => $user->linkedin_link,
        ],
    ]); ?>
</div>
