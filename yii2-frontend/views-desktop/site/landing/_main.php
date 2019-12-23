<?php

use app\core\helpers\EthHelper;
use app\widgets\Timer;
use app\widgets\SocialLinks;
use yii\bootstrap\Progress;
use yii\helpers\Html;

?>
<section class="site-index">
    <div class="container">
        <div class="row">
            <div class="col-sm-7">
                <h1 class="site-header">
                    <?= Yii::t('app', 'A currency that erases boundaries {0} of e-commerce', '<br>'); ?>
                </h1>
                <hr class="site-hr-397">
                <p class="site-additional">
                    <?= Yii::t('app', 'Buy and pay for purchases with {0} MamboCoin all over the world.', '<br>'); ?>
                </p>

                <?= SocialLinks::widget(); ?>
            </div>
            <div class="col-sm-5">
                <div class="ico-preform">
                    <?= Timer::widget(); ?>

                    <?= Progress::widget([
                        'label' => '', // Yii::$app->formatter->asDecimal(EthHelper::getTotalSupply()),
                        'percent' => EthHelper::getPercent(),
                    ]); ?>
                    <div class="progress-label clearfix">
                        <span class="pull-left">
                            Soft cap
                            <br>
                            <?= Yii::t('app', '{0} ETH', Yii::$app->formatter->asDecimal(EthHelper::getSoftCup('ETH'))); ?>
                        </span>
                        <span class="pull-right text-right">
                            Hard cap
                            <br>
                            <?= Yii::t('app', '{0} ETH', Yii::$app->formatter->asDecimal(EthHelper::getHardCup('ETH'))); ?>
                        </span>
                    </div>

                    <div class="ico-preform-buttons clearfix">
                        <?= Html::a(Yii::t('app', 'Buy tokens'), Yii::$app->user->isGuest ? '#' : '/cabinet/tokens', [
                            'class' => 'btn btn-buy pull-left',
                            'name' => 'buy-token-button',
                            'data-toggle' => Yii::$app->user->isGuest ? 'modal' : null,
                            'data-target' => Yii::$app->user->isGuest ? '#modal-buy' : null,
                        ]); ?>
                        <?=  Html::a(Yii::t('app', 'Get tokens'), Yii::$app->user->isGuest ? '#' : '/cabinet/bounty', [
                            'class' => 'btn btn-get pull-right',
                            'name' => 'get-token-button',
                            'data-toggle' => Yii::$app->user->isGuest ? 'modal' : null,
                            'data-target' => Yii::$app->user->isGuest ? '#modal-get' : null,
                        ]); ?>
                    </div>

                    <div class="ico-preform-rating clearfix">
                        <!-- <a href="http://icopulse.com/ico/mambocoin" target="_blank" title="Mambocoin">
                            <img src="https://icopulse.com/widget/v/ma/mambocoin.svg" width="100" height="100" border="0" alt="Mambocoin">
                        </a> -->

                        <a href="https://icobench.com/ico/mambocoin" target="_blank" rel="nofollow" title="Mambocoin on ICObench">
                            <img border="0" src="https://icobench.com/rated/mambocoin?shape=square&size=s" width="100" height="100" alt="Mambocoin ICO rating"/>
                        </a>

                        <a href="https://icomarks.com/ico/mambocoin" target="_blank" rel="nofollow" title="Mambocoin">
                            <img border="0" src="https://icomarks.com/widget/m/mambocoin/square.svg" width="100" height="100" alt="Mambocoin ICO rating"/>
                        </a>

                        <a href="https://foundico.com/ru/ico/mambocoin.html" target="_blank" rel="nofollow" title="Mambocoin on Foundico.com">
                            <img width="100" height="100" border="0" src="https://foundico.com/widget/?p=22382&f=s" alt="Mambocoin score on Foundico.com" />
                        </a>

                        <a href="http://toptokensales.com/ru/catalog/mambocoin/" target="_blank" rel="nofollow" title="MamboCoin - Top Token Sales" class="toptokensales">
                            <img width="195" height="50" border="0" src="http://toptokensales.com/wp-content/themes/ico/img/logo.svg" alt="MamboCoin - Top Token Sales" />
                            <div>
                                <span class="glyphicon glyphicon-star"></span>
                                <span class="glyphicon glyphicon-star"></span>
                                <span class="glyphicon glyphicon-star"></span>
                                <span class="glyphicon glyphicon-star"></span>
                                <span class="glyphicon glyphicon-star"></span>
                            </div>
                        </a>

                        <div id="icoholder-widget-big-green-listed-24458"></div>
                        <script type="application/javascript" async="async" src="https://icoholder.com/en/widget/big-green-listed/24458.js"></script>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="site-index-footer hidden">
    <div class="container">
        <span class="title"><?= Yii::t('app', 'The last purchase was made from:'); ?></span>
        <span class="last-purchase">
            <!-- @todo realize widget-->
            Россия
            <?= Html::img('/images/lang/ru.svg', [
                'height' => 16,
                'width' => 20,
            ]); ?>
        </span>
    </div>
</div>