<?php

use app\core\helpers\FileHelper;
use app\core\helpers\LangHelper;
use evgeniyrru\yii2slick\Slick;
use yii\helpers\Html;

$items = [];
if (LangHelper::isVn()) {
    $items[] = Html::tag('div', '<iframe class="embed-responsive-item" src="//www.youtube.com/embed/3U-bBZQBPh8?modestbranding=1&rel=0&showinfo=0"></iframe>', [
        'class' => 'embed-responsive embed-responsive-16by9',
    ]);
    $items[] = Html::tag('div', '<iframe class="embed-responsive-item" src="//www.youtube.com/embed/gG4xuHSR4vE?modestbranding=1&rel=0&showinfo=0"></iframe>', [
        'class' => 'embed-responsive embed-responsive-16by9',
    ]);
}
$items[] = Html::tag('div', '<iframe class="embed-responsive-item" src="//www.youtube.com/embed/gIY9lTYVd0k?modestbranding=1&rel=0&showinfo=0"></iframe>', [
    'class' => 'embed-responsive embed-responsive-16by9',
]);
if (LangHelper::isRu()) {
    $items[] = Html::tag('div', '<iframe class="embed-responsive-item" src="//www.youtube.com/embed/UGDmGBZy0z8?modestbranding=1&rel=0&showinfo=0"></iframe>', [
        'class' => 'embed-responsive embed-responsive-16by9',
    ]);
}
?>
<section id="white-paper" class="white-paper">
    <div class="container">
        <h2><?= Yii::t('app', 'White paper'); ?></h2>
        <hr>
        <div class="row">
            <div class="col-xs-6">
                <p>
                    <?= Yii::t('app', 'For 4 years, Mambo24 will be an international trading platform with its own currency, a system of lending purchases and P2P logistics.'); ?>
                    <br><br><br>
                    <?= Yii::t('app', 'Our goal is to become a world leader in e-commerce, to erase the boundaries for market expansions by combining countries into a single marketplace of goods and services.'); ?>
                    <br><br>
                    <?= Yii::t('app', 'Our social mission is to help small and medium-sized businesses development in the countries spanned with the project.'); ?>
                </p>

                <div class="btn-group">
                    <a href="<?= FileHelper::getWhitePaper() ? FileHelper::getWhitePaper() : FileHelper::getWhitePaper('en'); ?>" target="_blank" download class="btn btn-download">
                        <?= Yii::t('app', 'Download white paper'); ?>
                    </a>
                    <div class="btn-group">
                        <button type="button" class="btn btn-lang dropdown-toggle" data-toggle="dropdown">
                            <span id="lang-of-whitepaper"><?= FileHelper::getWhitePaper() ? LangHelper::getCurrent()->label : 'en'; ?></span>
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" role="menu">
                            <?php foreach (LangHelper::getList() as $language): ?>
                                <?php if (FileHelper::getWhitePaper($language->label)): ?>
                                    <li>
                                        <a href="<?= FileHelper::getWhitePaper($language->label); ?>" target="_blank" download>
                                            <?= $language->label; ?>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                            <?php /** @todo remove after add IT language  */ ?>
                            <li>
                                <a href="<?= FileHelper::getWhitePaper('it'); ?>" target="_blank" download>it</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <br><br>
                <div class="btn-group">
                    <a href="<?= FileHelper::getLightPaper() ? FileHelper::getLightPaper() : FileHelper::getLightPaper('en'); ?>" target="_blank" download class="btn btn-download">
                        <?= Yii::t('app', 'Download light paper'); ?>
                    </a>
                    <div class="btn-group">
                        <button type="button" class="btn btn-lang dropdown-toggle" data-toggle="dropdown">
                            <span id="lang-of-whitepaper"><?= FileHelper::getLightPaper() ? LangHelper::getCurrent()->label : 'en'; ?></span>
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" role="menu">
                            <?php foreach (LangHelper::getList() as $language): ?>
                                <?php if (FileHelper::getLightPaper($language->label)): ?>
                                    <li>
                                        <a href="<?= FileHelper::getLightPaper($language->label); ?>" target="_blank" download>
                                            <?= $language->label; ?>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
                <br><br>
                <div class="btn-group">
                    <a href="/docs/present-en.pdf?date=20180929" target="_blank" class="btn btn-download">
                        <?= Yii::t('app', 'present'); ?>
                    </a>
                    <div class="btn-group">
                        <button type="button" class="btn btn-lang dropdown-toggle" data-toggle="dropdown">
                            <span id="lang-of-whitepaper">en</span>
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" role="menu">
                            <li>
                                <a href="/docs/present-en.pdf?date=20180929" target="_blank">en</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-xs-6 slider-controlls-top">
                <?= Slick::widget([
                    'items' => $items,
                    // @see http://kenwheeler.github.io/slick/#settings
                    'clientOptions' => [
                        'autoplay' => false,
                        'dots'     => false,
                        'slidesToShow' => 1,
                        'slidesToScroll' => 1,
                        'arrows' => true,
                        'draggable' => true,
                        'prevArrow' => '<a class="left carousel-control" href="#w4" data-slide="prev">‹</a>',
                        'nextArrow' => '<a class="right carousel-control" href="#w4" data-slide="next">›</a>',
                        'responsive' => [
                            [
                                'breakpoint' => 768,
                                'settings' => [
                                    'slidesToShow' => 2,
                                    'slidesToScroll' => 1,
                                ],
                            ],
                            [
                                'breakpoint' => 480,
                                'settings' => [
                                    'slidesToShow' => 1,
                                    'slidesToScroll' => 1,
                                ],
                            ],
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</section>