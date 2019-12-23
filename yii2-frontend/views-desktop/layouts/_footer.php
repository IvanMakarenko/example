<?php

use app\core\forms\SubscribeForm;
use app\widgets\SocialLinks;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

?>
<footer class="footer">
    <div class="container">
        <div class="row">
            <a href="/" class="col-xs-12 col-sm-6 brand">
                <?= Html::img('/images/logo.svg', [
                    'width' => 36,
                    'height' => 36,
                    'class' => 'logo',
                    'alt' => 'MamboCoin',
                ]); ?>
                <?= Html::tag('span', 'Mambo') . Html::tag('span', 'coin', ['class' => 'color-chartreuse']); ?>
            </a>

            <?php
            $form = ActiveForm::begin([
                'action' => '/email/subscribe',
                'options' => ['class' => 'col-xs-12 col-sm-6 subscribe'],
            ]); ?>

            <?= $form->field(new SubscribeForm(), 'email', [
                    'template' => '{label}<div class="input-group">{input}<span class="input-group-btn">'
                        . Html::submitButton(Yii::t('app', 'subscribe'), [
                            'class' => 'btn text-uppercase',
                    ]) . '</span></div>{hint}{error}',
                ])
                ->input('email', [
                    'class' => 'form-control left',
                    'placeholder' => Yii::t('app', 'enter your e-mail'),
                ])
                ->label(false); ?>

            <?php ActiveForm::end(); ?>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <hr>
            </div>
        </div>
        <?= SocialLinks::widget(['options' => ['class' => 'pull-left']]); ?>
        <div class="doc-links text-right pull-right">
            <a href="/smart-contract" target="_blank">
                <?= Yii::t('app', 'Smart Contract'); ?>
            </a>
            <a href="/faq" target="_blank">
                <?= Yii::t('app', 'FAQ'); ?>
            </a>
            <a href="/docs/privacy-policy.pdf" target="_blank">
                <?= Yii::t('app', 'Privacy Policy'); ?>
            </a>
            <a href="/docs/terms-of-use.pdf" target="_blank">
                <?= Yii::t('app', 'Terms of Use'); ?>
            </a>
            <a href="/docs/cookie-policy.pdf" target="_blank">
                <?= Yii::t('app', 'Cookie Policy'); ?>
            </a>
            <a href="/bounty-agree" target="_blank">Bounty</a>
            <?= Yii::$app->user->can('admin')
                ? '<a href="/admin" target="_blank" class="glyphicon glyphicon-briefcase" "rel"="nofollow"></a>'
                : null; ?>
        </div>
    </div>
</footer>