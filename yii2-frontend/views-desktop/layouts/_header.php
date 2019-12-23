<?php

use app\core\helpers\LangHelper;
use app\widgets\SwitchLang;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;

NavBar::begin([
    'brandLabel' => Html::img('/images/logo.svg', [
            'width' => 36,
            'height' => 36,
            'class' => 'logo',
            'alt' => 'MamboCoin',
        ]) . Html::tag('span', 'Mambo') . Html::tag('span', 'coin', ['class' => 'color-chartreuse']),
    'brandUrl' => Yii::$app->homeUrl,
    'options' => [
        'class' => 'navbar-main navbar-fixed-top',
    ],
]);

echo Nav::widget([
    'options' => ['class' => 'navbar-nav navbar-left'],
    'items' => [
        [
            'label' => Yii::t('app', 'Why we?'),
            'url' => ['/', '#' => 'why-we'],
        ],
        [
            'label' => Yii::t('app', 'White Paper'),
            'url' => ['/', '#' => 'white-paper'],
            'linkOptions' => ['class' => 'color-chartreuse'],
        ],
        [
            'label' => Yii::t('app', 'Roadmap'),
            'url' => ['/', '#' => 'roadmap'],
        ],
        [
            'label' => Yii::t('app', 'Team'),
            'url' => ['/', '#' => 'team'],
        ],
        [
            'label' => Yii::t('app', 'FAQ'),
            'url' => ['/faq'],
        ],
        [
            'label' => Yii::t('app', 'Contact us'),
            'url' => ['/contact'],
        ],
        [
            'label' => Yii::t('app', 'Buy tokens'),
            'url' => Yii::$app->user->isGuest ? '#' : '/cabinet/tokens',
            'linkOptions' => [
                'class' => 'btn-chartreus text-uppercase',
                'data-toggle' => Yii::$app->user->isGuest ? 'modal' : null,
                'data-target' => Yii::$app->user->isGuest ? '#modal-buy' : null,
            ],
        ],
    ],
]);

echo Nav::widget([
    'options' => ['class' => 'navbar-nav navbar-right'],
    'encodeLabels' => false,
    'items' => [
        [
            'label' => '',
            'url' => ['/admin'],
            'linkOptions' => [
                'class' => Yii::$app->user->can('admin') ? 'glyphicon glyphicon-briefcase' : 'hidden',
                'rel' => 'nofollow',
            ],
        ],
        [
            'label' => '',
            'url' => ['/cabinet'],
            'linkOptions' => [
                'class' => ['glyphicon', 'glyphicon-user', Yii::$app->user->isGuest ? 'hidden' : null],
                'title' => Yii::t('app', 'Personal profile'),
            ],
        ],
        [
            'label' => LangHelper::getCurrent()->label,
            'linkOptions' => ['class' => 'text-uppercase'],
            'items' => SwitchLang::getItems(),
        ],
//        [
//            'label' => SwitchLang::getCurrentIcon(),
//            'options' => [
//                'class' => 'bg-gradient',
//            ],
//        ],
    ],
]);

NavBar::end();
