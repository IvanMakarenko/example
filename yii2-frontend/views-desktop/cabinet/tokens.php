<?php

use app\core\helpers\FileHelper;
use app\core\helpers\EthHelper;
use supplyhog\ClipboardJs\ClipboardJsWidget;
use yii\bootstrap\Modal;
use yii\helpers\StringHelper;

$this->title = Yii::t('personal', 'My tokens');

$this->registerCssFile(FileHelper::getMinForProd('/css/common', '.css'));
$this->beginContent('@app/views/layouts/cabinet.php', compact('user'));
?>
<div class="b-page__section">
    <div class="b-steps b-steps_small">
        <div class="b-steps__label"><?= Yii::t('personal', 'Stages'); ?></div>
        <div class="b-steps__items">
            <div class="b-steps__item await">
                <div class="b-steps__item__icon">
                    <div><i class="fa fa-fw fa-play"></i></div>
                </div>
                <div class="b-steps__item__status"><?= Yii::t('personal', 'Close'); ?></div>
                <div class="b-steps__item__info">
                    <div class="b-steps__item__name"><?= Yii::t('personal', 'Private Sale'); ?></div>
                    <div class="b-steps__item__price">&nbsp;</div>
                </div>
                <div class="b-steps__item__date">
                    <?= Yii::$app->params['PrivateSale']['start']; ?> - <?= Yii::$app->params['PrivateSale']['end']; ?>
                </div>
            </div>
            <div class="b-steps__item active">
                <div class="b-steps__item__icon">
                    <div><i class="fa fa-fw fa-clock"></i></div>
                </div>
                <div class="b-steps__item__sale"><?= Yii::t('personal', 'Sale up to {0}%', 50); ?></div>
                <div class="b-steps__item__status"><?= Yii::t('personal', 'Active'); ?></div>
                <div class="b-steps__item__info">
                    <div class="b-steps__item__name">Pre ICO</div>
                    <div class="b-steps__item__price">&nbsp;</div>
                </div>
                <div class="b-steps__item__date">
                    <?= Yii::$app->params['PreICO']['start']; ?> - <?= Yii::$app->params['PreICO']['end']; ?>
                </div>
            </div>
            <div class="b-steps__item await">
                <div class="b-steps__item__icon">
                    <div><i class="fa fa-fw fa-clock"></i></div>
                </div>
                <div class="b-steps__item__status"><?= Yii::t('personal', 'Expectation'); ?></div>
                <div class="b-steps__item__info">
                    <div class="b-steps__item__name">ICO</div>
                    <div class="b-steps__item__price">&nbsp;</div>
                </div>
                <div class="b-steps__item__date">
                    <?= Yii::$app->params['ICO']['start']; ?> - <?= Yii::$app->params['ICO']['end']; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="b-page__section">
    <div class="b-price">
        <div class="b-price__title">
            <?= Yii::t('personal', 'Cost of the token'); ?>
        </div>
        <div class="b-price__wrap">
            <div class="b-price__left">
                <div class="b-price__value">1<sup> MBC</sup> = <?= EthHelper::MBC_COURSE_ETH; ?><sup> ETH</sup></div>
            </div>
            <div class="b-price__right">
                <div class="b-price__gift glyphicon glyphicon-gift"></div>
                <div class="b-price__sale">
                    <?= Yii::t('personal', 'Sale up to {0}%', 50); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="b-page__section">
    <div class="b-calc i-mt20">
        <div class="b-calc__lev1">
            <div class="b-calc__lev1__wrap">
                <label class="b-calc__col">
                    <span class="b-calc__col__name"><?= Yii::t('personal', 'Sum ETH'); ?></span>
                    <input id="buy-ETH"
                           type="number"
                           step="0.01"
                           min="<?= EthHelper::getMinBuy('ETH'); ?>"
                           max="<?= EthHelper::getMaxBuy('ETH'); ?>"
                           value="<?= EthHelper::getMinBuy('ETH'); ?>"
                           class="b-calc__col__inp i-inp">
                </label>
                <div class="b-calc__separ">=</div>
                <label class="b-calc__col">
                    <span class="b-calc__col__name">
                        <small data-tooltip="<?= Yii::t('personal', 'The minimum number of tokens to purchase: {0}', 100); ?>">
                            <i class="fas fa-question-circle i-c-b i-f-r"></i>
                        </small>
                        MBC
                    </span>
                    <input id="buy-MBC"
                           oninput="this.value = this.value.replace(',', '.').replace(/[^0-9.]+/, ' '.trim()); $('#buy-ETH').val(this.value * $(this).data('course'));"
                           type="number"
                           min="<?= EthHelper::getMinBuy('MBC'); ?>"
                           max="<?= EthHelper::getMaxBuy('MBC'); ?>"
                           value="<?= EthHelper::getMinBuy('MBC'); ?>"
                           class="b-calc__col__inp i-inp">
                </label>
            </div>
            <div class="b-calc__lev1__wrap">
<!--                <div class="b-calc__separ">+</div>-->
<!--                <div class="b-calc__col b-calc__col_sale">0% <small>Sale</small></div>-->
                <div class="b-calc__separ">+</div>
                <div class="b-calc__col b-calc__col_bonus">
                    <span id="bonus">0%</span>
                    <small>Bonus %</small>
                </div>
                <div class="b-calc__separ">=</div>
                <div class="b-calc__col b-calc__col_result">
                    <span id="result"><?= EthHelper::getMinBuy('MBC'); ?></span>
                    <small>MBC</small>
                </div>
            </div>
            <div class="b-calc__lev1__wrap">
                <button class="b-calc__btn i-btn i-btn_large btn-save"
                        data-toggle="modal"
                        data-target="#tokens-buy">
                    <?= Yii::t('personal','Buy'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="alert alert-danger">
    <?= Yii::t('personal', 'Be sure that you pay from your own wallet. Smart contract mint MBC for sender, don`t use resellers or virtual wallet.'); ?>
</div>

<div class="c-page__section">
    <div class="c-empty">
        <div class="c-empty__title">
            <?= Yii::t('personal', 'Token not displaying'); ?>
        </div>
        <div class="c-empty__content">
            <?= Yii::t('personal', 'After purchasing the MBC and confirming the transaction by the network, most Ethereum wallets will automatically pull up information about MBCs purchased in the near future.'); ?>
            <?= Yii::t('personal', 'Otherwise, you yourself can add information about the available MamboCoin-ah on your wallet manually.'); ?>
            <br>
            <div class="text-left">
                <?= Yii::t('personal', 'To do this, click "Add your Token" and enter:'); ?>

                <?= ClipboardJsWidget::widget([
                    'tag' => 'div',
                    'text' => Yii::$app->params['MBC']['address'],
                    'label' => '<div>'
                        . Yii::t('personal', 'address') . ': '
                        . Yii::$app->params['MBC']['address']
                        . '<span>' . Yii::t('personal', 'Copy {0}', Yii::t('personal', 'Address')) . '</span>'
                        . '</div>',
                    'successText' => '<div>'
                        . Yii::t('personal', 'address') . ': '
                        . Yii::$app->params['MBC']['address']
                        . '<span><i class="fa fa-check"></i>'
                            . Yii::t('personal', '{0} copied!', Yii::t('personal', 'Address'))
                        . '</span>'
                        . '</div>',
                    'htmlOptions' => [
                        'class' => 'eth-wallet-copy',
                    ],
                ]); ?>
                <?= ClipboardJsWidget::widget([
                    'tag' => 'div',
                    'text' => Yii::$app->params['MBC']['name'],
                    'label' => '<div>'
                        . Yii::t('personal', 'Name') . ': '
                        . Yii::$app->params['MBC']['name']
                        . '<span>'
                            . Yii::t('personal', 'Copy {0}', Yii::t('personal', 'Name'))
                        . '</span>'
                        . '</div>',
                    'successText' => '<div>'
                        . Yii::t('personal', 'Name') . ': '
                        . Yii::$app->params['MBC']['name']
                        . '<span><i class="fa fa-check"></i>'
                            . Yii::t('personal', '{0} copied!', Yii::t('personal', 'Name'))
                        . '</span>'
                        . '</div>',
                    'htmlOptions' => [
                        'class' => 'eth-wallet-copy',
                    ],
                ]); ?>
                <?= ClipboardJsWidget::widget([
                    'tag' => 'div',
                    'text' => Yii::$app->params['MBC']['decimals'],
                    'label' => '<div>'
                        . Yii::t('personal', 'Number of decimal places') . ': '
                        . Yii::$app->params['MBC']['decimals']
                        . '<span>'
                            . Yii::t('personal', 'Copy {0}', Yii::t('personal', 'Decimals'))
                        . '</span>'
                        . '</div>',
                    'successText' => '<div>'
                        . Yii::t('personal', 'Number of decimal places') . ': '
                        . Yii::$app->params['MBC']['decimals']
                        . '<span><i class="fa fa-check"></i>'
                            . Yii::t('personal', '{0} copied!', Yii::t('personal', 'Decimals'))
                        . '</span>'
                        . '</div>',
                    'htmlOptions' => [
                        'class' => 'eth-wallet-copy',
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>

<?php $this->endContent(); ?>
<?php

Modal::begin([
    'id' => 'tokens-buy',
    'header' => Yii::t('personal','Payment waiting <small>rate 1 ETH: {0} USD</small>', EthHelper::getCourse()),
    'closeButton' => false,
    'size' => Modal::SIZE_LARGE,
]); ?>

    <div class="b-paytx__body">
        <div class="b-paytx__icon">
            <i class="fab fa-ethereum"></i>
        </div>
        <div class="b-paytx__title">
            <?= Yii::t('personal', 'Buy'); ?>
            <b class="mbc"><?= EthHelper::getMinBuy('MBC'); ?> MBC</b>
            <br>
            <?= Yii::t('personal', 'for'); ?>
            <b class="eth"><?= EthHelper::getMinBuy('ETH'); ?> ETH</b>
        </div>
<!--        <ul class="b-paytx__tabs">-->
<!--            <li class="">wallet</li>-->
<!--            <li class="active">Copy</li>-->
<!--            <li class="">Scan</li>-->
<!--        </ul>-->
    </div>
    <div class="b-paytx__footer">
        <div class="b-paytx__subtitle">
            <?= Yii::t('personal', 'Please send ETH to this address'); ?>
        </div>
        <div class="b-paytx__address"><?= Yii::$app->params['CroudSale']; ?></div>
        <ul class="b-paytx__copy">
            <li style="display: none;">
                <div>
                    <?= StringHelper::truncate(Yii::$app->params['CroudSale'], 6); ?>
                    <span><?= Yii::t('personal', 'Send'); ?></span>
                </div>
            </li>
            <?= ClipboardJsWidget::widget([
                'tag' => 'li',
                'text' => Yii::$app->params['CroudSale'],
                'label' => '<div>'
                    . StringHelper::truncate(Yii::$app->params['CroudSale'], 6)
                    . '<span>'
                    . Yii::t('personal', 'Copy {0}', Yii::t('personal', 'Address'))
                    . '</span>'
                    . '</div>',
                'successText' => '<div>'
                    . StringHelper::truncate(Yii::$app->params['CroudSale'], 6)
                    . '<span><i class="fa fa-check"></i>'
                        . Yii::t('personal', '{0} copied!', Yii::t('personal', 'Address'))
                    . '</span>'
                    . '</div>',
                'htmlOptions' => [
                    'class' => 'eth-wallet-copy',
                ],
            ]); ?>
            <?= ClipboardJsWidget::widget([
                'tag' => 'li',
                'text' => EthHelper::getMinBuy('ETH'),
                'label' => '<div>'
                    . '<i class="eth">' . EthHelper::getMinBuy('ETH') . ' ETH</i>'
                    . '<span>'
                        . Yii::t('personal', 'Copy {0}', Yii::t('personal', 'Amount'))
                    . '</span>'
                    . '</div>',
                'successText' => '<div>'
                    . '<i class="eth">' . EthHelper::getMinBuy('ETH') . ' ETH</i>'
                    . '<span><i class="fa fa-check"></i>'
                        . Yii::t('personal', '{0} copied!', Yii::t('personal', 'Amount'))
                    . '</span>'
                    . '</div>',
                'htmlOptions' => [
                    'class' => 'eth-amount-copy',
                ],
            ]); ?>
        </ul>
    </div>
<?php
Modal::end();